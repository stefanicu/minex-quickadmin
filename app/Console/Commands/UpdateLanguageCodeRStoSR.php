<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UpdateLanguageCodeRStoSR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rstosr:update';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Language code rs is wrong and need to be changed in all language codes with sr, the RS code is for the country not for language.';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbName = Config::get('database.minex_live');
        
        $RStoSR = file_get_contents('database/sql_scripts/rstosr.sql');
        $RStoSR = str_replace('$dbName', "$dbName", $RStoSR);
        DB::unprepared($RStoSR);
        
        echo '

        ========================  UPDATE COMPLETED!

        ';
    }
}
