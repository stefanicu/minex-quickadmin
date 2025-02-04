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
    
    /**
     * Create a new job instance.
     */
    use InteractsWithQueue, Queueable, SerializesModels;
    
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
        // Determine source locale based on the current locale
        $sourceLocale = ($this->locale === 'en') ? 'ro' : 'en';
        
        $translatedValue = ChatGPTService::translate($this->value, $this->locale, $sourceLocale);
        
        // Update the value in the translation table with the translated value
        DB::table($this->modelTranslation)
            ->where('locale', $this->locale)
            ->where($this->foreignKey, $this->id)
            ->update([$this->column => $translatedValue]);
    }
}
