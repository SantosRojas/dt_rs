<?php
// Script para verificar configuración PHP y drivers
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Información de configuración PHP</h2>";

echo "<h3>1. Versión de PHP:</h3>";
echo phpversion() . "<br><br>";

echo "<h3>2. Extensiones cargadas:</h3>";
$extensions = get_loaded_extensions();
$sql_extensions = array_filter($extensions, function ($ext) {
    return stripos($ext, 'sql') !== false || stripos($ext, 'pdo') !== false;
});

if (empty($sql_extensions)) {
    echo "<p style='color: red;'>No se encontraron extensiones SQL</p>";
} else {
    foreach ($sql_extensions as $ext) {
        echo "- " . $ext . "<br>";
    }
}

echo "<h3>3. Drivers PDO disponibles:</h3>";
$pdo_drivers = PDO::getAvailableDrivers();
if (empty($pdo_drivers)) {
    echo "<p style='color: red;'>No hay drivers PDO disponibles</p>";
} else {
    foreach ($pdo_drivers as $driver) {
        echo "- " . $driver . "<br>";
    }
}

echo "<h3>4. Configuración específica SQL Server:</h3>";
if (extension_loaded('pdo_sqlsrv')) {
    echo "<p style='color: green;'>✓ PDO SQL Server está cargado</p>";
} else {
    echo "<p style='color: red;'>✗ PDO SQL Server NO está cargado</p>";
}

if (extension_loaded('sqlsrv')) {
    echo "<p style='color: green;'>✓ SQL Server está cargado</p>";
} else {
    echo "<p style='color: red;'>✗ SQL Server NO está cargado</p>";
}

echo "<h3>5. Test de conexión básica:</h3>";
try {
    require_once __DIR__ . '/../config/database.php';
    $conn = getDbConnection();
    echo "<p style='color: green;'>✓ Conexión básica exitosa</p>";
    $conn = null;
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . $e->getMessage() . "</p>";
}
?>