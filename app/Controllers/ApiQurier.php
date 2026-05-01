<?php

namespace App\Controllers;

class ApiQurier extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function econt_action($method = '') {
        $method = $this -> request -> getVar('method');

        if ($method == 'get_cities') {
            $url      = "https://ee.econt.com/json_rpc.php?skip_loading_session=1";
            $cityName = $this -> request -> getVar('cityName');

            $data = [
                //'id'      => 7422243211769657212,
                'jsonrpc' => '2.0',
                //'method'  => 'Suggest.suggestCity',
                'method'  => 'ApiExternalSuggest.getCities',
                'params'  => [
                    //'params' => ['country' => 1033],
                    'text' => $cityName
                ]
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
        }

        if ($method == 'get_ofices') {
            $cityId = $this -> request -> getVar('cityId');

            $response = $this ::EcontRequest("Nomenclatures/NomenclaturesService.getOffices.json", array(
                        'cityID'      => $cityId,
                        'countryCode' => 'BGR'
            ));
        }

        return json_encode($response);
    }

    public function speedy_action() {
        $method   = $this -> request -> getVar('method');
        $cityName = $this -> request -> getVar('cityName');

        $locations = [
            'get_cities' => 'location/site',
            'get_ofices' => 'location/office'
        ];

        if ($method == 'get_cities') {
            $response = $this -> speedyRequest($locations[$method], ['name' => $cityName]);

            return $response;
        }
        $location = $locations[$method] ?? null;

        if ($location) {
            $jsonData = ['name' => $cityName];
            $response = $this -> speedyRequest($location, $jsonData);

            return $response;
        }

        return json_encode(['error' => 'Invalid method']);
    }

    public function api_calculateDostavka($courierMethod = '') {
        $payload = $this -> request -> getJSON(true);

        if (!is_array($payload)) {
            $payload = $this -> request -> getPost();
        }

        if (empty($courierMethod)) {
            return $this -> response -> setJSON(['error' => 'Липсва метод на доставка.']);
        }

        try {
            $payload['weight'] = (float) ($payload['weight'] ?? $this -> getCartTotalWeight());
            $payload['packCount'] = (int) ($payload['packCount'] ?? $this -> getCartPackCount());

            if ($payload['weight'] <= 0) {
                $payload['weight'] = 1;
            }

            $result = match ($courierMethod) {
                'econt_office', 'econt_machina', 'econt_door' => $this -> econt_calculateDostavka($payload),
                'speedy_office', 'speedy_machina', 'speedy_door' => $this -> speedy_calculateDostavka($payload),
                default => ['error' => 'Неподдържан куриерски метод.'],
            };

            return $this -> response -> setJSON($result);
        } catch (\Throwable $e) {
            log_message('error', 'Delivery estimate failed: ' . $e -> getMessage());
            return $this -> response -> setJSON(['error' => 'Неуспешна калкулация на доставката.']);
        }
    }

    public static function EcontRequest($method, $params = array(), $endpoint = 'https://ee.econt.com/services', $timeout = 10) {
        $settings = json_decode(service('settings') -> get('App.general')[0] ?? []);

        //production endpoint
        $auth['login']    = $settings -> econtUser;
        $auth['password'] = $settings -> econtPass;

        $ch         = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint . '/' . rtrim($method, '/'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        if (!empty($auth))
            curl_setopt($ch, CURLOPT_USERPWD, $auth['login'] . ':' . $auth['password']);
        if (!empty($params))
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_TIMEOUT, !empty($timeout) && intval($timeout) ? $timeout : 4);
        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $jsonResponse = json_decode($response, true);

        if (!$jsonResponse) {
            throw new \Exception("Invalid response.");
        }
        if (strpos($httpStatus, '2') !== 0) {

            $msg         = trim($jsonResponse['message']);
            $innerMsgs   = array();
            foreach ($jsonResponse['innerErrors'] as $e)
                $innerMsgs[] = self::flattenError($e);
            if (!empty($msg) && !empty($innerMsgs)) {
                $msg .= ": ";
            }
            return ['error' => $msg . implode("; ", array_filter($innerMsgs))];

            // throw new \Exception(self::flattenError($jsonResponse));//simple error handling by combining all the returned error's messages
        } else {
            return $jsonResponse;
        }
    }

    public static function flattenError($err) {
        $msg         = trim($err['message']);
        $innerMsgs   = array();
        foreach ($err['innerErrors'] as $e)
            $innerMsgs[] = self::flattenError($e);
        if (!empty($msg) && !empty($innerMsgs)) {
            $msg .= ": ";
        }
        return $msg . implode("; ", array_filter($innerMsgs));
    }

