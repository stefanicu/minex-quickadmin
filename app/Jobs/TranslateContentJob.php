<?php

namespace App\Jobs;

use App\Services\ChatGPTService;
use DOMDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslateContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    // Increased attempts to tolerate transient requeues
    public $tries = 10;
    public $maxExceptions = 1;
    public $timeout = 300; // 5 minutes
    
    protected $modelTranslation;
    protected $foreignKey;
    protected $id;
    protected $locale;
    protected $column;
    protected $content;
    protected $contentCharLimit;
    protected $minChunkSize;
    
    protected $rateLimitKeyPrefix = 'gpt-translation-rate:';
    protected $rateLimitMaxAttempts = 1;
    protected $rateLimitDecaySeconds = 10;
    
    public function __construct(
        $modelTranslation,
        $foreignKey,
        $id,
        $locale,
        $column,
        $content,
        $contentCharLimit,
        $minChunkSize
    ) {
        $this->modelTranslation = $modelTranslation;
        $this->foreignKey = $foreignKey;
        $this->id = $id;
        $this->locale = $locale;
        $this->column = $column;
        $this->content = $content;
        $this->contentCharLimit = $contentCharLimit;
        $this->minChunkSize = $minChunkSize;
    }
    
    public function backoff()
    {
        return [60, 120];
    }
    
    public function handle()
    {
        $lockKey = "gpt-translation-lock:{$this->id}:{$this->locale}";
        $lock = Cache::lock($lockKey, 120);
        
        // If lock not available, re-dispatch the job delayed instead of releasing (avoids consuming attempts)
        if ( ! $lock->get()) {
            Log::debug('Could not acquire translation lock; re-dispatching delayed', [
                'id' => $this->id,
                'attempt' => $this->attempts(),
            ]);
            static::dispatch(
                $this->modelTranslation,
                $this->foreignKey,
                $this->id,
                $this->locale,
                $this->column,
                $this->content,
                $this->contentCharLimit,
                $this->minChunkSize
            )->delay(now()->addSeconds(20));
            return;
        }
        
        try {
            Log::info('TranslateContentJob started', [
                'id' => $this->id,
                'contentLength' => mb_strlen($this->content),
                'attempt' => $this->attempts(),
            ]);
            
            $sourceLocale = ($this->locale === 'en') ? 'ro' : 'en';
            
            $romanian_reference_value = DB::table($this->modelTranslation)
                ->where('locale', 'ro')
                ->where($this->foreignKey, $this->id)
                ->first();
            
            $htmlBlocks = $this->extractHtmlBlocks($this->content);
            
            Log::info('HTML blocks extracted', [
                'id' => $this->id,
                'blockCount' => count($htmlBlocks),
            ]);
            
            $chunks = $this->createChunksFromHtmlBlocks($htmlBlocks, $this->contentCharLimit);
            
            Log::info('Content split into chunks', [
                'id' => $this->id,
                'chunkCount' => count($chunks),
            ]);
            
            $cacheKey = "translate_chunks:{$this->id}:{$this->locale}";
            $translatedChunks = cache()->get($cacheKey, []);
            $failedChunkIndices = [];
            
            Log::debug('Cache state', [
                'id' => $this->id,
                'cachedChunks' => count($translatedChunks),
                'totalChunks' => count($chunks),
                'attempt' => $this->attempts(),
            ]);
            
            $rateLimiter = app(RateLimiter::class);
            $rateKey = $this->rateLimitKeyPrefix.$this->id.':'.$this->locale;
            
            foreach ($chunks as $index => $chunk) {
                if (isset($translatedChunks[$index])) {
                    Log::debug('Chunk already translated (from cache)', [
                        'id' => $this->id,
                        'chunkIndex' => $index,
                    ]);
                    continue;
                }
                
                // If rate limiter reached, re-dispatch delayed instead of releasing
                if ($rateLimiter->tooManyAttempts($rateKey, $this->rateLimitMaxAttempts)) {
                    $retryIn = $rateLimiter->availableIn($rateKey) ?: $this->rateLimitDecaySeconds;
                    Log::warning('Rate limit reached, re-dispatching delayed job', [
                        'id' => $this->id,
                        'retryIn' => $retryIn,
                    ]);
                    static::dispatch(
                        $this->modelTranslation,
                        $this->foreignKey,
                        $this->id,
                        $this->locale,
                        $this->column,
                        $this->content,
                        $this->contentCharLimit,
                        $this->minChunkSize
                    )->delay(now()->addSeconds($retryIn + 1));
                    return;
                }
                
                Log::debug('Translating chunk in queue job', [
                    'id' => $this->id,
                    'chunkIndex' => $index,
                    'totalChunks' => count($chunks),
                    'chunkLength' => mb_strlen($chunk),
                    'attempt' => $this->attempts(),
                ]);
                
                try {
                    $rateLimiter->hit($rateKey, $this->rateLimitDecaySeconds);
                    
                    $translatedChunk = ChatGPTService::translate(
                        $chunk,
                        $this->locale,
                        $sourceLocale,
                        $romanian_reference_value->{$this->column} ?? null
                    );
                    
                    if (empty($translatedChunk)) {
                        throw new \Exception("Empty response from API");
                    }
                    
                    $cacheLockKey = "translate_chunks_lock:{$this->id}:{$this->locale}";
                    $cacheLock = Cache::lock($cacheLockKey, 10);
                    
                    if ($cacheLock->get()) {
                        try {
                            $translatedChunks = cache()->get($cacheKey, []);
                            $translatedChunks[$index] = $translatedChunk;
                            cache()->put($cacheKey, $translatedChunks, 7200);
                        } finally {
                            $cacheLock->release();
                        }
                    } else {
                        $translatedChunks[$index] = $translatedChunk;
                        cache()->put($cacheKey, $translatedChunks, 7200);
                    }
                    
                    Log::info('Chunk translated successfully', [
                        'id' => $this->id,
                        'chunkIndex' => $index,
                        'translatedLength' => mb_strlen($translatedChunk),
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Chunk translation failed, will retry', [
                        'id' => $this->id,
                        'chunkIndex' => $index,
                        'error' => $e->getMessage(),
                        'attempt' => $this->attempts(),
                    ]);
                    $failedChunkIndices[] = $index;
                }
            }
            
            if ( ! empty($failedChunkIndices)) {
                Log::warning('Some chunks failed translation; re-dispatching delayed job', [
                    'id' => $this->id,
                    'failedChunks' => $failedChunkIndices,
                    'totalFailed' => count($failedChunkIndices),
                    'attempt' => $this->attempts(),
                ]);
                static::dispatch(
                    $this->modelTranslation,
                    $this->foreignKey,
                    $this->id,
                    $this->locale,
                    $this->column,
                    $this->content,
                    $this->contentCharLimit,
                    $this->minChunkSize
                )->delay(now()->addSeconds(20));
                return;
            }
            
            if (count($translatedChunks) < count($chunks)) {
                Log::warning('Not all chunks translated; re-dispatching delayed job', [
                    'id' => $this->id,
                    'translatedCount' => count($translatedChunks),
                    'totalChunks' => count($chunks),
                ]);
                static::dispatch(
                    $this->modelTranslation,
                    $this->foreignKey,
                    $this->id,
                    $this->locale,
                    $this->column,
                    $this->content,
                    $this->contentCharLimit,
                    $this->minChunkSize
                )->delay(now()->addSeconds(10));
                return;
            }
            
            ksort($translatedChunks);
            $finalTranslation = implode('', $translatedChunks);
            
            if (empty($finalTranslation)) {
                throw new \Exception('Final translation is empty after consolidation');
            }
            
            Log::info('All chunks translated and consolidated', [
                'id' => $this->id,
                'totalChunks' => count($translatedChunks),
                'finalLength' => mb_strlen($finalTranslation),
            ]);
            
            DB::transaction(function () use ($finalTranslation) {
                $exists = DB::table($this->modelTranslation)
                    ->where('locale', $this->locale)
                    ->where($this->foreignKey, $this->id)
                    ->exists();
                
                if ($exists) {
                    DB::table($this->modelTranslation)
                        ->where('locale', $this->locale)
                        ->where($this->foreignKey, $this->id)
                        ->update([$this->column => $finalTranslation]);
                } else {
                    DB::table($this->modelTranslation)->insert([
                        $this->foreignKey => $this->id,
                        'locale' => $this->locale,
                        $this->column => $finalTranslation,
                    ]);
                }
            });
            
            Log::info('=== Translation CONSOLIDATED and saved to database from queue job ------------ ', [
                'id' => $this->id,
                'column' => $this->column,
                'locale' => $this->locale,
                'finalLength' => mb_strlen($finalTranslation),
            ]);
            
            cache()->forget($cacheKey);
        } catch (\Throwable $e) {
            Log::error('TranslateContentJob failed: '.$e->getMessage(), [
                'id' => $this->id,
                'column' => $this->column,
                'attempt' => $this->attempts(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Let the framework handle retry/failure for unexpected exceptions
            throw $e;
        } finally {
            // release only if lock was acquired
            try {
                $lock->release();
            } catch (\Throwable $releaseException) {
                // ignore release errors
            }
        }
    }
    
    /**
     * Extract first-level block elements and text nodes using DOM
     */
    private function extractHtmlBlocks($content): array
    {
        $blocks = [];
        
        // Normalize encoding and wrap in a container to parse fragments
        // FIX CRITIC: Adăugăm meta charset pentru a forța DOMDocument să citească UTF-8 corect.
        // Fără asta, caracterele speciale (gen en-dash) sunt interpretate ca Latin-1 și devin mojibake (â€...)
        $html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'.$content.'</body></html>';
        
        $doc = new DOMDocument();
        // Suppress warnings from malformed HTML
        libxml_use_internal_errors(true);
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        $body = $doc->getElementsByTagName('body')->item(0);
        if ( ! $body) {
            return [['type' => 'text', 'content' => $content]];
        }
        
        $topLevelTags = ['p', 'div', 'blockquote', 'article', 'section', 'aside', 'nav', 'header', 'footer'];
        foreach ($body->childNodes as $node) {
            if ($node->nodeType === XML_TEXT_NODE) {
                $text = trim($node->nodeValue);
                if ($text !== '') {
                    $blocks[] = ['type' => 'text', 'content' => $text];
                }
                continue;
            }
            
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $tag = strtolower($node->nodeName);
                $innerHtml = '';
                foreach ($node->childNodes as $child) {
                    $innerHtml .= $doc->saveHTML($child);
                }
                
                $attributes = '';
                if ($node->hasAttributes()) {
                    foreach ($node->attributes as $attr) {
                        $attributes .= ' '.$attr->nodeName.'="'.$attr->nodeValue.'"';
                    }
                }
                
                if (in_array($tag, $topLevelTags, true)) {
                    $openTag = '<'.$tag.$attributes.'>';
                    $closeTag = '</'.$tag.'>';
                    $blocks[] = [
                        'type' => 'html',
                        'tag' => $tag,
                        'attributes' => $attributes,
                        'openTag' => $openTag,
                        'closeTag' => $closeTag,
                        'content' => $innerHtml,
                    ];
                } else {
                    // Treat unknown tags as inline text (preserve HTML)
                    $outer = $doc->saveHTML($node);
                    $blocks[] = ['type' => 'text', 'content' => $outer];
                }
            }
        }
        
        return $blocks;
    }
    
    /**
     * Create chunks from blocks using mb_* functions
     */
    private function createChunksFromHtmlBlocks($htmlBlocks, $maxChunkSize): array
    {
        $chunks = [];
        $currentChunk = '';
        $currentChunkSize = 0;
        
        foreach ($htmlBlocks as $block) {
            if ($block['type'] === 'html') {
                $blockSize = mb_strlen($block['openTag']) + mb_strlen($block['content']) + mb_strlen($block['closeTag']);
                
                if ($blockSize > $maxChunkSize) {
                    if ($currentChunk !== '') {
                        $chunks[] = trim($currentChunk);
                        $currentChunk = '';
                        $currentChunkSize = 0;
                    }
                    
                    $innerChunks = $this->splitTextBySentences($block['content'], $maxChunkSize);
                    
                    foreach ($innerChunks as $innerChunk) {
                        $reconstructed = $block['openTag'].$innerChunk.$block['closeTag'];
                        $chunks[] = $reconstructed;
                    }
                    continue;
                }
                
                if (($currentChunkSize + $blockSize > $maxChunkSize) && $currentChunk !== '') {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = $block['openTag'].$block['content'].$block['closeTag'];
                    $currentChunkSize = $blockSize;
                } else {
                    $currentChunk .= $block['openTag'].$block['content'].$block['closeTag'];
                    $currentChunkSize += $blockSize;
                }
            } else {
                $blockSize = mb_strlen($block['content']);
                
                if (($currentChunkSize + $blockSize > $maxChunkSize) && $currentChunk !== '') {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = $block['content'];
                    $currentChunkSize = $blockSize;
                } else {
                    $currentChunk .= $block['content'];
                    $currentChunkSize += $blockSize;
                }
            }
        }
        
        if ($currentChunk !== '') {
            $chunks[] = trim($currentChunk);
        }
        
        return $chunks;
    }
    
    /**
     * Split text into sentences and assemble chunks (multibyte safe)
     */
    private function splitTextBySentences($text, $maxSize): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        if (empty($sentences)) {
            return [$text];
        }
        
        $chunks = [];
        $currentChunk = '';
        $currentSize = 0;
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            $sentenceSize = mb_strlen($sentence);
            
            if ($sentenceSize > $maxSize) {
                if ($currentChunk !== '') {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                    $currentSize = 0;
                }
                $chunks[] = $sentence;
                continue;
            }
            
            if (($currentSize + $sentenceSize + ($currentChunk === '' ? 0 : 1) > $maxSize) && $currentChunk !== '') {
                $chunks[] = trim($currentChunk);
                $currentChunk = $sentence;
                $currentSize = $sentenceSize;
            } else {
                $currentChunk .= ($currentChunk === '' ? '' : ' ').$sentence;
                $currentSize += $sentenceSize + ($currentChunk === '' ? 0 : 1);
            }
        }
        
        if ($currentChunk !== '') {
            $chunks[] = trim($currentChunk);
        }
        
        return $chunks;
    }
}