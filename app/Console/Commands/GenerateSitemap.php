<?php

//namespace App\Console\Commands;
//
//use Illuminate\Console\Command;
//use Spatie\Sitemap\SitemapGenerator;
//use Spatie\Sitemap\Tags\Url;
//
//class GenerateSitemap extends Command
//{
//    protected $signature = 'sitemap:generate';
//
//    protected $description = 'Generate sitemap';
//
//    public function handle()
//    {
//        // The Crawler
//        SitemapGenerator::create(url(''))
//            ->hasCrawled(function (Url $url) {
//                // Some logic here
//                return $url;
//            })
//            ->writeToFile(public_path('sitemap.xml'));
//    }
//}

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an XML sitemap';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (config('translatable.locales') as $locale) {
            $sitemap = Sitemap::create()
                ->add(Url::create('/'.$locale)->setPriority(1.0))
                ->add(Url::create('/'.$locale.'/'.trans('slugs.brands'))->setPriority(0.8))
                ->add(Url::create('/'.$locale.'/'.trans('slugs.references'))->setPriority(0.8))
                ->add(Url::create('/'.$locale.'/'.trans('slugs.testimonials'))->setPriority(0.8))
                ->add(Url::create('/'.$locale.'/'.trans('slugs.blog'))->setPriority(0.8))
                ->add(Url::create('/'.$locale.'/'.trans('slugs.brands'))->setPriority(0.8));
            
            // Dynamically add URLs (e.g., blog posts)
            //            foreach (\App\Models\Category::all() as $category) {
            //                $sitemap->add(Url::create("/".trans('slugs.category')."/{$category->slug}")->setPriority(0.7));
            //            }
            
            foreach (\App\Models\Application::all() as $applications) {
                $sitemap->add(Url::create("/".$locale.'/'.trans('slugs.products')."/{$applications->slug}")->setPriority(0.7));
            }
            
            foreach (\App\Models\Brand::all() as $brand) {
                $sitemap->add(Url::create("/".$locale.'/'.trans('slugs.brand')."/{$brand->slug}")->setPriority(0.7));
            }
            
            foreach (\App\Models\Blog::all() as $blog) {
                $sitemap->add(Url::create("/".$locale.'/'.trans('slugs.blog')."/{$blog->slug}")->setPriority(0.7));
            }
            
            foreach (\App\Models\Product::all() as $product) {
                $sitemap->add(Url::create("/".$locale.'/'.trans('slugs.product')."/{$product->slug}")->setPriority(0.9));
            }
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));
        
        $this->info('Sitemap generated successfully!');
    }
}