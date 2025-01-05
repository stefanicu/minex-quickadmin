<?php

namespace App\Traits;

trait HasMetaData
{
    public function getMetaData($model, $defaultValues = []): array
    {
        $meta = [
            'meta_title' => $model->meta_title ?? $model->name.' | Minex Group International',
            'meta_description' => $model->meta_description ?? $model->name.' description',
            'canonical_url' => $model->canonical_url ?? null,
            'author' => $model->author ?? 'Minex Group International',
            'meta_image_url' => null,
            'meta_image_width' => $defaultValues['image_width'] ?? null,
            'meta_image_height' => $defaultValues['image_height'] ?? null,
            'meta_image_name' => null,
            'og_type' => $defaultValues['og_type'] ?? 'website',
        ];
        
        // Use a method to get the image or fallback to a default value
        if (method_exists($model, 'getMetaImage')) {
            $image = $model->getMetaImage();
            
            if ($image) {
                $meta['meta_image_url'] = $image['url'] ?? null;
                $meta['meta_image_name'] = $image['name'] ?? null;
                $meta['meta_image_width'] = $image['width'] ?? $defaultValues['image_width'];
                $meta['meta_image_height'] = $image['height'] ?? $defaultValues['image_height'];
            }
        }
        
        return $meta;
    }
}