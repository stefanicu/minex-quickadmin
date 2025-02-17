<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAppCatToProd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AppCatToProd';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove soft deleted from Applications, Categories and Brands tables.';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = DB::table('products')->get();
        
        foreach ($products as $product) {
            // Get the first application assigned to the product
            $application = DB::table('application_product')
                ->where('product_id', $product->id)
                ->first();
            
            // Get the first category assigned to the product
            $category = DB::table('category_product')
                ->where('product_id', $product->id)
                ->first();
            
            DB::table('products')->where('id', $product->id)->update([
                'application_id' => $application->application_id ?? null,
                'category_id' => $category->category_id ?? null,
            ]);
            
            //            if (isset($application->application_id) && isset($category->category_id)) {
            //                // Get the application translation
            //                $application_translation = DB::table('application_translations')
            //                    ->where('application_id', $application->application_id)
            //                    ->first();
            //
            //                // Get the category translation
            //                $category_translation = DB::table('category_translations')
            //                    ->where('category_id', $category->category_id)
            //                    ->first();
            //
            //                DB::table('product_translations')->where('product_id', $product->id)->update([
            //                    'application_slug' => $application_translation->slug ?? null,
            //                    'application_name' => $application_translation->name ?? null,
            //                    'category_slug' => $category_translation->slug ?? null,
            //                    'category_name' => $category_translation->name ?? null,
            //                ]);
            //            }
        }
        
        
        echo '
        
        DONE DONE DONE
        
        Migrate Applications and Categories into the Products table.
        
        ';
    }
}
