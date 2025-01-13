<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    protected $client;
    protected $apiKey;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');
    }
    
    public function translate($text, $targetLanguage = 'ro', $sourceLanguage = 'en'): ?string
    {
        $prompt = "Translate the following text from {$sourceLanguage} to {$targetLanguage}: {$text}";
        
        return $this->sendRequest($prompt);
    }
    
    public function generateSeoMeta($title, $description)
    {
        $prompt = "Generate an SEO-optimized meta title and meta description based on the following page title and description:\n\n".
            "Page Title: {$title}\n".
            "Page Description: {$description}\n\n".
            "Provide the result in JSON format with 'meta_title' and 'meta_description' fields.";
        
        $response = $this->sendRequest($prompt);
        
        return json_decode($response, true);
    }
    
    protected function sendRequest($prompt): ?string
    {
        try {
            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
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