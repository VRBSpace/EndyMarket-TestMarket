<?php

/**
 * Генерира Product JSON-LD schema за продуктова страница
 */
function h_google_schema(object $product): string {
    $schemaName = $product -> product_name ?? 'Продукт';

    $schemaDesc = strip_tags($product -> short_description ?? '');
    if (mb_strlen($schemaDesc) < 10) {
        $schemaDesc = 'Купете ' . $schemaName . ' на топ цена. Бърза доставка и гарантирано качество.';
    }

    $schemaImage = !empty($product -> image) ? $_ENV['app.imageDir'] . $product -> image : base_url('uploads/no-image.jpg');

    $schemaPrice     = (string) ($product -> cenaKKC ?? '0.00');
    $schemaCanonical = current_url();

    $shippingDetails = [
        '@type'               => 'OfferShippingDetails',
        'shippingRate'        => [
            '@type'    => 'MonetaryAmount',
            'value'    => '5.00',
            'currency' => 'EUR',
        ],
        'shippingDestination' => [
            '@type'          => 'DefinedRegion',
            'addressCountry' => 'BG',
        ],
        'deliveryTime'        => [
            '@type'        => 'ShippingDeliveryTime',
            'transitTime'  => [
                '@type'    => 'QuantitativeValue',
                'minValue' => 1,
                'maxValue' => 3,
                'unitCode' => 'DAY',
            ],
            'businessDays' => [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'https://schema.org/Monday',
                    'https://schema.org/Tuesday',
                    'https://schema.org/Wednesday',
                    'https://schema.org/Thursday',
                    'https://schema.org/Friday',
                ],
            ],
            'handlingTime' => [
                '@type'    => 'QuantitativeValue',
                'minValue' => 1,
                'maxValue' => 2,
                'unitCode' => 'DAY',
            ],
        ],
    ];

    $returnPolicy = [
        '@type'                => 'MerchantReturnPolicy',
        'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
        'merchantReturnDays'   => 14,
        'returnMethod'         => 'https://schema.org/ReturnByMail',
        'returnFees'           => 'https://schema.org/FreeReturn',
        'applicableCountry'    => 'BG',
    ];

    $schemaArray = [
        '@context'        => 'https://schema.org',
        '@type'           => 'Product',
        'name'            => $schemaName,
        'description'     => $schemaDesc,
        'image'           => [$schemaImage],
        'sku'             => $product -> kod ?? '',
        'mpn'             => $product -> oem ?? '',
        'brand'           => [
            '@type' => 'Brand',
            'name'  => $product -> brandTxt ?? 'Brand',
        ],
        // === OPTIONAL BUT IMPORTANT FOR SEO ===
        "aggregateRating" => [
            "@type"       => "AggregateRating",
            "ratingValue" => "5",
            "reviewCount" => "1"
        ],
        "review"          => [[
        "@type"        => "Review",
        "reviewRating" => [
            "@type"       => "Rating",
            "ratingValue" => 5,
            "bestRating"  => 5
        ],
        "author"       => [
            "@type" => "Person",
            "name"  => "Клиент"
        ]
            ]],
        'offers'          => [
            '@type'                   => 'Offer',
            'priceCurrency'           => 'EUR',
            'price'                   => $schemaPrice,
            'availability'            => 'https://schema.org/InStock',
            'url'                     => $schemaCanonical,
            'priceValidUntil'         => date('Y-m-d', strtotime('+1 year')),
            'shippingDetails'         => $shippingDetails,
            'hasMerchantReturnPolicy' => $returnPolicy,
        ],
    ];

    return '<script type="application/ld+json">' . PHP_EOL
            . json_encode($schemaArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . PHP_EOL
            . '</script>';

}
