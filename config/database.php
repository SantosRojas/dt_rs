<?php
/**
 * Configuración de conexión a base de datos
 * Carga las credenciales desde el archivo .env
 */

// Función para cargar variables del archivo .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("Archivo .env no encontrado en: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Separar clave=valor
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remover comillas si existen
            $value = trim($value, '"\'');

            // Establecer como variable de entorno
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
}

// Cargar el archivo .env desde la raíz del proyecto
$envPath = __DIR__ . '/../.env';
loadEnv($envPath);

// Configuración de base de datos usando variables de entorno
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));

/**
 * Obtiene una conexión PDO a la base de datos
 * @return PDO
 */
function getDbConnection()
{
    try {
        $conn = new PDO(
            "sqlsrv:server=" . DB_HOST . ";Database=" . DB_NAME,
            DB_USER,
            DB_PASSWORD
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        throw new Exception("Error de conexión: " . $e->getMessage());
    }
}

/**
 * Obtiene la configuración para SSP (Server-Side Processing)
 * @return array
 */
function getDbConfigSSP()
{
    return array(
        "host" => DB_HOST,
        "user" => DB_USER,
        "pass" => DB_PASSWORD,
        "db" => DB_NAME
    );
}

/**
 * Obtiene la configuración como array de opciones de conexión
 * @return array
 */
function getConnectionOptions()
{
    return array(
        "Database" => DB_NAME,
        "Uid" => DB_USER,
        "PWD" => DB_PASSWORD
    );
}
