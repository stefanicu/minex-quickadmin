<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait SlugGenerator
{
    /**
     * Generează slug din text cu suport pentru multiple limbi
     */
    protected function generateSlug(string $name, string $locale): string
    {
        // Mapă globală ISO simplificată (ASCII-only)
        $charMap = [
            // Comun (BG, MK, SR, UK etc.)
            'щ' => 'sht', 'Щ' => 'Sht',
            'ж' => 'zh', 'Ж' => 'Zh',
            'ч' => 'ch', 'Ч' => 'Ch',
            'ш' => 'sh', 'Ш' => 'Sh',
            'ю' => 'yu', 'Ю' => 'Yu',
            'я' => 'ya', 'Я' => 'Ya',
            'ц' => 'ts', 'Ц' => 'Ts',
            
            // Bulgară
            'й' => 'j', 'Й' => 'J',
            'ъ' => 'u', 'Ъ' => 'U',
            
            // Macedoneană
            'ј' => 'j', 'Ј' => 'J',
            'ѓ' => 'gj', 'Ѓ' => 'Gj',
            'ќ' => 'kj', 'Ќ' => 'Kj',
            'љ' => 'lj', 'Љ' => 'Lj',
            'њ' => 'nj', 'Њ' => 'Nj',
            'џ' => 'dz', 'Џ' => 'Dz',
            'ѕ' => 'dz', 'Ѕ' => 'Dz',
            
            // Sârbă / Bosniacă / Croată
            'ђ' => 'dj', 'Ђ' => 'Dj',
            'ћ' => 'c', 'Ћ' => 'C',
            // notă: pentru slug-uri → "č" devine "ch"
            'ч' => 'ch', 'Ч' => 'Ch',
            'ш' => 'sh', 'Ш' => 'Sh',
            'ж' => 'zh', 'Ж' => 'Zh',
            
            // Ucraineană
            'є' => 'ye', 'Є' => 'Ye',
            'ї' => 'yi', 'Ї' => 'Yi',
            'й' => 'j', 'Й' => 'J',
            'щ' => 'shch', 'Щ' => 'Shch',
            'г' => 'h', 'Г' => 'H',
            'ґ' => 'g', 'Ґ' => 'G',
        ];
        
        // Aplică maparea personalizată
        $name = str_replace(array_keys($charMap), array_values($charMap), $name);
        
        // Transliterează restul caracterelor în ASCII
        $slug = transliterator_transliterate('Any-Latin; Latin-ASCII', $name);
        
        // Normalizează pentru slug
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
    
    /**
     * Asigură că slug-ul este unic prin adăugarea unui suffix numeric
     */
    protected function ensureUniqueSlug(?string $slug, $table, $locale, $keyColumn, $excludeId = null): string
    {
        $originalSlug = $slug;
        $count = 1;
        
        // Construiește query-ul pentru verificarea existenței slug-ului
        $query = DB::table($table)->where('slug', $slug);
        
        // Adaugă condiția pentru locale doar dacă există (pentru translation tables)
        if ($locale !== null) {
            $query->where('locale', '=', $locale);
        }
        
        // Dacă este furnizat un ID de exclus, îl adaugă la query
        if ($excludeId !== null) {
            $query->where($keyColumn, '!=', $excludeId);
        }
        
        // Verifică dacă slug-ul există în baza de date
        while ($query->exists()) {
            // Dacă există, resetează query-ul pentru următoarea iterație
            // cu noul slug, dar păstrează excluderea
            $slug = $originalSlug.'-'.$count;
            $count++;
            
            $query = DB::table($table)->where('slug', $slug);
            
            if ($locale !== null) {
                $query->where('locale', '=', $locale);
            }
            
            if ($excludeId !== null) {
                $query->where($keyColumn, '!=', $excludeId);
            }
        }
        
        return $slug;
    }
}