<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ReplaceHtmlFromMetaDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metadescription:update';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace html tags from meta description.';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbName = Config::get('database.minex_live');
        
        $RStoSR = file_get_contents('database/sql_scripts/metadescription.sql');
        $RStoSR = str_replace('$dbName', "$dbName", $RStoSR);
        DB::unprepared($RStoSR);
        
        echo '

        ========================  All meta descriptions are updated  ========================
        
        ========================          UPDATE COMPLETED!          ========================
        
        
        

        ';
    }
}
