<?php

namespace App\Services;

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    protected static $client;
    protected static $apiKey;
    
    public static function init()
    {
        // Initialize the client and API key only once
        if (is_null(self::$client)) {
            self::$client = new Client();
            self::$apiKey = env('OPENAI_API_KEY');
        }
    }
    
    public static function translate($text, $targetLanguage = 'ro', $sourceLanguage = 'en'): ?string
    {
        // Initialize if not already done
        self::init();
        
        $prompt = "Translate the following text from {$sourceLanguage} to {$targetLanguage}: {$text}";
        
        return self::sendRequest($prompt);
    }
    
    public static function generateSeoMeta($title, $description)
    {
        // Initialize if not already done
        self::init();
        
        $prompt = "Generate an SEO-optimized meta title and meta description based on the following page title and description:\n\n".
            "Page Title: {$title}\n".
            "Page Description: {$description}\n\n".
            "Provide the result in JSON format with 'meta_title' and 'meta_description' fields.";
        
        $response = self::sendRequest($prompt);
        
        return json_decode($response, true);
    }
    
    protected static function sendRequest($prompt): ?string
    {
        try {
            $response = self::$client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '.self::$apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ],
            ]);
            
            $data = json_decode($response->getBody(), true);
            return trim($data['choices'][0]['message']['content']);
        } catch (\Exception $e) {
            Log::error('ChatGPT API error: '.$e->getMessage());
            return null;
        }
    }
}