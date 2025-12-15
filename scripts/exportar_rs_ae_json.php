<?php
/**
 * Endpoint que devuelve todos los registros filtrados en formato JSON
 * para exportación completa
 * 
 * Parámetros:
 * - countOnly=1: Solo devuelve el conteo (rápido)
 * - Sin countOnly: Devuelve todos los datos
 */

// Aumentar límites para exportación de grandes volúmenes
set_time_limit(300);
ini_set('memory_limit', '512M');

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../config/database.php';

$connectionOptions = array(
    "Database" => DB_NAME,
    "Uid" => DB_USER,
    "PWD" => DB_PASSWORD,
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect(DB_HOST, $connectionOptions);

if ($conn === false) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error de conexión', 'details' => sqlsrv_errors()]);
    exit();
}

// Obtener parámetros
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$filter2 = isset($_GET['filter2']) ? $_GET['filter2'] : '';
$columnFilters = isset($_GET['columnFilters']) ? json_decode($_GET['columnFilters'], true) : [];
$countOnly = isset($_GET['countOnly']) && $_GET['countOnly'] == '1';

// Construir WHERE
$whereClause = " WHERE 1=1";
$params = [];

// Filtro principal (estado)
if ($filter === 'VENCIDO') {
    $whereClause .= " AND RegEstado_AE = 'VENCIDO'";
} elseif ($filter === 'PRORROGADO') {
    $whereClause .= " AND RegEstado_AE = 'PRORROGADO'";
} elseif ($filter === 'VENCE_3_MESES') {
    $whereClause .= " AND RegEstado_AE = 'VENCE 3 MESES'";
} elseif ($filter === 'VENCE_2_MESES') {
    $whereClause .= " AND RegEstado_AE = 'VENCE 2 MESES'";
} elseif ($filter === 'VENCE_1_MES') {
    $whereClause .= " AND RegEstado_AE = 'VENCE 1 MES'";
} elseif ($filter === 'VENCE_ESTE_MES') {
    $whereClause .= " AND RegEstado_AE = 'VENCE ESTE MES'";
}

// Filtros de columnas
if (!empty($columnFilters)) {
    $columnMap = [
        2 => 'ArtCodigo_AE', 3 => 'ArtCodigo_AE',
        4 => 'ArtDescripcion_AE', 5 => 'RegNumero_AE',
        6 => 'RegResolucion_AE', 7 => 'RegFechaEmision_AE',
        8 => 'RegFechaAprobacion_AE', 9 => 'RegFechaVencimiento_AE',
        10 => 'RegEstado_AE', 11 => 'ArtFabricante_AE',
        12 => 'ArtPaisOrigen_AE', 13 => 'ArtLugarFabricacion_AE',
        14 => 'NivelRiesgoPeru_AE', 15 => 'ExoneracionCM_AE',
        16 => 'CodigoEAN_14', 17 => 'CodigoEAN_13',
        18 => 'CodigoGTIN', 19 => 'EsEsteril_AE',
        20 => 'Etiqueta_AE', 21 => 'NumeroIFU_AE',
        22 => 'Codigo_GMDN_UMDNS', 23 => 'Cambios_AE',
        24 => 'ProblemaDimensiones_AE', 25 => 'RegObservacion_AE', 26 => 'RegObservacion_AE'
    ];
    $dateColumns = ['RegFechaEmision_AE', 'RegFechaAprobacion_AE', 'RegFechaVencimiento_AE'];

    foreach ($columnFilters as $colIndex => $searchValue) {
        $colIndex = intval($colIndex);
        if (isset($columnMap[$colIndex]) && !empty($searchValue)) {
            $colName = $columnMap[$colIndex];
            if (in_array($colName, $dateColumns)) {
                $whereClause .= " AND CONVERT(VARCHAR, $colName, 103) LIKE ?";
            } else {
                $whereClause .= " AND CAST($colName AS NVARCHAR(MAX)) LIKE ?";
            }
            $params[] = '%' . $searchValue . '%';
        }
    }
}

// Si solo se pide conteo, hacer COUNT rápido
if ($countOnly) {
    $sqlCount = "SELECT COUNT(*) as total FROM vw_rs_ae" . $whereClause;
    $stmtCount = sqlsrv_query($conn, $sqlCount, $params);
    
    if ($stmtCount === false) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error en conteo', 'details' => sqlsrv_errors()]);
        exit();
    }
    
    $row = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmtCount);
    sqlsrv_close($conn);
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['total' => $row['total']]);
    exit();
}

// Consulta completa
$sql = "SELECT ArtCodigo_AE, ArtDescripcion_AE, RegNumero_AE, RegResolucion_AE, 
    RegFechaEmision_AE, RegFechaAprobacion_AE, RegFechaVencimiento_AE, RegEstado_AE,
    ArtFabricante_AE, ArtPaisOrigen_AE, ArtLugarFabricacion_AE, NivelRiesgoPeru_AE,
    ExoneracionCM_AE, CodigoEAN_14, CodigoEAN_13, CodigoGTIN, EsEsteril_AE,
    Etiqueta_AE, NumeroIFU_AE, Codigo_GMDN_UMDNS, Cambios_AE, ProblemaDimensiones_AE, RegObservacion_AE
FROM vw_rs_ae" . $whereClause . " ORDER BY RegFechaVencimiento_AE ASC";

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en consulta', 'details' => sqlsrv_errors()]);
    exit();
}

$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $emision = $row['RegFechaEmision_AE'] instanceof DateTime ? $row['RegFechaEmision_AE']->format('d/m/Y') : '';
    $aprobacion = $row['RegFechaAprobacion_AE'] instanceof DateTime ? $row['RegFechaAprobacion_AE']->format('d/m/Y') : '';
    $vencimiento = $row['RegFechaVencimiento_AE'] instanceof DateTime ? $row['RegFechaVencimiento_AE']->format('d/m/Y') : '';

    $data[] = [
        'Código' => $row['ArtCodigo_AE'] ?? '',
        'Descripción' => $row['ArtDescripcion_AE'] ?? '',
        'RS' => $row['RegNumero_AE'] ?? '',
        'Resolución' => $row['RegResolucion_AE'] ?? '',
        'Emisión' => $emision,
        'Aprobación' => $aprobacion,
        'Vencimiento' => $vencimiento,
        'Estado' => $row['RegEstado_AE'] ?? '',
        'Fabricante' => $row['ArtFabricante_AE'] ?? '',
        'País Origen' => $row['ArtPaisOrigen_AE'] ?? '',
        'Lugar Fabricación' => $row['ArtLugarFabricacion_AE'] ?? '',
        'Nivel Riesgo' => $row['NivelRiesgoPeru_AE'] ?? '',
        'Exoneración CM' => $row['ExoneracionCM_AE'] ?? '',
        'EAN-14' => $row['CodigoEAN_14'] ?? '',
        'EAN-13' => $row['CodigoEAN_13'] ?? '',
        'GTIN' => $row['CodigoGTIN'] ?? '',
        'Es Estéril' => $row['EsEsteril_AE'] ?? '',
        'Etiqueta' => $row['Etiqueta_AE'] ?? '',
        'Número IFU' => $row['NumeroIFU_AE'] ?? '',
        'GMDN/UMDNS' => $row['Codigo_GMDN_UMDNS'] ?? '',
        'Cambios' => $row['Cambios_AE'] ?? '',
        'Problema Dimensiones' => $row['ProblemaDimensiones_AE'] ?? '',
        'Observación' => $row['RegObservacion_AE'] ?? ''
    ];
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['data' => $data, 'total' => count($data)]);
