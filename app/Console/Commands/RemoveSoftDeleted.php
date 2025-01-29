<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Console\Command;

class RemoveSoftDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:softdeleted';
    
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
        Application::onlyTrashed()->forceDelete();
        echo '
        Remove Soft Deleted Applications';
        Category::onlyTrashed()->forceDelete();
        echo '
        Remove Soft Deleted Categories';
        Brand::onlyTrashed()->forceDelete();
        echo '
        Remove Soft Deleted Brands';
        
        
        echo '
        
        DONE DONE DONE
        
        ';
    }
}
