<?php
// Obtener el esquema (http o https)
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

// Obtener el nombre del host
$host = $_SERVER['HTTP_HOST'];

// Obtener el URI de la solicitud
$request_uri = $_SERVER['REQUEST_URI'];

// Combinar todo para formar la URL completa
$current_url = $scheme . '://' . $host . $request_uri;


// Definir las rutas de los menús en un array utilizando BASE_URL
$menu_routes = [
    'tickets' => BASE_URL . 'tickets/tickets',
];

// Ejemplo de cómo acceder a una ruta específica
// echo $menu_routes['tickets'];