<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    protected static $client;
    protected static $apiKey;
    
    public static function init()
    {
        if (is_null(self::$client)) {
            self::$client = new Client();
            self::$apiKey = config('app.openai_api_key_gpt5');
        }
    }
    
    /**
     * Translate text with meaning check.
     * - Default: EN → target
     * - If target = en → RO → EN
     * - If target = ro → without diacritics
     * - If target = rs → Latin script
     */
    public static function translate($text, $targetLanguage, $sourceLanguage, $romanianReference = null): ?string
    {
        self::init();
        
        $systemPrompt = self::systemPrompt($targetLanguage, $romanianReference);
        $userPrompt = self::buildUserPrompt($targetLanguage, $text, $romanianReference, $sourceLanguage);
        
        return self::sendRequest($systemPrompt, $userPrompt);
    }
    
    /**
     * Pregătește system prompt-ul (reguli generale)
     */
    protected static function systemPrompt($targetLanguage, $romanianReference = null): string
    {
        if ($targetLanguage === 'en') {
            // Excepție: RO → EN
            return "
                You are a professional translation API specialized in industrial equipment, machinery, and engineering terminology.
                Always translate from Romanian (ro) into English (en).
                Ensure the translation preserves the meaning and nuances of the Romanian source.
                Keep technical accuracy and use consistent terminology.

                Domain context:
                - The content belongs to the industrial equipment and engineering field.
                - Common subjects include pumps, compressors, filters, fans, motors, reducers, conveyors, automation, and industrial components.
                - Maintain a professional, technical tone suitable for product descriptions, catalogs, and equipment datasheets.
                - Use terminology used in technical manuals and B2B product marketing, not consumer advertising.

                Rules:
                - Always use consistent terminology and phrasing across requests.
                - Keep formatting, punctuation, and HTML tags identical to the source.
                - Translate visible text inside HTML tags.
                - Also translate text inside <img> attributes such as alt and title.
                - Keep attribute names (alt, title, src, href, etc.) unchanged, only translate their values.
                - Do not rephrase sentences; translate as literally as possible while preserving grammar.
                - Do not add or remove words.
                - Preserve HTML entities (&lt;, &gt;, &amp;).
                - Output ONLY the translation, no explanations.
            ";
        }
        
        // Regula generală EN → target
        return "
            You are a professional translation API specialized in industrial equipment, machinery, and engineering terminology.
            Always translate from English (en) into the requested target language.
            Ensure the translation preserves the meaning and nuances of the English source.".
            ($romanianReference ? " Validate against the Romanian reference meaning if provided." : "")."
            Keep technical accuracy and use consistent terminology.

            Domain context:
            - The content belongs to the industrial equipment and engineering field.
            - Common subjects include pumps, compressors, filters, fans, motors, reducers, conveyors, automation, and industrial components.
            - Maintain a professional, technical tone suitable for product descriptions, catalogs, and equipment datasheets.
            - Use terminology used in technical manuals and B2B product marketing, not consumer advertising.

            Rules:
            - Always use consistent terminology and phrasing across requests.
            - Keep formatting, punctuation, and HTML tags identical to the source.
            - Translate visible text inside HTML tags.
            - Also translate text inside <img> attributes such as alt and title.
            - Keep attribute names (alt, title, src, href, etc.) unchanged, only translate their values.
            - Do not rephrase sentences; translate as literally as possible while preserving grammar.
            - Do not add or remove words.
            - Preserve HTML entities (&lt;, &gt;, &amp;).
            - Output ONLY the translation, no explanations.
        ";
    }
    
    /**
     * Pregătește user prompt-ul (instrucțiuni pe textul concret)
     */
    protected static function buildUserPrompt($targetLanguage, $text, $romanianReference = null, $sourceLanguage = 'en'): string
    {
        if ($targetLanguage === 'en') {
            return "Translate the following Romanian text into English.\n\nRomanian: {$text}";
        }
        
        if ($targetLanguage === 'ro') {
            return "Translate the following English text into Romanian (without diacritics).\n\nEnglish: {$text}";
        }
        
        if ($targetLanguage === 'rs') {
            return "Translate the following English text into Serbian (Latin script).\n\nEnglish: {$text}\n".
                ($romanianReference ? "Romanian reference meaning: {$romanianReference}" : "");
        }
        
        return "Translate the following English text into {$targetLanguage}.\n\nEnglish: {$text}\n".
            ($romanianReference ? "Romanian reference meaning: {$romanianReference}" : "");
    }
    
    /**
     * Trimite request-ul la OpenAI Responses API
     */
    protected static function sendRequest($systemPrompt, $userPrompt): ?string
    {
        try {
            $response = self::$client->post('https://api.openai.com/v1/responses', [
                'headers' => [
                    'Authorization' => 'Bearer '.self::$apiKey,
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 120,
                'retry' => 3,
                'json' => [
                    'model' => 'gpt-5',
                    'reasoning' => [
                        'effort' => 'minimal',
                    ],
                    'text' => [
                        'verbosity' => 'low',
                    ],
                    'input' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt,
                        ],
                    ],
                ],
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            // log ca să vezi structura reală
            // Log::info('ChatGPT raw response', $data);
            
            return trim($data['output'][1]['content'][0]['text'] ?? '');
        } catch (\Exception $e) {
            Log::error('ChatGPT API error: '.$e->getMessage());
            return null;
        }
    }
}