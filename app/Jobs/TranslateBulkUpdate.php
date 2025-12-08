<?php

namespace App\Jobs;

use App\Services\ChatGPTService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslateBulkUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 2;
    public $maxExceptions = 1;
    public $timeout = 300; // 5 minute per chunk
    
    protected $modelTranslation;
    protected $foreignKey;
    protected $id;
    protected $locale;
    protected $column;
    protected $value;
    protected $metadata;
    
    public function __construct(
        $modelTranslation,
        $foreignKey,
        $id,
        $locale,
        $column,
        $value,
        $metadata = []
    ) {
        $this->modelTranslation = $modelTranslation;
        $this->foreignKey = $foreignKey;
        $this->id = $id;
        $this->locale = $locale;
        $this->column = $column;
        $this->value = $value;
        $this->metadata = $metadata;
    }
    
    public function handle()
    {
        try {
            // Determine source locale based on the current locale
            $sourceLocale = ($this->locale === 'en') ? 'ro' : 'en';
            
            $romanian_reference_value = DB::table($this->modelTranslation)
                ->where('locale', 'ro')
                ->where($this->foreignKey, $this->id)->first();
            
            // Verifică dacă e chunk sau translation normal
            if ( ! empty($this->metadata) && $this->metadata['isChunk'] ?? false) {
                // E chunk - traduce și salvează în cache
                $this->handleChunkTranslation(
                    $sourceLocale
                );
            } else {
                // E translation normal (nu e chunked) - salvează direct
                $this->handleNormalTranslation(
                    $sourceLocale,
                    $romanian_reference_value
                );
            }
        } catch (\Throwable $e) {
            Log::error('TranslateBulkUpdate failed: '.$e->getMessage(), [
                'id' => $this->id,
                'column' => $this->column,
                'isChunk' => $this->metadata['isChunk'] ?? false,
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Release the job back into the queue with delay
            $this->release(30);
        }
    }
    
    /**
     * Tratează traducerea unui chunk individual
     */
    private function handleChunkTranslation($sourceLocale, $romanian_reference_value): void
    {
        $chunkIndex = $this->metadata['chunkIndex'] ?? 0;
        $chunkSetId = $this->metadata['chunkSetId'] ?? null;
        $totalChunks = $this->metadata['totalChunks'] ?? 1;
        
        Log::debug('Translating chunk', [
            'id' => $this->id,
            'chunkSetId' => $chunkSetId,
            'chunkIndex' => $chunkIndex,
            'totalChunks' => $totalChunks,
        ]);
        
        // Traduce chunk-ul
        $translatedChunk = ChatGPTService::translate(
            $this->value,
            $this->locale,
            $sourceLocale,
            $romanian_reference_value->{$this->column} ?? null
        );
        
        // Verifică dacă traducerea e validă
        if (empty($translatedChunk)) {
            throw new \Exception("Translation failed for chunk {$chunkIndex}: Empty response from API");
        }
        
        // Salvează chunk-ul tradus în cache
        $cacheKey = 'translation_chunks:'.$chunkSetId;
        $translatedChunksData = Cache::get($cacheKey, []);
        $translatedChunksData[$chunkIndex] = $translatedChunk;
        
        // Salvează cu TTL de 2 ore (în caz de retry-uri)
        Cache::put($cacheKey, $translatedChunksData, 7200);
        
        Log::info('Chunk translated and cached', [
            'id' => $this->id,
            'chunkSetId' => $chunkSetId,
            'chunkIndex' => $chunkIndex,
            'chunkLength' => strlen($translatedChunk),
        ]);
    }
    
    /**
     * Tratează traducerea normală (nu chunked)
     */
    private function handleNormalTranslation($sourceLocale, $romanian_reference_value): void
    {
        // Traduce direct
        $translatedValue = ChatGPTService::translate(
            $this->value,
            $this->locale,
            $sourceLocale,
            $romanian_reference_value->{$this->column} ?? null
        );
        
        // Verifică dacă traducerea e validă
        if (empty($translatedValue)) {
            throw new \Exception('Translation failed: Empty response from API');
        }
        
        // Salvează direct în bază de date
        DB::table($this->modelTranslation)
            ->where('locale', $this->locale)
            ->where($this->foreignKey, $this->id)
            ->update([$this->column => $translatedValue]);
        
        Log::info('Translation saved successfully', [
            'id' => $this->id,
            'column' => $this->column,
            'locale' => $this->locale,
            'translatedLength' => strlen($translatedValue),
        ]);
    }
}