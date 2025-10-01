<?php

require_once BASE_PATH . 'config/config.php';
require_once BASE_PATH . 'config/autoload.php';
require_once BASE_PATH . 'config/env.php';
require_once BASE_PATH . 'vendor/autoload.php';


loadEnv();

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

// Configuración de la base de datos (solo para traer las tiendas)
$host = isset($_ENV['DB_HOST_PROD']) ? $_ENV['DB_HOST_PROD'] : $_ENV['DB_HOST_DEV'];
$db = isset($_ENV['DB_NAME_PROD']) ? $_ENV['DB_NAME_PROD'] : $_ENV['DB_NAME_DEV'];
$user = isset($_ENV['DB_USER_PROD']) ? $_ENV['DB_USER_PROD'] : $_ENV['DB_USER_DEV'];
$pass = isset($_ENV['DB_PASS_PROD']) ? $_ENV['DB_PASS_PROD'] : $_ENV['DB_PASS_DEV'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

// Base URL
$baseUrl = 'https://172.36.16.62/registroClientes/cliente/inicio';

// Carpeta para guardar los códigos QR
$qrFolder = $_SERVER['DOCUMENT_ROOT'] . '/public/links/';
echo $qrFolder;
if (!is_dir($qrFolder)) {
    mkdir($qrFolder, 0777, true);
}

// Obtener todas las tiendas
$stmt = $pdo->prepare("SELECT codigo, nombre FROM tiendas");
$stmt->execute();
$tiendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tiendas as $tienda) {
    $code_id = $tienda['codigo'];
    $storeName = $tienda['nombre'];
    
    // Normalizar el nombre para el archivo
    $storeName = iconv('UTF-8', 'ASCII//TRANSLIT', $storeName);
    $storeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $storeName);
    
    $qrPath = $qrFolder . '/' . $storeName . '.png';
    
    // Si ya existe el archivo, lo saltamos
    if (file_exists($qrPath)) {
        echo "QR ya existe para tienda: {$storeName} - Saltando...\n";
        continue;
    }

    $primaryUrl = $baseUrl . '/' . $code_id;

    try {
        // Generar el QR
        $qrCode = new QrCode($primaryUrl);
        $qrCode->setSize(300);
        $qrCode->setBackgroundColor(new Color(253, 239, 226));
        $qrCode->setForegroundColor(new Color(0, 0, 0));
        $writer = new PngWriter();

        $result = $writer->write($qrCode);
        $result->saveToFile($qrPath);

        echo "QR generado y guardado para tienda: {$storeName}\n";

    } catch (Exception $e) {
        echo "Error al procesar tienda: {$storeName}. Error: " . $e->getMessage() . "\n";
    }
}

echo "Proceso completado.\n";
