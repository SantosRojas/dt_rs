<?php
/**
 * Endpoint que devuelve todos los registros filtrados en formato JSON
 * para exportación completa
 */
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$serverName = "pe01-wsqlprd01.bbmag.bbraun.com";
$connectionOptions = array(
    "Database" => "DP_BBRAUN_SAP",
    "Uid" => "sa_bbmpe",
    "PWD" => 'ItPeru22$#',
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    header('Content-Type: application/json');
    $errors = sqlsrv_errors();
    echo json_encode(['error' => 'Error de conexión a la base de datos', 'details' => $errors]);
    exit();
}

// Obtener parámetros de filtro
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$filter2 = isset($_GET['filter2']) ? $_GET['filter2'] : '';
$columnFilters = isset($_GET['columnFilters']) ? json_decode($_GET['columnFilters'], true) : [];

// Construir la consulta base
$sql = "SELECT 
    RegID_AE,
    ArtID_AE,
    ArtCodigo_AE, 
    ArtDescripcion_AE, 
    RegNumero_AE,
    RegResolucion_AE, 
    RegFechaEmision_AE, 
    RegFechaAprobacion_AE, 
    RegFechaVencimiento_AE,
    RegEstado_AE,
    ArtFabricante_AE,
    ArtPaisOrigen_AE,
    ArtLugarFabricacion_AE,
    NivelRiesgoPeru_AE,
    ExoneracionCM_AE,
    CodigoEAN_14,
    CodigoEAN_13,
    CodigoGTIN,
    EsEsteril_AE,
    Etiqueta_AE,
    NumeroIFU_AE,
    Codigo_GMDN_UMDNS,
    Cambios_AE,
    ProblemaDimensiones_AE,
    RegObservacion_AE
FROM vw_rs_ae WHERE 1=1";

$params = [];

// Aplicar filtro principal (estado del RS)
if ($filter === 'VENCIDO') {
    $sql .= " AND RegEstado_AE = 'VENCIDO'";
} elseif ($filter === 'PRORROGADO') {
    $sql .= " AND RegEstado_AE = 'PRORROGADO'";
} elseif ($filter === 'VENCE_3_MESES') {
    $sql .= " AND RegEstado_AE = 'VENCE 3 MESES'";
} elseif ($filter === 'VENCE_2_MESES') {
    $sql .= " AND RegEstado_AE = 'VENCE 2 MESES'";
} elseif ($filter === 'VENCE_1_MES') {
    $sql .= " AND RegEstado_AE = 'VENCE 1 MES'";
} elseif ($filter === 'VENCE_ESTE_MES') {
    $sql .= " AND RegEstado_AE = 'VENCE ESTE MES'";
}

// Aplicar filtro de tipo de artículo
// if (!empty($filter2)) {
//     $sql .= " AND ArtTipo_AE = ?";
//     $params[] = $filter2;
// }

// Aplicar filtros de columnas individuales
if (!empty($columnFilters)) {
    // Mapeo de índice de columna a nombre de columna en la vista
    $columnMap = [
        3 => 'ArtCodigo_AE',
        4 => 'ArtDescripcion_AE',
        5 => 'RegNumero_AE',
        6 => 'RegResolucion_AE',
        7 => 'RegFechaEmision_AE',
        8 => 'RegFechaAprobacion_AE',
        9 => 'RegFechaVencimiento_AE',
        10 => 'RegEstado_AE',
        11 => 'ArtFabricante_AE',
        12 => 'ArtPaisOrigen_AE',
        13 => 'ArtLugarFabricacion_AE',
        14 => 'NivelRiesgoPeru_AE',
        15 => 'ExoneracionCM_AE',
        16 => 'CodigoEAN_14',
        17 => 'CodigoEAN_13',
        18 => 'CodigoGTIN',
        19 => 'EsEsteril_AE',
        20 => 'Etiqueta_AE',
        21 => 'NumeroIFU_AE',
        22 => 'Codigo_GMDN_UMDNS',
        23 => 'Cambios_AE',
        24 => 'ProblemaDimensiones_AE',
        25 => 'RegObservacion_AE'
    ];

    // Columnas de fecha que necesitan conversión especial
    $dateColumns = ['RegFechaEmision_AE', 'RegFechaAprobacion_AE', 'RegFechaVencimiento_AE'];

    foreach ($columnFilters as $colIndex => $searchValue) {
        $colIndex = intval($colIndex);
        if (isset($columnMap[$colIndex]) && !empty($searchValue)) {
            $colName = $columnMap[$colIndex];

            if (in_array($colName, $dateColumns)) {
                // Para columnas de fecha, usar CONVERT para buscar en formato dd/mm/yyyy
                $sql .= " AND CONVERT(VARCHAR, $colName, 103) LIKE ?";
            } else {
                $sql .= " AND CAST($colName AS NVARCHAR(MAX)) LIKE ?";
            }
            $params[] = '%' . $searchValue . '%';
        }
    }
}

// Ordenar por fecha de vencimiento
$sql .= " ORDER BY RegFechaVencimiento_AE ASC";

// Ejecutar consulta
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en la consulta', 'sql' => $sql, 'details' => sqlsrv_errors()]);
    exit();
}

// Recopilar datos
$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Formatear fechas
    $emision = '';
    $aprobacion = '';
    $vencimiento = '';

    if ($row['RegFechaEmision_AE'] instanceof DateTime) {
        $emision = $row['RegFechaEmision_AE']->format('d/m/Y');
    } elseif (!empty($row['RegFechaEmision_AE'])) {
        $emision = date('d/m/Y', strtotime($row['RegFechaEmision_AE']));
    }

    if ($row['RegFechaAprobacion_AE'] instanceof DateTime) {
        $aprobacion = $row['RegFechaAprobacion_AE']->format('d/m/Y');
    } elseif (!empty($row['RegFechaAprobacion_AE'])) {
        $aprobacion = date('d/m/Y', strtotime($row['RegFechaAprobacion_AE']));
    }

    if ($row['RegFechaVencimiento_AE'] instanceof DateTime) {
        $vencimiento = $row['RegFechaVencimiento_AE']->format('d/m/Y');
    } elseif (!empty($row['RegFechaVencimiento_AE'])) {
        $vencimiento = date('d/m/Y', strtotime($row['RegFechaVencimiento_AE']));
    }

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

// Devolver JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['data' => $data, 'total' => count($data)]);
