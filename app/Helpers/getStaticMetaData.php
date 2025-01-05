<?php

function getStaticMetaData(array $overrides = []): array
{
    // Default meta data
    $defaultMeta = [
        'meta_title' => null,
        'meta_description' => null,
        'canonical_url' => null,
        'author' => 'Minex Group International',
        'meta_image_url' => null,
        'meta_image_width' => null,
        'meta_image_height' => null,
        'meta_image_name' => null,
        'og_type' => 'website',
    ];
    
    // Allow overriding defaults
    return array_merge($defaultMeta, $overrides);
}