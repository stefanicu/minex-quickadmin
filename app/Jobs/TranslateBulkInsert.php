<?php

namespace App\Jobs;

use App\Services\ChatGPTService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TranslateBulkInsert implements ShouldQueue
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
    protected $columns;
    protected $record;
    
    public function __construct($modelTranslation, $foreignKey, $id, $locale, $columns, $record)
    {
        $this->modelTranslation = $modelTranslation;
        $this->foreignKey = $foreignKey;
        $this->id = $id;
        $this->locale = $locale;
        $this->columns = $columns;
        $this->record = $record;
    }
    
    public function handle()
    {
        // No record exists for the current locale, so create a new one
        $newRecordData = [
            $this->foreignKey => $this->id,
            'locale' => $this->locale,
        ];
        
        // For each column, if the source field is not empty, translate and insert the value
        foreach ($this->columns as $column) {
            // If the source field exists and has a value, translate it
            if ( ! empty($this->record->{$column})) {
                // Translate from source language (Romanian for en, or English for others)
                if ($column != 'slug') {
                    $translatedValue = app(ChatGPTService::class)->translate($this->record->{$column}, $this->locale, 'en');
                    $newRecordData[$column] = $translatedValue;
                }
            }
        }
        
        $newRecordData['slug'] = generateSlug($newRecordData['name']);
        $newRecordData['online'] = 1;
        
        // Insert the new record with all translated fields
        DB::table($this->modelTranslation)->insert($newRecordData);
    }
}
