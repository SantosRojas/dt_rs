<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuario no autorizado']);
    exit;
}

// Cargar configuración
$config_file = __DIR__ . '/../config/importaciones_config.php';
require_once __DIR__ . '/../config/database.php';

if (file_exists($config_file)) {
    $config = include $config_file;
    $sql_details = getDbConfigSSP();
} else {
    $sql_details = getDbConfigSSP();
}

header('Content-Type: application/json');

try {
    // Conexión a la base de datos
    $dsn = "sqlsrv:server={$sql_details['host']};Database={$sql_details['db']}";
    $pdo = new PDO($dsn, $sql_details['user'], $sql_details['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Obtener estructura de la vista
    $sql_estructura = "SELECT TOP 5 * FROM vw_importaciones ORDER BY 1";
    $stmt = $pdo->prepare($sql_estructura);
    $stmt->execute();
    $resultados = $stmt->fetchAll();

    $columnas = [];
    $datos_muestra = [];

    if (!empty($resultados)) {
        $columnas = array_keys($resultados[0]);
        $datos_muestra = $resultados;
    }

    // Obtener conteo total de registros
    $sql_count = "SELECT COUNT(*) as total FROM vw_importaciones";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute();
    $total_registros = $stmt_count->fetch()['total'];

    echo json_encode([
        'success' => true,
        'columnas' => $columnas,
        'datos_muestra' => $datos_muestra,
        'total_registros' => $total_registros,
        'primera_columna' => !empty($columnas) ? $columnas[0] : null
    ]);

} catch (PDOException $e) {
    error_log("Error de BD en obtener_estructura_vista.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error en obtener_estructura_vista.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>