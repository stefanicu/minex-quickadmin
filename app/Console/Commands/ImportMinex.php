<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
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
        $minexq = Config::get('database.minexq');
        $minex_live =  Config::get('database.minex_live');


        $applications = file_get_contents('database/import_scripts/applications.sql');
        $applications = str_replace('$minexq', "$minexq", $applications);
        $applications = str_replace('$minex_live', "$minex_live", $applications);
        DB::unprepared($applications);
        echo '
        Applications import data...............done';

        $categories = file_get_contents('database/import_scripts/categories.sql');
        $categories = str_replace('$minexq', "$minexq", $categories);
        $categories = str_replace('$minex_live', "$minex_live", $categories);
        DB::unprepared($categories);
        echo '
        Categories import data.................done';

        $application_category = file_get_contents('database/import_scripts/application_category.sql');
        $application_category = str_replace('$minexq', "$minexq", $application_category);
        $application_category = str_replace('$minex_live', "$minex_live", $application_category);
        DB::unprepared($application_category);
        echo '
        Application_Category import data.......done';

        $front_pages = file_get_contents('database/import_scripts/front_pages.sql');
        $front_pages = str_replace('$minexq', "$minexq", $front_pages);
        $front_pages = str_replace('$minex_live', "$minex_live", $front_pages);
        DB::unprepared($front_pages);
        echo '
        FrontPages import data.................done';

        $blogs = file_get_contents('database/import_scripts/blogs.sql');
        $blogs = str_replace('$minexq', "$minexq", $blogs);
        $blogs = str_replace('$minex_live', "$minex_live", $blogs);
        DB::unprepared($blogs);
        echo '
        Blogs import data......................done';

        $translation_centers = file_get_contents('database/import_scripts/translation_centers.sql');
        $translation_centers = str_replace('$minexq', "$minexq", $translation_centers);
        $translation_centers = str_replace('$minex_live', "$minex_live", $translation_centers);
        DB::unprepared($translation_centers);
        echo '
        Translation Centers import data........done';

        $industries = file_get_contents('database/import_scripts/industries.sql');
        $industries = str_replace('$minexq', "$minexq", $industries);
        $industries = str_replace('$minex_live', "$minex_live", $industries);
        DB::unprepared($industries);
        echo '
        Industries import data.................done';

        $references = file_get_contents('database/import_scripts/references.sql');
        $references = str_replace('$minexq', "$minexq", $references);
        $references = str_replace('$minex_live', "$minex_live", $references);
        DB::unprepared($references);
        echo '
        References import data.................done';

        $testimonials = file_get_contents('database/import_scripts/testimonials.sql');
        $testimonials = str_replace('$minexq', "$minexq", $testimonials);
        $testimonials = str_replace('$minex_live', "$minex_live", $testimonials);
        DB::unprepared($testimonials);
        echo '
        Testimonials import data...............done';

        $brands = file_get_contents('database/import_scripts/brands.sql');
        $brands = str_replace('$minexq', "$minexq", $brands);
        $brands = str_replace('$minex_live', "$minex_live", $brands);
        DB::unprepared($brands);
        echo '
        Brands import data.....................done';

        $contacts = file_get_contents('database/import_scripts/contacts.sql');
        $contacts = str_replace('$minexq', "$minexq", $contacts);
        $contacts = str_replace('$minex_live', "$minex_live", $contacts);
        DB::unprepared($contacts);
        echo '
        Contacts import data...................done';

        $products = file_get_contents('database/import_scripts/products.sql');
        $products = str_replace('$minexq', "$minexq", $products);
        $products = str_replace('$minex_live', "$minex_live", $products);
        DB::unprepared($products);
        echo '
        PRODUCTS import data...................done';

//        $application_product = file_get_contents('database/import_scripts/application_product.sql');
//        $application_product = str_replace('$minexq', "$minexq", $application_product);
//        $application_product = str_replace('$minex_live', "$minex_live", $application_product);
//        DB::unprepared($application_product);
//        echo '
//        Application_Product import data.......done';

        $category_product = file_get_contents('database/import_scripts/category_product.sql');
        $category_product = str_replace('$minexq', "$minexq", $category_product);
        $category_product = str_replace('$minex_live', "$minex_live", $category_product);
        DB::unprepared($category_product);
        echo '
        Category_Product import data...........done';


        $restore_media_bkp = file_get_contents('database/import_scripts/restore_media_bkp.sql');
        $restore_media_bkp = str_replace('$minexq', "$minexq", $restore_media_bkp);
        $restore_media_bkp = str_replace('$minex_live', "$minex_live", $restore_media_bkp);
        DB::unprepared($restore_media_bkp);
        echo '
        --
        Restore Media from Back up.............done';


        echo '

        ========================  IMPORT COMPLETED!

        ';
    }
}
