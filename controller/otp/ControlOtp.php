<?php
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/env.php';
require_once(QUERY_OBJECT_PATH . 'objectSelect.php');

loadEnv();

class ControlOtp {
    private $objOtp;
    var $objCliente;
    private $apiKey;
    private $baseUrl;
    private $objControlConexion;

    function __construct($objOtp = null, $objCliente = null) {
        $this->objOtp = $objOtp;
        $this->objCliente = $objCliente;
        $this->apiKey = $_ENV['BREVO']; 
        $this->baseUrl = "https://api.brevo.com/v3/transactionalSMS/send";
        $this->objControlConexion = ControlConexion::getInstance();
    }

    /**
     * Envía un SMS usando el contenido definido en $objOtp
     */
    function enviarSMS() {
        $data = [
            "sender" => "OXFORDJEANS",
            "recipient" => $this->objOtp->getRecipient(), // importante: debe ser en formato internacional
            "content" => $this->objOtp->getContent(),
            "type" => "transactional",
            "tag" => "Verificacion",
            "unicodeEnabled" => false
        ];

        try {
            $ch = curl_init($this->baseUrl);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "accept: application/json",
                "api-key: {$this->apiKey}",
                "content-type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new Exception("Error cURL: " . curl_error($ch));
            }

            curl_close($ch);

            $decoded = json_decode($response, true);

            if ($httpCode >= 200 && $httpCode < 300) {
                return ["success" => true, "data" => $decoded];
            } else {
                return [
                    "success" => false,
                    "status" => $httpCode,
                    "error" => $decoded['message'] ?? $response
                ];
            }

        } catch (Exception $e) {
            return ["success" => false, "message" => "Error enviando SMS: " . $e->getMessage()];
        }
    }

    /**
     * Genera un código OTP de 6 dígitos
     */
    function generarOtp() {
        $otp = '';
        for ($i = 0; $i < 6; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    }

    /**
     * Genera un OTP, lo setea en el mensaje y lo envía por SMS
     */
    function enviarOtp() {
        $otp = $this->generarOtp();
        $mensaje = "Hola, " . $_SESSION['cliente']['nombres'] . " " . $_SESSION['cliente']['apellidos'] . ", Tu código de verificación es: $otp \n" . " En Moda Oxford S.A.S., valoramos profundamente la confianza que depositas en nosotros. Por eso queremos invitarte a autorizar el tratamiento de tus datos personales, conforme a nuestra política 👉 https://www.oxfordjeans.com/terminos/tratamiento-de-datos Por seguridad, para autenticar tu identidad y completar la autorización, ingresa el código";

        // setear el mensaje en el modelo
        $this->objOtp->setContent($mensaje);
        $this->objOtp->setOtp($otp);

        $resultado = $this->enviarSMS();

        return [
            "otp" => $otp,
            "resultado" => $resultado
        ];
    }

    function validarOtp(){
        $documento = $this->objCliente->getDocumento();
        $otp = $this->objOtp->getOtp();

        // Conexión a la base de datos
        $querySelect = new QuerySelect();
        $comandoSql = $querySelect->select("otp")
                                  ->from("habeas_data")
                                  ->where("id = '$documento' AND otp = '$otp' AND created_at >= NOW() - INTERVAL 2 MINUTE");

        try{
            $this->objControlConexion->abrirBd();
            $recordSet = $this->objControlConexion->ejecutarSelect($comandoSql);

            
            if (mysqli_num_rows($recordSet) > 0) {
                return ["success" => true, "message" => "Cliente registrado correctamente."];
            }else{
                return ["success" => false, "message" => "Código inválido."];
            }

        }catch(Exception $e){
            throw new Exception("Error durante la conexión a la base de datos: " . $e->getMessage());
        }finally{
            $this->objControlConexion->cerrarBd();
        }
    }
}
