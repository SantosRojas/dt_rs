<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

// Cargar funciones auxiliares
require_once __DIR__ . '/../includes/importaciones_helpers.php';

// Configuración de la base de datos
$config_file = __DIR__ . '/../config/importaciones_config.php';
require_once __DIR__ . '/../config/database.php';

if (file_exists($config_file)) {
    $config = include $config_file;
    $sql_details = getDbConfigSSP();
} else {
    $sql_details = getDbConfigSSP();
}

// Verificar que hay códigos en la sesión
if (!isset($_SESSION['codigos_procesamiento']) || empty($_SESSION['codigos_procesamiento'])) {
    die('No hay códigos para exportar');
}

$codigos = $_SESSION['codigos_procesamiento'];

try {
    // Conexión a la base de datos
    $dsn = "sqlsrv:server={$sql_details['host']};Database={$sql_details['db']}";
    $pdo = new PDO($dsn, $sql_details['user'], $sql_details['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Consulta para obtener todos los datos
    $union_parts = [];
    foreach ($codigos as $i => $codigo) {
        $union_parts[] = "SELECT ? as codigo_busqueda";
    }

    $query = "
        WITH codigos_cte AS (
            " . implode(' UNION ALL ', $union_parts) . "
        )
        SELECT 
            c.codigo_busqueda as codigo_buscado,
            CASE 
                WHEN v.ArtCodigo IS NOT NULL THEN 'Encontrado'
                ELSE 'No Encontrado'
            END as estado,
            v.ArtDescripcion,
            v.RegistroSanitario,
            v.FechaVencimiento,
            v.PaisFabricacion,
            v.FactoryLocation,
            CASE 
                WHEN v.FechaVencimiento IS NOT NULL AND v.FechaVencimiento < CAST(GETDATE() AS DATE) THEN 'VENCIDO'
                WHEN v.FechaVencimiento IS NOT NULL THEN 'VIGENTE'
                ELSE 'N/A'
            END as estado_producto
        FROM codigos_cte c
        LEFT JOIN vw_importaciones v ON v.ArtCodigo = c.codigo_busqueda
        ORDER BY c.codigo_busqueda";

    $stmt = $pdo->prepare($query);
    $stmt->execute($codigos);
    $resultados = $stmt->fetchAll();

    // Configurar headers para descarga de Excel
    $filename = 'Resultados_Importacion_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    // Crear el output
    $output = fopen('php://output', 'w');

    // BOM para UTF-8
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Escribir información de resumen
    fputcsv($output, ['RESUMEN DE IMPORTACIÓN - ' . date('Y-m-d H:i:s')], ';');
    fputcsv($output, [''], ';');
    fputcsv($output, ['Usuario:', $_SESSION['usuario']], ';');
    fputcsv($output, ['Total procesados:', count($resultados)], ';');

    // Calcular estadísticas
    $encontrados = count(array_filter($resultados, function ($r) {
        return $r['estado'] === 'Encontrado'; }));
    $no_encontrados = count($resultados) - $encontrados;
    $vencidos = count(array_filter($resultados, function ($r) {
        return $r['estado_producto'] === 'VENCIDO'; }));
    $vigentes = count(array_filter($resultados, function ($r) {
        return $r['estado_producto'] === 'VIGENTE'; }));

    fputcsv($output, ['Encontrados:', $encontrados], ';');
    fputcsv($output, ['No encontrados:', $no_encontrados], ';');
    fputcsv($output, ['Productos vencidos:', $vencidos], ';');
    fputcsv($output, ['Productos vigentes:', $vigentes], ';');
    fputcsv($output, [''], ';');
    fputcsv($output, ['RESULTADOS DETALLADOS'], ';');
    fputcsv($output, [''], ';');

    // Headers de la tabla
    fputcsv($output, [
        'Código Buscado',
        'Estado',
        'ArtDescripcion',
        'RegistroSanitario',
        'FechaVencimiento',
        'PaisFabricacion',
        'FactoryLocation',
        'Estado Producto'
    ], ';');

    // Datos
    foreach ($resultados as $fila) {
        $fecha_vencimiento = '';
        if ($fila['FechaVencimiento']) {
            try {
                $fecha = new DateTime($fila['FechaVencimiento']);
                $fecha_vencimiento = $fecha->format('d/m/Y');
                if ($fila['estado_producto'] === 'VENCIDO') {
                    $fecha_vencimiento .= ' (VENCIDO)';
                }
            } catch (Exception $e) {
                $fecha_vencimiento = $fila['FechaVencimiento'];
            }
        }

        fputcsv($output, [
            $fila['codigo_buscado'],
            $fila['estado'],
            $fila['ArtDescripcion'] ?: '',
            $fila['RegistroSanitario'] ?: '',
            $fecha_vencimiento,
            $fila['PaisFabricacion'] ?: '',
            $fila['FactoryLocation'] ?: '',
            $fila['estado_producto']
        ], ';');
    }

    fclose($output);
    exit;

} catch (Exception $e) {
    error_log("Error en exportar_completo.php: " . $e->getMessage());
    die('Error al generar el archivo de exportación: ' . $e->getMessage());
}
?>