    public function speedyRequest($location = null, $jsonData = []) {
        $settings = json_decode(service('settings') -> get('App.general')[0] ?? []);

        $jsonData += array(
            'userName'  => $settings -> speedyUser,
            'password'  => $settings -> speedyPass,
            'language'  => 'BG',
            'countryId' => '100',
        );

        if (in_array('unsetCountryId', $jsonData)) {
            unset($jsonData['countryId'], $jsonData[0]);
        }

        if (isset($arg['canselShipment'])) {
            $location = $arg['canselShipment']['location'];
            $jsonData += $arg['canselShipment']['arg'];
            //_______________________________________
        } elseif (isset($arg['getAllOfices'])) {
            $location = 'location/office';
            $jsonData += ['name' => $arg['cityName']];
        } elseif (isset($arg['search'])) {
            // $location = $this -> request -> getVar('location');
            $location = $arg['location'];
            $jsonData += ['name' => $arg['name'], 'siteId' => $arg['cityId']];
            //_____________________________________
        } elseif (isset($arg['calculateDostavka'])) {

            $location = 'calculate/';
            $jsonData += [
                'sender'    => ['clientId' => $arg['profilSender'] -> clientId],
                'recipient' =>
                (isset($arg['tk_order']['office_id']) && $arg['tk_order']['office_id'] != '') ?
                [
            'privatePerson'  => true,
            'pickupOfficeId' => $arg['tk_order']['office_id']
                ] :
                [
            'privatePerson'   => true,
            'addressLocation' => ['siteId' => $arg['tk_order']['city_id']]
                ],
                'service'   => [
                    "autoAdjustPickupDate" => true,
                    "serviceIds"           => [505],
                ],
                'content'   => [
                    'parcelsCount' => 1,
                    'totalWeight'  => $arg['calc']['totalWeight']
                ],
                'payment'   => ['courierServicePayer' => 'RECIPIENT'],
            ];
        }
        //dd($jsonData);
        $jsonDataEncoded = json_encode($jsonData);
        // dd($jsonDataEncoded);

        $curl         = curl_init('https://api.speedy.bg/v1/' . $location);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        $jsonResponse = curl_exec($curl);

        if ($jsonResponse === FALSE) {
            exit("cURL Error: " . curl_error($curl));
        }
        return($jsonResponse);
    }

    private function econt_calculateDostavka(array $data = []): array {
        $profile = $this ::EcontRequest("Profile/ProfileService.getClientProfiles.json", [0]);

        if (!empty($profile['error'])) {
            return ['error' => $profile['error']];
        }

        $senderAddress = $profile['profiles'][0]['addresses'][0] ?? null;

        if (empty($senderAddress)) {
            return ['error' => 'Липсва адрес на подателя за Еконт.'];
        }

        $cityName = trim((string) ($data['grad'] ?? ''));
        $postCode = trim((string) ($data['postCode'] ?? ''));
        $officeText = trim((string) ($data['ofis'] ?? ''));
        $isOffice = str_contains((string) ($data['izborKurier'] ?? ''), '_office') || str_contains((string) ($data['izborKurier'] ?? ''), '_machina');

        if ($cityName === '') {
            return ['error' => 'Липсва населено място за калкулация.'];
        }

        $label = [
            'senderAddress'   => $senderAddress,
            'receiverAddress' => [
                'city'   => array_filter([
                    'name'     => $cityName,
                    'postCode' => $postCode,
                ]),
                'street' => trim((string) ($data['street'] ?? '')) ?: '1',
                'num'    => trim((string) ($data['street_num'] ?? '')) ?: '1',
                'quarter'=> trim((string) ($data['quarter'] ?? '')) ?: null,
                'other'  => trim((string) ($data['other'] ?? '')) ?: null,
            ],
            'packCount'       => max(1, (int) ($data['packCount'] ?? 1)),
            'shipmentType'    => 'pack',
            'weight'          => max(0.1, (float) ($data['weight'] ?? 1)),
        ];

        if ($isOffice) {
            $officeCode = trim((string) ($data['office_code'] ?? ''));

            if ($officeCode === '') {
                $officeCode = $this -> resolveEcontOfficeCode($cityName, $officeText);
            }

            if ($officeCode === '') {
                return ['error' => 'Не може да бъде открит офис на Еконт за избраното населено място.'];
            }

            $label['receiverOfficeCode'] = $officeCode;
            unset($label['receiverAddress']['street'], $label['receiverAddress']['num'], $label['receiverAddress']['quarter'], $label['receiverAddress']['other']);
        }

        $response = $this ::EcontRequest("Shipments/LabelService.createLabel.json", [
            'mode'  => 'calculate',
            'label' => $label,
        ]);

        if (!empty($response['error'])) {
            return ['error' => $response['error']];
        }

        $totalPrice = (float) ($response['label']['totalPrice'] ?? 0);

        return ['totalPrice' => sprintf('%0.2f', $totalPrice)];
    }

