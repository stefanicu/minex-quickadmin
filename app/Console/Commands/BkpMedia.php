<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
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
        $minexq = Config::get('database.minexq');
        $minex_live =  Config::get('database.minex_live');

        $bkp_media = file_get_contents('database/import_scripts/bkp_media.sql');
        $bkp_media = str_replace('$minexq', "$minexq", $bkp_media);
        $bkp_media = str_replace('$minex_live', "$minex_live", $bkp_media);
        DB::unprepared($bkp_media);
        echo '
        Baking up media files..................done
        --';
    }
}
