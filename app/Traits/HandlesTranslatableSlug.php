<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HandlesTranslatableSlug
{
    protected function saveWithSlug($request, $model)
    {
        $config = $this->getSlugConfig($model);
        
        $locale = $config['locale'];
        $slug = $request->slug;
        
        if ( ! $slug) {
            $slug = $this->generateSlug($request->name, $locale ?? 'en');
        }
        
        $slug = $this->ensureUniqueSlug(
            $slug,
            $config['table'],
            $config['locale'],
            $config['key_column'],
            $model->id ?? null
        );
        
        $request->merge(['slug' => $slug]);
        
        if ($model->exists) {
            $model->update($request->all());
        } else {
            $model->fill($request->all())->save();
        }
        
        return $model;
    }
    
    /**
     * Generează slug din text cu suport pentru multiple limbi
     */
    protected function generateSlug(string $name, string $locale): string
    {
        // Aplică reguli de transliterare specifice pentru fiecare limbă înainte de procesarea generală
        switch ($locale) {
            case 'bg': // Bulgară
                $charMap = [
                    // Problemă specifică: 'ъ' devine 'a'
                    'ъ' => 'a', 'Ъ' => 'A',
                    // Digrafe comune
                    'щ' => 'sht', 'Щ' => 'Sht',
                    'ж' => 'zh', 'Ж' => 'Zh',
                    'ч' => 'ch', 'Ч' => 'Ch',
                    'ш' => 'sh', 'Ш' => 'Sh',
                    'ю' => 'yu', 'Ю' => 'Yu',
                    'я' => 'ya', 'Я' => 'Ya',
                    'ц' => 'ts', 'Ц' => 'Ts',
                ];
                $name = str_replace(array_keys($charMap), array_values($charMap), $name);
                break;
            
            case 'sr': // Sârbă (Cyrillic)
            case 'bs': // Bosniacă
            case 'hr': // Croată
                $charMap = [
                    'Ђ' => 'Dj', 'ђ' => 'dj',
                    'Љ' => 'Lj', 'љ' => 'lj',
                    'Њ' => 'Nj', 'њ' => 'nj',
                    'Џ' => 'Dz', 'џ' => 'dz',
                    // Asigură maparea corectă pentru caractere care altfel ar deveni identice
                    'Ћ' => 'C', 'ћ' => 'c',
                    'Ч' => 'C', 'ч' => 'c',
                    'Ш' => 'S', 'ш' => 's',
                    'Ж' => 'Z', 'ж' => 'z',
                ];
                $name = str_replace(array_keys($charMap), array_values($charMap), $name);
                break;
            
            case 'mk': // Macedoneană
                $charMap = [
                    'Ѓ' => 'Gj', 'ѓ' => 'gj',
                    'Ѕ' => 'Dz', 'ѕ' => 'dz',
                    'Ќ' => 'Kj', 'ќ' => 'kj',
                    'Љ' => 'Lj', 'љ' => 'lj',
                    'Њ' => 'Nj', 'њ' => 'nj',
                    'Џ' => 'Dzh', 'џ' => 'dzh',
                ];
                $name = str_replace(array_keys($charMap), array_values($charMap), $name);
                break;
            
            case 'uk': // Ucraineană
                $charMap = [
                    'Є' => 'Ye', 'є' => 'ye',
                    'Ї' => 'Yi', 'ї' => 'yi',
                    'Й' => 'Y', 'й' => 'y',
                    'Щ' => 'Shch', 'щ' => 'shch',
                    'Г' => 'H', 'г' => 'h',
                    'Ґ' => 'G', 'ґ' => 'g',
                    'Ю' => 'Yu', 'ю' => 'yu',
                    'Я' => 'Ya', 'я' => 'ya',
                ];
                $name = str_replace(array_keys($charMap), array_values($charMap), $name);
                break;
        }
        
        // Transliterează restul caracterelor (inclusiv diacriticele latine) în ASCII
        $slug = transliterator_transliterate('Any-Latin; Latin-ASCII', $name);
        
        // Convertește la litere mici
        $slug = strtolower($slug);
        
        // Înlocuiește orice secvență de caractere care nu sunt litere sau cifre cu o singură cratimă
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Elimină cratimele de la începutul și sfârșitul șirului
        $slug = trim($slug, '-');
        
        return $slug;
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
    
    /**
     * Obține configurarea slug-ului pentru model
     */
    protected function getSlugConfig($model)
    {
        // Verifică dacă controllerul are configurare customizată
        if (property_exists($this, 'slugConfig')) {
            return $this->slugConfig;
        }
        
        // Default pentru translation tables
        $modelName = strtolower(class_basename($model));
        return [
            'table' => $modelName.'_translations',
            'locale' => app()->getLocale(),
            'key_column' => $modelName.'_id'
        ];
    }
}