<?php

if ( ! function_exists('getSvgDimensions')) {
    function getSvgDimensions($filePath)
    {
        $svgContent = file_get_contents($filePath);
        $xml = simplexml_load_string($svgContent);
        
        $attributes = $xml->attributes();
        $width = (float) $attributes->width;
        $height = (float) $attributes->height;
        
        return [$width, $height];
    }
}