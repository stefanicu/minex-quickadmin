<?php

namespace App\Jobs;

use App\Services\ChatGPTService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TranslateBulkUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $modelTranslation;
    protected $foreignKey;
    protected $id;
    protected $locale;
    protected $column;
    protected $value;
    
    public function __construct($modelTranslation, $foreignKey, $id, $locale, $column, $value)
    {
        $this->modelTranslation = $modelTranslation;
        $this->foreignKey = $foreignKey;
        $this->id = $id;
        $this->locale = $locale;
        $this->column = $column;
        $this->value = $value;
    }
    
    public function handle()
    {
        try {
            // Determine source locale based on the current locale
            $sourceLocale = ($this->locale === 'en') ? 'ro' : 'en';
            
            $romanian_reference_value = DB::table($this->modelTranslation)
                ->where('locale', $this->locale)
                ->where($this->foreignKey, $this->id)->first();
            
            // Call ChatGPT API
            $translatedValue = ChatGPTService::translate($this->value, $this->locale, $sourceLocale, $romanian_reference_value->{$this->column});
            
            // Ensure translation is valid
            if (empty($translatedValue)) {
                throw new \Exception('Translation failed: Empty response from API');
            }
            
            // Update translation in the database
            DB::table($this->modelTranslation)
                ->where('locale', $this->locale)
                ->where($this->foreignKey, $this->id)
                ->update([$this->column => $translatedValue]);
        } catch (\Throwable $e) {
            \Log::error('TranslateBulkUpdate failed: '.$e->getMessage());
            
            // Release the job back into the queue with delay
            $this->release(10); // Retry after 10 seconds
        }
    }
}
