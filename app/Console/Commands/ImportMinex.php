<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMinex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:minex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from minex-live table. This table is need to be updated before to lunch in production.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //DB::unprepared(file_get_contents('database/import_script.sql'));
        DB::unprepared(file_get_contents('database/import_scripts/applications.sql'));
        echo '
        Applications import data...............done';

        DB::unprepared(file_get_contents('database/import_scripts/categories.sql'));
        echo '
        Categories import data.................done';

        DB::unprepared(file_get_contents('database/import_scripts/application_category.sql'));
        echo '
        Application_Category import data.......done';

        DB::unprepared(file_get_contents('database/import_scripts/front_pages.sql'));
        echo '
        FrontPages import data.................done';

        DB::unprepared(file_get_contents('database/import_scripts/blogs.sql'));
        echo '
        Blogs import data......................done';

        DB::unprepared(file_get_contents('database/import_scripts/translation_centers.sql'));
        echo '
        Translation Centers import data........done';

        DB::unprepared(file_get_contents('database/import_scripts/industries.sql'));
        echo '
        Industries import data.................done';

        DB::unprepared(file_get_contents('database/import_scripts/references.sql'));
        echo '
        References import data.................done';

        echo '

           --- not translatable imports ---
        ';

        DB::unprepared(file_get_contents('database/import_scripts/brands.sql'));
        echo '
        Brands import data.....................done';

        DB::unprepared(file_get_contents('database/import_scripts/contacts.sql'));
        echo '
        Contacts import data...................done';

        echo '

            ==============================
            ====   IMPORT COMPLETED   ====
            ==============================

        ';

        DB::unprepared(file_get_contents('database/import_scripts/restore_media_bkp.sql'));
        echo '
        Restore Media from Back up.............done';

        echo '

            =====================================
            ====   RESTORE MEDIA COMPLETED   ====
            =====================================

        ';
    }
}
