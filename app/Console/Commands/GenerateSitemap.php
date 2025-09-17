<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
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
        SitemapGenerator::create(config("app.url"))
            ->hasCrawled(function (Url $url) {
                // Supported languages
                $languages = array_keys(config('panel.available_languages'));
                
                // Get the current URL and parse its path
                $parsedUrl = parse_url($url->url);
                $path = $parsedUrl['path'] ?? ''; // Extract the path (e.g., /en/page)
                
                // Split the path into segments and replace the first segment (language)
                $segments = explode('/', trim($path, '/'));
                
                if ( ! empty($segments[0]) && in_array($segments[0], $languages)) {
                    $currentLang = $segments[0]; // First segment is language
                } else {
                    $currentLang = null; // Default, no language in URL
                }
                
                foreach ($languages as $lang) {
                    // Replace the first segment with the target language
                    $segments[0] = $lang;
                    
                    // Rebuild the alternate URL
                    $alternateUrl = url(implode('/', $segments));
                    
                    // Add the alternate hreflang attribute
                    // $url->addAlternate($alternateUrl, $lang); ============================================================== TODO: de creat hreflang tradus in sitemap
                }
                
                // Determine the `lastmod`, `changefreq`, and `priority`
                $lastmod = now(); // Replace this with your actual updated_date logic.
                $changefreq = 'weekly'; // Use dynamic logic if needed
                $priority = 0.5; // Define a priority based on your logic
                
                // Set additional metadata
                $url->setLastModificationDate($lastmod) // Sets the last modification date
                ->setChangeFrequency($changefreq)  // Sets the change frequency
                ->setPriority($priority);          // Sets the priority
                
                
                return $url;
            })
            ->writeToFile(public_path('sitemap2.xml'));
        
        $this->info('Sitemap generated successfully!');
    }
}