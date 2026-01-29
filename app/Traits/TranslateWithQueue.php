<?php

namespace App\Traits;

use App\Jobs\TranslateBulkUpdate;
use App\Jobs\TranslateContentJob;
use App\Services\ChatGPTService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait TranslateWithQueue
{
    use SlugGenerator;
    
    private $contentCharLimit = 1500;
    private $minChunkSize = 500;
    
    public function translateQueueByColumns($modelTranslation, $foreignKey, $locale, $id, ChatGPTService $chatGptService)
    {
        // Determine source locale based on the current locale
        $sourceLocale = ($locale === 'en') ? 'ro' : 'en';
        
        if ($modelTranslation === 'frontPage_translations') {
            $modelTranslation = 'front_page_translations';
            $foreignKey = 'front_page_id';
        }
        
        // Fetch the source record only once (Romanian or English, depending on the locale)
        $record = DB::table($modelTranslation)
            ->whereIn('locale', [$sourceLocale]) // Fetch only source language record
            ->where($foreignKey, '=', $id)
            ->first();
        
        if ( ! $record) {
            // Early exit if no record found for translation source
            return;
        }
        
        // Get all columns from the translation table dynamically (excluding id, foreign_key, and locale)
        $columns = Schema::getColumnListing($modelTranslation);
        
        $columns = array_diff($columns, ['id', $foreignKey, 'locale', 'online']); // Exclude the unwanted columns
        
        // Check if a record for the target language exists
        $existingRecord = DB::table($modelTranslation)
            ->where('locale', $locale)
            ->where($foreignKey, '=', $id)
            ->first();
        
        $newRecordData = [
            $foreignKey => $id,
            'locale' => $locale,
        ];
        
        if ($existingRecord === null) {
            // If no existing record, prepare to insert
            if ($locale === 'en') {
                $translatedName = isset($record->name)
                    ? $chatGptService->translate($record->name, $locale, $sourceLocale)
                    : null;
            } else {
                $romanianReferenceRecord = DB::table($modelTranslation)
                    ->where('locale', 'ro')
                    ->where($foreignKey, '=', $id)
                    ->first();
                
                if (isset($romanianReferenceRecord->name)) {
                    $translatedName = isset($record->name)
                        ? $chatGptService->translate($record->name, $locale, $sourceLocale, $romanianReferenceRecord->name)
                        : null;
                } else {
                    $translatedName = isset($record->name)
                        ? $chatGptService->translate($record->name, $locale, $sourceLocale)
                        : null;
                }
            }
            
            // Add name if translated
            if ($translatedName) {
                $newRecordData['name'] = $translatedName;
                
                // verifică dacă tabela are coloana 'slug'
                if (Schema::hasColumn($modelTranslation, 'slug')) {
                    $slugGenerated = $this->generateSlug($translatedName, $locale);
                    
                    $newRecordData['slug'] = $this->ensureUniqueSlug(
                        $slugGenerated,
                        $modelTranslation,
                        $locale,
                        $foreignKey,
                        $id
                    );
                }
            }
            
            //if (count($newRecordData) === 4) {
            // Insert the new record (if no existing record)
            DB::table($modelTranslation)->insert($newRecordData);
            //}
        }
        
        // Variabile pentru chunking
        $contentCharLimit = $this->contentCharLimit; // caractere per chunk
        $minChunkSize = $this->minChunkSize;     // Dimensiune minimă a unui chunk
        
        foreach ($columns as $column) {
            $value = $record->{$column};
            
            // If the target field is empty and the source field has a value
            if ( ! empty($value) && $column !== 'slug') {
                $columnsToUpdate[] = $column;
                
                // Dacă e câmpul 'content' și are prea mult conținut, dispatch în queue
                if ($column === 'content' && strlen($value) > $contentCharLimit) {
                    TranslateContentJob::dispatch(
                        $modelTranslation,
                        $foreignKey,
                        $id,
                        $locale,
                        $column,
                        $value,
                        $contentCharLimit,
                        $minChunkSize
                    );
                } else {
                    // Dispatch the job to the queue for each column
                    TranslateBulkUpdate::dispatch(
                        $modelTranslation,
                        $foreignKey,
                        $id,
                        $locale,
                        $column,
                        $value
                    );
                }
            }
        }
        
        
        // ✅ After you find the columns for translate
        if ( ! empty($columnsToUpdate) && $modelTranslation === 'blog_translations') {
            \App\Models\Blog::updateOnlineStatus($id);
        }
    }
    
    /**
     * Traduce conținutul mare sincron prin chunking
     *
     * @param  string  $modelTranslation
     * @param  string  $foreignKey
     * @param  int  $id
     * @param  string  $locale
     * @param  string  $column
     * @param  string  $content
     * @param  int  $contentCharLimit
     * @param  int  $minChunkSize
     */
    private function _translateContentWithChunking(
        $modelTranslation,
        $foreignKey,
        $id,
        $locale,
        $column,
        $content,
        $contentCharLimit,
        $minChunkSize,
        ChatGPTService $chatGptService
    ): void {
        Log::info('Content chunking started (synchronous)', [
            'id' => $id,
            'contentLength' => strlen($content),
        ]);
        
        // Extrage blocurile HTML
        $htmlBlocks = $this->extractHtmlBlocks($content);
        
        Log::info('HTML blocks extracted', [
            'id' => $id,
            'blockCount' => count($htmlBlocks),
        ]);
        
        // Creează chunks
        $chunks = $this->createChunksFromHtmlBlocks($htmlBlocks, $contentCharLimit);
        
        Log::info('Content split into chunks', [
            'id' => $id,
            'chunkCount' => count($chunks),
        ]);
        
        // Determine source locale
        $sourceLocale = ($locale === 'en') ? 'ro' : 'en';
        
        // Obține referința românească
        $romanian_reference_value = DB::table($modelTranslation)
            ->where('locale', $locale)
            ->where($foreignKey, $id)->first();
        
        $translatedChunks = [];
        
        // Traduce fiecare chunk sincron
        foreach ($chunks as $index => $chunk) {
            Log::debug('Translating chunk synchronously', [
                'id' => $id,
                'chunkIndex' => $index,
                'totalChunks' => count($chunks),
                'chunkLength' => strlen($chunk),
            ]);
            
            try {
                // Traduce chunk-ul
                $translatedChunk = $chatGptService->translate(
                    $chunk,
                    $locale,
                    $sourceLocale,
                    $romanian_reference_value->{$column} ?? null
                );
                
                // Verifică dacă traducerea e validă
                if (empty($translatedChunk)) {
                    throw new \Exception("Translation failed for chunk {$index}: Empty response from API");
                }
                
                $translatedChunks[] = $translatedChunk;
                
                Log::info('Chunk translated successfully', [
                    'id' => $id,
                    'chunkIndex' => $index,
                    'translatedLength' => strlen($translatedChunk),
                ]);
                
                // Pauză între chunks pentru a nu supraîncărca API-ul
                if ($index < count($chunks) - 1) {
                    sleep(2); // 2 secunde între chunk-uri
                }
            } catch (\Exception $e) {
                Log::error('Chunk translation failed', [
                    'id' => $id,
                    'chunkIndex' => $index,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }
        
        // Concatenează toate chunk-urile traduse
        $finalTranslation = implode('', $translatedChunks);
        
        if (empty($finalTranslation)) {
            throw new \Exception('Final translation is empty after consolidation');
        }
        
        Log::info('All chunks translated and consolidated', [
            'id' => $id,
            'totalChunks' => count($translatedChunks),
            'finalLength' => strlen($finalTranslation),
        ]);
        
        // Salvează traducerea finală în bază de date - O SINGURĂ DATĂ
        DB::table($modelTranslation)
            ->where('locale', $locale)
            ->where($foreignKey, $id)
            ->update([$column => $finalTranslation]);
        
        Log::info('Translation consolidated and saved to database', [
            'id' => $id,
            'column' => $column,
            'locale' => $locale,
            'finalLength' => strlen($finalTranslation),
        ]);
    }
    
    /**
     * Extrage doar primul nivel de tag-uri cu conținuturile lor complete (inclusiv tag-uri nested)
     *
     * @param  string  $content
     * @return array Fiecare element conține: ['tag' => 'p', 'openTag' => '<p...>', 'closeTag' => '</p>', 'content' => '...cu tot cu tag-urile nested...']
     */
    private function extractHtmlBlocks($content): array
    {
        $blocks = [];
        
        // Tag-urile de nivel 1 pe care le extragem
        $topLevelTags = 'p|div|h[1-6]|blockquote|article|section|aside|nav|header|footer';
        
        // Pattern: Capta tag-ul deschidere, apoi orice content (inclusiv tag-uri nested), apoi tag-ul inchidere
        $pattern = '/<('.$topLevelTags.')([^>]*)>((?:[^<]|<(?!\/\1>))*?)<\/\1>/is';
        
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
        
        $lastOffset = 0;
        
        // Procesează matcheurile
        for ($i = 0; $i < count($matches[0]); $i++) {
            $fullMatch = $matches[0][$i][0];
            $offset = $matches[0][$i][1];
            
            // Dacă e text netaguit înainte de acest tag, adaugă-l ca text plain
            if ($offset > $lastOffset) {
                $plainText = substr($content, $lastOffset, $offset - $lastOffset);
                if ( ! empty(trim($plainText))) {
                    $blocks[] = [
                        'type' => 'text',
                        'content' => $plainText,
                    ];
                }
            }
            
            $tag = strtolower($matches[1][$i][0]);
            $attributes = $matches[2][$i][0];
            $innerContent = $matches[3][$i][0];
            
            // Construiește tag-urile
            $openTag = '<'.$tag.$attributes.'>';
            $closeTag = '</'.$tag.'>';
            
            $blocks[] = [
                'type' => 'html',
                'tag' => $tag,
                'attributes' => $attributes,
                'openTag' => $openTag,
                'closeTag' => $closeTag,
                'content' => $innerContent,
            ];
            
            $lastOffset = $offset + strlen($fullMatch);
        }
        
        // Adaugă textul rămas la final
        if ($lastOffset < strlen($content)) {
            $plainText = substr($content, $lastOffset);
            if ( ! empty(trim($plainText))) {
                $blocks[] = [
                    'type' => 'text',
                    'content' => $plainText,
                ];
            }
        }
        
        return $blocks;
    }
    
    /**
     * Creează chunks din blocurile HTML, respectând limitele
     *
     * @param  array  $htmlBlocks
     * @param  int  $maxChunkSize
     * @return array
     */
    private function createChunksFromHtmlBlocks($htmlBlocks, $maxChunkSize): array
    {
        $chunks = [];
        $currentChunk = '';
        $currentChunkSize = 0;
        
        foreach ($htmlBlocks as $block) {
            if ($block['type'] === 'html') {
                $blockSize = strlen($block['openTag']) + strlen($block['content']) + strlen($block['closeTag']);
                
                // Dacă blocul singur e mai mare decât limita, fragmentează conținutul interior
                if ($blockSize > $maxChunkSize) {
                    // Salvează chunk-ul curent
                    if ( ! empty($currentChunk)) {
                        $chunks[] = trim($currentChunk);
                        $currentChunk = '';
                        $currentChunkSize = 0;
                    }
                    
                    // Fragmentează conținutul interior al tag-ului
                    $innerChunks = $this->splitTextBySentences($block['content'], $maxChunkSize);
                    
                    foreach ($innerChunks as $innerChunk) {
                        $reconstructed = $block['openTag'].$innerChunk.$block['closeTag'];
                        $chunks[] = $reconstructed;
                    }
                    continue;
                }
                
                // Dacă adăugarea blocului ar depăși limita
                if (($currentChunkSize + $blockSize > $maxChunkSize) && ! empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = $block['openTag'].$block['content'].$block['closeTag'];
                    $currentChunkSize = $blockSize;
                } else {
                    $currentChunk .= $block['openTag'].$block['content'].$block['closeTag'];
                    $currentChunkSize += $blockSize;
                }
            } else {
                // Text plain
                $blockSize = strlen($block['content']);
                
                if (($currentChunkSize + $blockSize > $maxChunkSize) && ! empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = $block['content'];
                    $currentChunkSize = $blockSize;
                } else {
                    $currentChunk .= $block['content'];
                    $currentChunkSize += $blockSize;
                }
            }
        }
        
        // Salvează ultimul chunk
        if ( ! empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }
        
        return $chunks;
    }
    
    /**
     * Împarte textul pe propoziții
     *
     * @param  string  $text
     * @param  int  $maxSize
     * @return array
     */
    private function splitTextBySentences($text, $maxSize): array
    {
        // Detectează propoziții
        $sentences = preg_split('/(?<=[.!?])\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        if (empty($sentences)) {
            return [$text];
        }
        
        $chunks = [];
        $currentChunk = '';
        $currentSize = 0;
        
        foreach ($sentences as $sentence) {
            $sentenceSize = strlen($sentence);
            
            if ($sentenceSize > $maxSize) {
                // Propoziție prea mare, o adaugă singură
                if ( ! empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                    $currentSize = 0;
                }
                $chunks[] = $sentence;
                continue;
            }
            
            if (($currentSize + $sentenceSize > $maxSize) && ! empty($currentChunk)) {
                $chunks[] = trim($currentChunk);
                $currentChunk = $sentence;
                $currentSize = $sentenceSize;
            } else {
                $currentChunk .= (empty($currentChunk) ? '' : ' ').$sentence;
                $currentSize += $sentenceSize + 1;
            }
        }
        
        if ( ! empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }
        
        return $chunks;
    }
}
