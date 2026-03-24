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
            return $this -> econt_action($method);
        }
        $location = $locations[$method] ?? null;

        if ($location) {
            $jsonData = ['name' => $cityName];
            $response = $this -> speedyRequest($location, $jsonData);

            return $response;
        }

        return json_encode(['error' => 'Invalid method']);
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
}

//return $this -> response -> setJSON(json_encode($data['selectOptions'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
