<?php

// app/Helpers/euroRate_helper.php

if (!function_exists('priceToEur')) {

    // Функция за обратен курс на евро към лев
    function priceToEur($price, $fontSize = 14) {
        $setting = json_decode(service('settings') -> get('App.general')[0], true) ?? [];

        $preval   = $setting['preval'] ?? [];
        $currency = $preval['base_currency'] ?? '';
        $rate     = setingGlob('App.general')['euroRate'] ?? 1;      // Курс лев -> евро

        $rate = !empty($currency) ? 1.95583 : $rate;
        $sign = !empty($currency) ? 'лв.' : '€';

        if ($rate == 1) {
            //return;
        }

        $conv = preg_replace('/\.([0-9]*)/', '<sup class="font-size-' . $fontSize . '">.$1</sup>',  sprintf('%.2f', $price * $rate));

        return $conv . "<small class='fw-bold'>$sign</small>";
    }

    // Функция за обратен курс на евро към лев
    function priceToEur2($price) {
        $setting = json_decode(service('settings') -> get('App.general')[0], true) ?? [];

        $preval   = $setting['preval'] ?? [];
        $currency = $preval['base_currency'] ?? '';
        $rate     = setingGlob('App.general')['euroRate'] ?? 1;      // Курс лев -> евро

        $rate = !empty($currency) ? 1.95583 : $rate;
        $sign = !empty($currency) ? 'лв.' : '€';

        if ($rate == 1) {
            //return;
        }

        return sprintf('%.2f', $price * $rate) . ' ' . $sign;
    }
	
	 function get_valuta() {
        $setting = json_decode(service('settings') -> get('App.general')[0], true) ?? [];

        $preval   = $setting['preval'] ?? [];
        $currency = $preval['base_currency'] ?? '';

        return match ($currency) {
            'EUR' => '€',
            'USD' => '$',
            default => 'лв.',
        };
    }

    function priceDual($price) {
        $price = (float) $price;

        return sprintf('%.2f', $price) . ' ' . get_valuta() . '<br>(' . priceToEur2($price) . ')';
    }

}
