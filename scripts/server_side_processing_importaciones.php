<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Usuario no autorizado']);
    exit;
}

// DB table to use
$table = "vw_importaciones";

// Table's primary key
$primaryKey = "ArtCodigo";

// Array of database columns which should be read and sent back to DataTables.
$columns = [
    [
        "db" => "ArtCodigo",
        "dt" => "DT_RowId",
        "formatter" => function ($d, $row) {
            return "ID_" . $d;
        },
    ],
    [
        "db" => "ArtCodigo",
        "dt" => "codigo_buscado",
        "formatter" => function ($d, $row) {
            return '<strong>' . htmlspecialchars($d) . '</strong>';
        }
    ],
    ["db" => "ArtDescripcion", "dt" => "ArtDescripcion"],
    ["db" => "RegistroSanitario", "dt" => "RegistroSanitario"],
    [
        "db" => "FechaVencimiento",
        "dt" => "FechaVencimiento",
        "formatter" => function ($d, $row) {
            if ($d == "" || $d == null) {
                return "-";
            } else {
                $fecha = date("d/m/Y", strtotime($d));
                // Verificar si está vencido
                $fechaVencimiento = new DateTime($d);
                $fechaActual = new DateTime();
                if ($fechaVencimiento < $fechaActual) {
                    $fecha .= ' <i class="fas fa-exclamation-triangle text-danger" title="Producto Vencido"></i>';
                }
                return $fecha;
            }
        },
    ],
    [
        "db" => "Estado",
        "dt" => "Estado",
        "formatter" => function ($d, $row) {
            if ($d == "" || $d == null) {
                return '<span class="badge badge-secondary">N/A</span>';
            } else {
                // Mostrar el estado con un badge apropiado
                switch (strtoupper(trim($d))) {
                    case 'VIGENTE':
                        return '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>VIGENTE</span>';
                    case 'VENCIDO':
                        return '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>VENCIDO</span>';
                    case 'SUSPENDIDO':
                        return '<span class="badge badge-warning"><i class="fas fa-pause-circle mr-1"></i>SUSPENDIDO</span>';
                    default:
                        return '<span class="badge badge-info">' . htmlspecialchars($d) . '</span>';
                }
            }
        }
    ],
    ["db" => "PaisFabricacion", "dt" => "PaisFabricacion"],
    ["db" => "FactoryLocation", "dt" => "FactoryLocation"],
    [
        "db" => "FechaVencimiento",
        "dt" => "DT_RowClass",
        "formatter" => function ($d, $row) {
            if ($d == "" || $d == null) {
                return "";
            } else {
                $fechaVencimiento = new DateTime($d);
                $fechaActual = new DateTime();
                return $fechaVencimiento < $fechaActual ? 'table-danger producto-vencido' : '';
            }
        },
    ]
];

require_once __DIR__ . '/../config/database.php';
$sql_details = getDbConfigSSP();

// Obtener códigos desde la sesión
$codigos = [];
if (isset($_POST['codigos']) && is_array($_POST['codigos'])) {
    $codigos = $_POST['codigos'];
    $_SESSION['codigos_procesamiento'] = $codigos;
} elseif (isset($_SESSION['codigos_procesamiento'])) {
    $codigos = $_SESSION['codigos_procesamiento'];
}

if (empty($codigos)) {
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'estadisticas' => [
            'total' => 0,
            'encontrados' => 0,
            'no_encontrados' => 0,
            'vencidos' => 0,
            'vigentes' => 0
        ]
    ]);
    exit;
}

// Crear condición WHERE para filtrar por códigos
$codigosEscapados = array_map(function ($codigo) {
    return str_replace("'", "''", trim($codigo));
}, $codigos);

$codigosLista = "'" . implode("','", $codigosEscapados) . "'";
$where = "ArtCodigo IN ($codigosLista)";

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require "ssp.class.php";

$response = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $where);

// Calcular estadísticas correctas
$total_codigos = count($codigos);

// Para obtener el número real de códigos encontrados, necesitamos hacer una consulta directa
try {
    $dsn = "sqlsrv:server={$sql_details['host']};Database={$sql_details['db']}";
    $pdo = new PDO($dsn, $sql_details['user'], $sql_details['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Consulta para contar códigos realmente encontrados
    $count_query = "SELECT COUNT(DISTINCT ArtCodigo) as encontrados FROM vw_importaciones WHERE ArtCodigo IN ($codigosLista)";
    $count_stmt = $pdo->prepare($count_query);
    $count_stmt->execute();
    $encontrados_count = $count_stmt->fetchColumn();

    // Consulta para contar vencidos y vigentes
    $stats_query = "SELECT 
                        SUM(CASE WHEN FechaVencimiento IS NOT NULL AND FechaVencimiento < CAST(GETDATE() AS DATE) THEN 1 ELSE 0 END) as vencidos,
                        SUM(CASE WHEN FechaVencimiento IS NOT NULL AND FechaVencimiento >= CAST(GETDATE() AS DATE) THEN 1 ELSE 0 END) as vigentes
                    FROM vw_importaciones 
                    WHERE ArtCodigo IN ($codigosLista)";
    $stats_stmt = $pdo->prepare($stats_query);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch();

    $vencidos_count = (int) $stats['vencidos'];
    $vigentes_count = (int) $stats['vigentes'];
    $no_encontrados_count = $total_codigos - $encontrados_count;

} catch (Exception $e) {
    // En caso de error, usar valores por defecto
    $encontrados_count = count($response['data']);
    $no_encontrados_count = $total_codigos - $encontrados_count;
    $vencidos_count = 0;
    $vigentes_count = 0;
}

// Agregar estadísticas a la respuesta
$response['estadisticas'] = [
    'total' => $total_codigos,
    'encontrados' => $encontrados_count,
    'no_encontrados' => $no_encontrados_count,
    'vencidos' => $vencidos_count,
    'vigentes' => $vigentes_count
];

echo json_encode($response);
?>