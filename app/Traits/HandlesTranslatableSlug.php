<?php

namespace App\Traits;

trait HandlesTranslatableSlug
{
    use SlugGenerator;
    
    protected function saveWithSlug($request, $model)
    {
        $config = $this->getSlugConfig($model);
        
        $locale = $config['locale'];
        $slug = $request->slug;
        
        $slug = $this->generateSlug($request->name, $locale ?? 'en', $slug);
        
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