    private function speedy_calculateDostavka(array $data = []): array {
        $cityName = trim((string) ($data['grad'] ?? ''));
        $officeText = trim((string) ($data['ofis'] ?? ''));
        $courierMethod = (string) ($data['izborKurier'] ?? '');
        $isOffice = str_contains($courierMethod, '_office') || str_contains($courierMethod, '_machina');

        if ($cityName === '') {
            return ['error' => 'Липсва населено място за калкулация.'];
        }

        $contractResponse = json_decode($this -> speedyRequest('client/contract', []), true);
        $clientId = $contractResponse['clients'][0]['clientId'] ?? null;

        if (empty($clientId)) {
            return ['error' => 'Липсва конфигурация на подателя за Speedy.'];
        }

        $recipient = ['privatePerson' => true];

        if ($isOffice) {
            $officeId = (int) ($data['office_id'] ?? 0);

            if ($officeId <= 0) {
                $officeId = $this -> resolveSpeedyOfficeId($cityName, $officeText);
            }

            if ($officeId <= 0) {
                return ['error' => 'Не може да бъде открит офис на Speedy за избраното населено място.'];
            }

            $recipient['pickupOfficeId'] = $officeId;
        } else {
            $siteId = (int) ($data['city_id'] ?? 0);

            if ($siteId <= 0) {
                $siteId = $this -> resolveSpeedySiteId($cityName);
            }

            if ($siteId <= 0) {
                return ['error' => 'Не може да бъде открито населено място в Speedy.'];
            }

            $recipient['addressLocation'] = ['siteId' => $siteId];
        }

        $response = json_decode($this -> speedyRequest('calculate/', [
            'sender'    => ['clientId' => $clientId],
            'recipient' => $recipient,
            'service'   => [
                'autoAdjustPickupDate' => true,
                'serviceIds'           => [505],
            ],
            'content'   => [
                'parcelsCount' => max(1, (int) ($data['packCount'] ?? 1)),
                'totalWeight'  => max(0.1, (float) ($data['weight'] ?? 1)),
            ],
            'payment'   => ['courierServicePayer' => 'RECIPIENT'],
        ]), true);

        if (!empty($response['error']['message'])) {
            return ['error' => $response['error']['message']];
        }

        $totalPrice = (float) ($response['calculations'][0]['totalPrice'] ?? 0);

        return ['totalPrice' => sprintf('%0.2f', $totalPrice)];
    }

    private function resolveSpeedySiteId(string $cityName): int {
        $response = json_decode($this -> speedyRequest('location/site', ['name' => $cityName]), true);
        $sites = $response['sites'] ?? [];

        foreach ($sites as $site) {
            if (mb_strtolower((string) ($site['name'] ?? '')) === mb_strtolower($cityName)) {
                return (int) ($site['id'] ?? 0);
            }
        }

        return (int) ($sites[0]['id'] ?? 0);
    }

    private function resolveSpeedyOfficeId(string $cityName, string $officeText): int {
        $response = json_decode($this -> speedyRequest('location/office', ['name' => $cityName]), true);
        $offices = $response['offices'] ?? [];

        return (int) ($this -> matchOffice($offices, $officeText, 'id') ?? 0);
    }

    private function resolveEcontOfficeCode(string $cityName, string $officeText): string {
        $cityId = $this -> resolveEcontCityId($cityName);

        if (!$cityId) {
            return '';
        }

        $response = $this ::EcontRequest("Nomenclatures/NomenclaturesService.getOffices.json", [
            'cityID'      => $cityId,
            'countryCode' => 'BGR',
        ]);

        $offices = $response['offices'] ?? [];

        return (string) ($this -> matchOffice($offices, $officeText, 'code') ?? '');
    }

    private function resolveEcontCityId(string $cityName): int {
        $url = "https://ee.econt.com/json_rpc.php?skip_loading_session=1";
        $data = [
            'jsonrpc' => '2.0',
            'method'  => 'ApiExternalSuggest.getCities',
            'params'  => [
                'text' => $cityName,
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        $decoded = json_decode($response, true);
        $cities = $decoded['result']['cities'] ?? [];

        foreach ($cities as $city) {
            if (mb_strtolower((string) ($city['name'] ?? '')) === mb_strtolower($cityName)) {
                return (int) ($city['id'] ?? 0);
            }
        }

        return (int) ($cities[0]['id'] ?? 0);
    }

    private function matchOffice(array $offices, string $officeText, string $field) {
        $officeText = mb_strtolower(trim($officeText));

        foreach ($offices as $office) {
            $name = mb_strtolower((string) ($office['name'] ?? ''));
            $fullAddress = mb_strtolower((string) ($office['address']['fullAddressString'] ?? $office['address']['fullAddress'] ?? ''));

            if ($officeText !== '' && (str_contains($officeText, $name) || ($fullAddress !== '' && str_contains($officeText, $fullAddress)))) {
                return $office[$field] ?? null;
            }
        }

        return $offices[0][$field] ?? null;
    }

    private function getCartTotalWeight(): float {
        $cartSession = $this -> getSessionProperty();
        $totalWeight = 0.0;

        foreach (($cartSession['products'] ?? []) as $product) {
            $quantity = (int) ($product['quantity'] ?? 0);
            $weight = (float) ($product['product_info'] -> teglo ?? 0);

            if ($weight <= 0) {
                $weight = 1;
            }

            $totalWeight += $weight * max(1, $quantity);
        }

        return max(0.1, $totalWeight);
    }

    private function getCartPackCount(): int {
        $cartSession = $this -> getSessionProperty();

        return max(1, (int) array_sum(array_map(
            static fn($product) => (int) ($product['quantity'] ?? 0),
            $cartSession['products'] ?? []
        )));
    }
}

//return $this -> response -> setJSON(json_encode($data['selectOptions'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
