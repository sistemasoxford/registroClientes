<?php
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/env.php';
require_once(QUERY_OBJECT_PATH . 'objectSelect.php');
require BASE_PATH . '/vendor/autoload.php';

// =========================================================================
// SOLUCIÓN: Usar los namespaces de PHPMailer
// Tu autoloader ya sabe cómo cargar estas rutas, solo hay que usarlas aquí.
// =========================================================================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; 

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
            "recipient" => $this->objOtp->getRecipient(), 
            "content" => $this->objOtp->getContent(),
            "type" => "transactional",
            "tag" => "Verificacion",
            "unicodeEnabled" => false
        ];

        try {
            // ... (código cURL para enviar SMS)
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
     * Envía un correo electrónico usando PHPMailer con la plantilla HTML.
     */
    function enviarCorreo($destinatario, $otp, $nombreCompleto) {
        // Guion
    $otpGuion = substr($otp, 0, 3) . '-' . substr($otp, 3, 3);   // 123-456
        
        // 1. Cargar la plantilla HTML
        $templatePath = BASE_PATH . '/template/email.html';
        if (!file_exists($templatePath)) {
             return ["success" => false, "message" => "Error: La plantilla de correo no se encontró en: " . $templatePath];
        }
        $htmlBody = file_get_contents($templatePath);

        // 2. Reemplazar placeholders en la plantilla
        $htmlBody = str_replace('[[NOMBRE_CLIENTE]]', $nombreCompleto, $htmlBody);
        $htmlBody = str_replace('[[CODIGO_OTP]]', $otpGuion, $htmlBody);

        // 3. Configurar datos del correo
        $data = [
            "from" => "notificaciones@oxfordjeans.com",
            "fromName" => "Oxford Jeans",
            "to" => $destinatario,
            "subject" => "Tu Código de Verificación: " . $otp, 
            "body" => $htmlBody, // Contenido HTML modificado
            "isHtml" => true,
        ];

        try {
            $mail = new PHPMailer(true);
            
            // Configuración del servidor SMTP de Brevo
            $mail->isSMTP();
            $mail->Host = 'smtp-relay.brevo.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'notificaciones@oxfordjeans.com';
            $mail->Password = 'JtchDspdGZAzIv16';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->CharSet = 'UTF-8';
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom($data['from'], $data['fromName']);
            $mail->addAddress($data['to']);

            // Configuración del contenido
            $mail->isHTML($data['isHtml']);
            $mail->Subject = $data['subject'];
            $mail->Body = $data['body']; // Usamos el HTML ya cargado y modificado

            // Enviar el correo
            if ($mail->send()) {
                return ["success" => true, "message" => "Código enviado exitosamente"];
            } else {
                return ["success" => false, "message" => "Error al enviar el correo: " . $mail->ErrorInfo];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => "Error enviando correo: " . $e->getMessage()];
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
     * Genera un OTP, lo setea en el mensaje y lo envía por SMS y Correo
     */
    function enviarOtp() {
        $otp = $this->generarOtp();
        
        // Obtener nombre completo
        $nombreCompleto = $_SESSION['cliente']['nombres'] . ' ' . $_SESSION['cliente']['apellidos'];
        
        // Mensaje para el SMS (aún necesario)
        $mensajeSMS = "Hola, $nombreCompleto, Tu código de verificación es: $otp \n" . " En Moda Oxford S.A.S., valoramos profundamente la confianza que depositas en nosotros. Por eso queremos invitarte a autorizar el tratamiento de tus datos personales, conforme a nuestra política 👉 https://www.oxfordjeans.com/terminos/tratamiento-de-datos Por seguridad, para autenticar tu identidad y completar la autorización, ingresa el código";

        // setear el mensaje en el modelo (para el SMS)
        $this->objOtp->setContent($mensajeSMS);
        $this->objOtp->setOtp($otp);

        // Enviar el SMS
        $resultadoSMS = $this->enviarSMS();

        // Enviar el correo - Ahora se pasa el nombre completo
        $resultadoCorreo = $this->enviarCorreo($_SESSION['cliente']['email'], $otp, $nombreCompleto);

        return [
            "otp" => $otp,
            "resultadoSMS" => $resultadoSMS,
            "resultadoCorreo" => $resultadoCorreo
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