<?php
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/env.php';

loadEnv();

class ControlCustomer {
    private $client;
    private $wsdl;
    private $options;

    function __construct() {
        $this->wsdl = "http://200.41.6.86/Y2_PROD/CustomerWcfService.svc?wsdl";

        $this->options = [
            'trace' => 1,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login' => $_SESSION['loginSoap'],
            'password' => $_ENV['SOAP_PASS'] ?? ''
        ];

        $this->client = new SoapClient($this->wsdl, $this->options);
    }

    /**
     * Construye los parámetros para el servicio AddNewCustomer
     */
    private function buildParams() {
        return [
            'customerData' => [
                'FirstName'      => $_SESSION['cliente']['nombres'] ?? '',
                'LastName'       => $_SESSION['cliente']['apellidos'] ?? '',
                'IsCompany'      => false,
                'PassportNumber' => $_SESSION['cliente']['tDocumento'] . ";" . $_SESSION['cliente']['documento'] ?? '',
                'LanguageId'     => 'ESP',
                'NationalityId'  => 'COL',
                'Sex'            => $_SESSION['cliente']['sexo'] ?? '',

                'BirthDateData' => [
                    'BirthDateDay'   => $_SESSION['cliente']['diaNacimiento'] ?? null,
                    'BirthDateMonth' => $_SESSION['cliente']['mesNacimiento'] ?? null,
                    'BirthDateYear'  => $_SESSION['cliente']['anioNacimiento'] ?? null
                ],

                'EmailData' => [
                    'Email'            => $_SESSION['cliente']['email'] ?? '',
                    'EmailingAccepted' => true
                ],
                'PhoneData' => [
                    'CellularPhoneNumber' => $_SESSION['cliente']['telefono'] ?? ''
                ],
                'AddressData' => [
                    'AddressLine1'  => $_SESSION['cliente']['nombre_ciudad'] ?? '',
                    'City'          => $_SESSION['cliente']['nombre_ciudad'] ?? '',
                    'ZipCode'       => $_SESSION['cliente']['codigo_postal'] ?? '',
                    'CountryId'     => 'COL',
                    'CountryIdType' => 'ISO3',
                    'RegionId'      => $_SESSION['cliente']['codigo_departamento'] ?? ''
                ],
                'UsualStoreId'   => $_SESSION['cliente']['codigo_tienda'],

                'UserFields' => [
                    'UserField' => [
                        [
                            'Id'        => 1,
                            'TextValue' => 'S',
                            'ValueType' => 'TextValueKind'
                        ]
                    ]
                ],

                'LongDescription' => 'Registro sorteo instagram'
            ],
            'clientContext' => [
                'DatabaseId' => 'Y2_C4_PROD'
            ]
        ];
    }

    /**
     * Llama al servicio SOAP AddNewCustomer
     */
    public function addNewCustomer() {
        try {
            $params = $this->buildParams();
            $response = $this->client->__soapCall('AddNewCustomer', [$params]);

            return [
                "success" => true,
                "response" => $response
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "error"   => $e->getMessage(),
                "request" => $this->client->__getLastRequest(),
                "response"=> $this->client->__getLastResponse()
            ];
        }
    }
}
