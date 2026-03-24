<?php

use Config\Services;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @link: https://codeigniter4.github.io/CodeIgniter4/
 */
function view(string $name, array $data = [], array $options = []): string {
    $renderer     = Services::renderer();
    $customConfig = new \Config\ValentiConfig\CustomConfig();

    $config   = config(View::class);
    $saveData = $config -> saveData;

    $data                 = $data + ['customConfig' => $customConfig];
    if (array_key_exists('saveData', $options)) {
        $saveData = (bool) $options['saveData'];
        unset($options['saveData']);
    }

    return $renderer -> setData($data, 'raw') -> render($name, $options, $saveData);
}

function base_url($uri = '', string $protocol = null): string {

    // If running from CLI, don't execute the function
    if (is_cli()) {
        return '';
    }

    // convert segment array to string
    if (is_array($uri)) {
        $uri = implode('/', $uri);
    }
    $uri = trim($uri, '/');

    // We should be using the configured baseURL that the user set;
    // otherwise get rid of the path, because we have
    // no way of knowing the intent...
    $config = \Config\Services::request() -> config;

    // If baseUrl does not have a trailing slash it won't resolve
    // correctly for users hosting in a subfolder.
    $baseUrl = !empty($config -> baseURL) && $config -> baseURL !== '/' ? rtrim($config -> baseURL, '/ ') . '/' : $config -> baseURL;

    $url = new \CodeIgniter\HTTP\URI($baseUrl);
    unset($config);

    // Merge in the path set by the user, if any
    if (!empty($uri)) {
        $url = $url -> resolveRelativeURI($uri);
    }

    // If the scheme wasn't provided, check to
    // see if it was a secure request
    if (empty($protocol) && \Config\Services::request() -> isSecure()) {
        $protocol = 'https';
    }

    if (!empty($protocol)) {
        $url -> setScheme($protocol);
    }

    return (string) $url;
}
