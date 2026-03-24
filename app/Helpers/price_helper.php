<?php
if (!function_exists('calculateDiscount')) {
    function calculateDiscount($originalPrice, $promoPrice) {
        if ($originalPrice <= 0) {
            return null; // Предпазване от деление на нула
        }
        $discount = $originalPrice - $promoPrice;
        $percent  = ($discount / $originalPrice) * 100;
        return number_format($percent, 2, '.', ' ');
    }
}

if (!function_exists('getPromoPercentFromGensoftJson')) {
    function getPromoPercentFromGensoftJson($gensoftJson) {
        if (empty($gensoftJson)) {
            return null;
        }

        $decoded = json_decode($gensoftJson, true);
        if (!is_array($decoded)) {
            return null;
        }

        if (!isset($decoded['promoPercent'])) {
            return null;
        }

        $percent = (float) $decoded['promoPercent'];
        if ($percent == 0.0) {
            return null;
        }

        return $percent;
    }
}

if (!function_exists('calculatePromoPrice')) {
    function calculatePromoPrice($basePrice, $promoPercent) {
        if ($basePrice <= 0 || $promoPercent === null) {
            return null;
        }

        $percent = abs((float) $promoPercent);
        if ($percent <= 0) {
            return null;
        }

        $promoPrice = $basePrice - ($basePrice * ($percent / 100));
        return max(0, $promoPrice);
    }
}
