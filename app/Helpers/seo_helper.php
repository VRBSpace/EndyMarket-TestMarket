<?php

use CodeIgniter\I18n\Time;

/**
 * Build normalized $seo array for product detail page.
 *
 * @param array $product
 * @param array|null $seoRow From _product_seo
 * @return array
 */
function buildProductSeo($product = [], ?array $seoRow): array {
    $fallbackDesc = trim(mb_substr(strip_tags($product['description'] ?? ''), 0, 160));

    $default = [
        'seo_title'       => $product['name'] ?? '',
        'seo_description' => $fallbackDesc,
        'canonical_url'   => current_url(),
        'focus_keyword'   => '',
        'noindex'         => 0,
        'seo_slug'        => '',
        'seo_mainImage'   => $product['image'] ?? '',
        'seo_cenaKKC'     => $product['cenaKKC'] ?? '',
        'seo_images'      => null,
    ];

    $seo = array_merge($default, $seoRow ?? []);

    // Ensure seo_images has shape ['main'=>..., 'gallery'=>[...]]
    if (!empty($seo['seo_images']) && is_array($seo['seo_images'])) {
        // accept both {'main': 'url', 'gallery':[]} and an array of objects with alt/title (your schema)
        if (!isset($seo['seo_images']['main']) && isset($product['image_main'])) {
            $seo['seo_images']['main'] = $product['image_main'];
        }
    } else {
        $seo['seo_images'] = [];
        if (!empty($product['image_main'])) {
            $seo['seo_images']['main'] = $product['image_main'];
        }
    }

    return $seo;
}


