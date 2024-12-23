<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateErrorPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:error-pages';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate error pages (403, 404, 500, etc.)';
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $errorCodes = [403, 404, 500];
        $template = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error {{code}}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    margin: 50px;
                }
                h1 {
                    font-size: 3rem;
                    color: #333;
                }
                p {
                    font-size: 1.2rem;
                    color: #666;
                }
                a {
                    color: #007bff;
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <h1>Error {{code}}</h1>
            <p>{{message}}</p>
            <a href="{{url}}">Go to Homepage</a>
        </body>
        </html>
        HTML;
        
        foreach ($errorCodes as $code) {
            $message = match ($code) {
                403 => 'Forbidden: You do not have permission to access this page.',
                404 => 'Page Not Found: The page you are looking for does not exist.',
                500 => 'Internal Server Error: Something went wrong on our end.',
                default => 'An error occurred.',
            };
            
            $content = str_replace(
                ['{{code}}', '{{message}}', '{{url}}'],
                [$code, $message, url('/')],
                $template
            );
            
            $path = resource_path("views/errors/{$code}.blade.php");
            
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            
            $this->info("Error page for {$code} created at {$path}");
        }
        
        return 0;
    }
}
