<?php
require_once __DIR__ .  '/../vendor/autoload.php'; // Asegúrate de tener MongoDB instalado vía Composer

try {
    // Crear conexión (reemplaza con tus credenciales de MongoDB Atlas)
    $client = new MongoDB\Client(
        "mongodb+srv://Gjuarez:Gabriel_03@gabriel.5u6ne.mongodb.net/?retryWrites=true&w=majority&appName=Gabriel"
    );

    // Seleccionar la base de datos
    $db = $client->restaurante;

} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
