<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    protected static $client;

    public static function init()
    {
        if (is_null(self::$client)) {
            self::$client = new Client();
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
        // 1. Normalizează textul principal
        $normalizedText = self::normalize_text_for_translation($text);

        // 2. FIX: Normalizează și referința dacă există!
        if ($romanianReference) {
            $normalizedRomanianReference = self::normalize_text_for_translation($romanianReference);
        }
        $systemPrompt = self::systemPrompt($targetLanguage, $normalizedRomanianReference);
        $userPrompt = self::buildUserPrompt($targetLanguage, $normalizedText, $normalizedRomanianReference, $sourceLanguage);

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
            Ensure the translation preserves the meaning and nuances of the English source." .
            ($romanianReference ? " Validate against the Romanian reference meaning if provided." : "") . "
            Treat special characters like dashes or bullets as simple punctuation.
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
            return "Translate the following English text into Serbian (Latin script).\n\nEnglish: {$text}\n" .
                ($romanianReference ? "Romanian reference meaning: {$romanianReference}" : "");
        }

        return "Translate the following English text into {$targetLanguage}.\n\nEnglish: {$text}\n" .
            ($romanianReference ? "Romanian reference meaning: {$romanianReference}" : "");
    }

    static function normalize_text_for_translation(string $text): string
    {
        // Hack pentru a repara dubla codare (UTF-8 interpretat ca Windows-1252)
        // Doar dacă ești sigur că asta e problema!
        $fixed_text = mb_convert_encoding($text, 'Windows-1252', 'UTF-8');
        if ($fixed_text !== false && mb_check_encoding($fixed_text, 'UTF-8')) {
            // Dacă conversia inversă a rezultat într-un string UTF-8 valid, îl folosim
            $text = $fixed_text;
        }

        // 1. Încercăm o transliterare automată pentru a reduce caracterele complexe la ASCII (opțional, dar recomandat pt AI)
        // Aceasta transformă ș, ț, ă în s, t, a și diverse cratime în minus.
        // Dacă vrei să păstrezi diacriticele românești, comentează linia de mai jos.
        // $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        // 2. Normalizare Unicode (Forma C - Composed)
        if (class_exists('Normalizer')) {
            $text = Normalizer::normalize($text, Normalizer::FORM_C);
        }

        // 3. Înlocuiri bazate pe clase Unicode (mult mai sigur decât lista manuală)

        // \p{Pd} = Punctuation, Dash (prinde toate tipurile de cratime: en-dash, em-dash, minus etc.)
        $text = preg_replace('/\p{Pd}/u', '-', $text);

        // \p{Pi} și \p{Pf} = Initial/Final quote (ghilimele)
        // Le înlocuim pe toate cu ghilimele duble standard
        $text = preg_replace('/[\p{Pi}\p{Pf}\p{Po}&&[^\.,!\?\(\)\-\:;]]/u', '"', $text);

        // Curățăm ghilimelele care au rămas sau au fost convertite greșit, forțăm ASCII quotes
        $quotes_map = [
            '“' => '"',
            '”' => '"',
            '„' => '"',
            '‘' => "'",
            '’' => "'",
            '‚' => "'"
        ];
        $text = strtr($text, $quotes_map);

        // \p{Z} = Separator (prinde toate tipurile de spații invizibile, non-breaking space etc.)
        // Le înlocuim cu un spațiu simplu
        $text = preg_replace('/\p{Z}/u', ' ', $text);

        // 4. Eliminăm caracterele invizibile de control (mai puțin newline și tab)
        $text = preg_replace('/[^\P{C}\n\t]/u', '', $text);

        // 5. Curățare finală spații multiple
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    /**
     * Trimite request-ul la OpenAI Responses API
     */
    protected static function sendRequest($systemPrompt, $userPrompt): ?string
    {
        $maxAttempts = 3;
        $baseTimeout = 120;
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $response = self::$client->post('https://api.openai.com/v1/responses', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                        'Content-Type' => 'application/json',
                    ],
                    'timeout' => $baseTimeout,
                    'json' => [
                        'model' => 'gpt-5.1-chat-latest',
                        'reasoning' => ['effort' => 'medium'],
                        'text' => ['verbosity' => 'medium'],
                        'input' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user', 'content' => $userPrompt],
                        ],
                    ],
                ]);

                $statusCode = $response->getStatusCode();
                $body = (string) $response->getBody();
                $headers = $response->getHeaders();
                $data = json_decode($body, true);

                if ($statusCode !== 200) {
                    Log::warning('ChatGPT: non-200 status', [
                        'attempt' => $attempt,
                        'status' => $statusCode,
                        'headers' => $headers,
                        'body' => $body,
                        'decoded' => $data,
                    ]);

                    if (in_array($statusCode, [429, 500, 502, 503, 504]) && $attempt < $maxAttempts) {
                        sleep((int) pow(2, $attempt - 1));
                        continue;
                    }

                    return null;
                }

                $translation = null;

                if (isset($data['output']) && is_array($data['output'])) {
                    foreach ($data['output'] as $item) {
                        if (!empty($item['type']) && $item['type'] === 'message' && isset($item['content']) && is_array($item['content'])) {
                            foreach ($item['content'] as $c) {
                                if (isset($c['text']) && trim($c['text']) !== '') {
                                    $translation = trim($c['text']);
                                    break 2;
                                }
                            }
                        }
                    }
                }

                if (empty($translation) && !empty($data['output_text'])) {
                    $translation = trim($data['output_text']);
                }

                if (empty($translation) && !empty($data['choices']) && is_array($data['choices'])) {
                    foreach ($data['choices'] as $choice) {
                        if (!empty($choice['message']['content'])) {
                            if (is_string($choice['message']['content'])) {
                                $translation = trim($choice['message']['content']);
                                break;
                            } elseif (is_array($choice['message']['content'])) {
                                foreach ($choice['message']['content'] as $part) {
                                    if (isset($part['text']) && trim($part['text']) !== '') {
                                        $translation = trim($part['text']);
                                        break 2;
                                    }
                                }
                            }
                        } elseif (!empty($choice['text'])) {
                            $translation = trim($choice['text']);
                            break;
                        }
                    }
                }

                if (empty($translation)) {
                    // helper to create a short preview for logs
                    $preview = function ($val) {
                        if ($val === null) {
                            return null;
                        }
                        if (is_string($val)) {
                            return mb_strimwidth($val, 0, 1000, '...');
                        }
                        return mb_strimwidth(json_encode($val, JSON_UNESCAPED_UNICODE), 0, 1000, '...');
                    };

                    $checked = [
                        'paths_searched' => [
                            'output -> [..] -> content -> text',
                            'output_text',
                            'choices -> [..] -> message -> content/text',
                        ],
                        'found_values_preview' => [
                            'output' => isset($data['output']) ? $preview($data['output']) : null,
                            'output_text' => isset($data['output_text']) ? $preview($data['output_text']) : null,
                            'choices' => isset($data['choices']) ? $preview($data['choices']) : null,
                            'usage' => isset($data['usage']) ? $preview($data['usage']) : null,
                        ],
                        'raw_body_length' => strlen($body),
                        'json_decode_error' => json_last_error() === JSON_ERROR_NONE ? null : json_last_error_msg(),
                    ];

                    // Try pretty-printing JSON for easier reading; fallback to raw body
                    $pretty = null;
                    if (is_array($data)) {
                        $pretty = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        $pretty = $body;
                    }

                    Log::error('ChatGPT returned empty translation (attempt) - inspected fields and raw JSON', [
                        'attempt' => $attempt,
                        'status' => $statusCode,
                        'headers' => $headers,
                        'checked' => $checked,
                        'pretty_json' => $pretty,
                        'systemPromptLength' => strlen($systemPrompt),
                        'userPromptLength' => strlen($userPrompt),
                    ]);

                    if ($attempt < $maxAttempts) {
                        sleep((int) pow(2, $attempt - 1));
                        continue;
                    }

                    return null;
                }

                Log::debug('ChatGPT: Translation successful', [
                    'inputLength' => strlen($userPrompt),
                    'outputLength' => strlen($translation),
                    'tokens' => [
                        'input' => $data['usage']['input_tokens'] ?? null,
                        'output' => $data['usage']['output_tokens'] ?? null,
                    ],
                ]);

                return $translation;
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                $lastException = $e;
                $resp = $e->getResponse();
                $body = $resp ? (string) $resp->getBody() : null;
                $status = $resp ? $resp->getStatusCode() : null;
                $headers = $resp ? $resp->getHeaders() : null;
                $decoded = $body ? json_decode($body, true) : null;

                Log::warning('ChatGPT HTTP error', [
                    'attempt' => $attempt,
                    'message' => $e->getMessage(),
                    'status' => $status,
                    'headers' => $headers,
                    'body' => $body,
                    'decoded' => $decoded,
                ]);

                if ($attempt < $maxAttempts && in_array($status, [429, 500, 502, 503, 504])) {
                    sleep((int) pow(2, $attempt - 1));
                    continue;
                }

                break;
            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('ChatGPT API error details', [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'systemPromptLength' => strlen($systemPrompt),
                    'userPromptLength' => strlen($userPrompt),
                    'trace' => $e->getTraceAsString(),
                    'attempt' => $attempt,
                ]);

                if ($attempt < $maxAttempts) {
                    sleep((int) pow(2, $attempt - 1));
                    continue;
                }

                break;
            }
        }

        if ($lastException) {
            Log::error('ChatGPT final failure', ['exception' => $lastException->getMessage()]);
        }

        return null;
    }
}