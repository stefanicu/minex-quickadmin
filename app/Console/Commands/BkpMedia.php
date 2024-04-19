<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BkpMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bkp:Media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Back up the media files table. Run it before migrating:fresh command.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //DB::unprepared(file_get_contents('database/import_script.sql'));
        DB::unprepared(file_get_contents('database/import_scripts/bkp_media.sql'));
        echo '
        Baking up media files...............done';

        echo '

            ==================================
            ====   BACK UP IS COMPLETED   ====
            ==================================

        ';
    }
}
