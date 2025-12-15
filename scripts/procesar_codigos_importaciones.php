<?php
session_start();

header('Content-Type: application/json');

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autorizado']);
    exit;
}

// Configuración de la base de datos
require_once __DIR__ . '/../config/database.php';
$sql_details = getDbConfigSSP();

// Obtener códigos desde POST
if (!isset($_POST['codigos']) || !is_array($_POST['codigos']) || empty($_POST['codigos'])) {
    echo json_encode(['success' => false, 'error' => 'No se recibieron códigos para procesar']);
    exit;
}

$codigos = $_POST['codigos'];
$_SESSION['codigos_procesamiento'] = $codigos;

try {
    // Conexión a la base de datos
    $dsn = "sqlsrv:server={$sql_details['host']};Database={$sql_details['db']}";
    $pdo = new PDO($dsn, $sql_details['user'], $sql_details['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Limpiar códigos (trim), filtrar vacíos y eliminar duplicados
    $codigosLimpios = array_values(array_unique(array_filter(array_map('trim', $codigos), function ($c) {
        return $c !== '';
    })));

    $codigosEscapados = array_map(function ($codigo) {
        return str_replace("'", "''", $codigo);
    }, $codigosLimpios);

    $codigosLista = "'" . implode("','", $codigosEscapados) . "'";

    // Consulta para obtener todos los datos
    $query = "SELECT 
                ArtCodigo,
                ArtDescripcion,
                RegistroSanitario,
                Resolucion,
                FechaEmision,
                FechaAprobacion,
                FechaVencimiento,
                Estado,
                Fabricante,
                PaisOrigen,
                FactoryLocation
              FROM vw_importaciones 
              WHERE ArtCodigo IN ($codigosLista)
              ORDER BY ArtCodigo";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll();

    // Obtener lista de códigos encontrados
    $codigosEncontrados = array_column($resultados, 'ArtCodigo');

    // Calcular códigos no encontrados
    $codigosNoEncontrados = array_values(array_diff($codigosLimpios, $codigosEncontrados));

    // Contar vencidos y vigentes
    $vencidos = 0;
    $vigentes = 0;
    $fechaActual = new DateTime();

    // Formatear datos para la respuesta
    $data = [];
    foreach ($resultados as $row) {
        $fechaEmision = '-';
        $fechaAprobacion = '-';
        $fechaVencimiento = '-';
        $rowClass = '';

        if (!empty($row['FechaEmision'])) {
            try {
                $fecha = new DateTime($row['FechaEmision']);
                $fechaEmision = $fecha->format('d/m/Y');
            } catch (Exception $e) {
                $fechaEmision = $row['FechaEmision'];
            }
        }

        if (!empty($row['FechaAprobacion'])) {
            try {
                $fecha = new DateTime($row['FechaAprobacion']);
                $fechaAprobacion = $fecha->format('d/m/Y');
            } catch (Exception $e) {
                $fechaAprobacion = $row['FechaAprobacion'];
            }
        }

        if (!empty($row['FechaVencimiento'])) {
            try {
                $fecha = new DateTime($row['FechaVencimiento']);
                $fechaVencimiento = $fecha->format('d/m/Y');

                // Verificar si está vencido
                if ($fecha < $fechaActual) {
                    $rowClass = 'table-danger producto-vencido';
                    $fechaVencimiento .= ' <i class="fas fa-exclamation-triangle text-danger" title="Producto Vencido"></i>';
                    $vencidos++;
                } else {
                    $vigentes++;
                }
            } catch (Exception $e) {
                $fechaVencimiento = $row['FechaVencimiento'];
            }
        }

        // Formatear estado
        $estado = $row['Estado'] ?: '';
        $estadoFormateado = '<span class="badge badge-secondary">N/A</span>';
        if (!empty($estado)) {
            switch (strtoupper(trim($estado))) {
                case 'VIGENTE':
                    $estadoFormateado = '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>VIGENTE</span>';
                    break;
                case 'VENCIDO':
                    $estadoFormateado = '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>VENCIDO</span>';
                    break;
                case 'SUSPENDIDO':
                    $estadoFormateado = '<span class="badge badge-warning"><i class="fas fa-pause-circle mr-1"></i>SUSPENDIDO</span>';
                    break;
                default:
                    $estadoFormateado = '<span class="badge badge-info">' . htmlspecialchars($estado) . '</span>';
            }
        }

        $data[] = [
            'DT_RowId' => 'ID_' . $row['ArtCodigo'],
            'DT_RowClass' => $rowClass,
            'codigo_buscado' => '<strong>' . htmlspecialchars($row['ArtCodigo']) . '</strong>',
            'ArtDescripcion' => $row['ArtDescripcion'] ?: '',
            'RegistroSanitario' => $row['RegistroSanitario'] ?: '',
            'Resolucion' => $row['Resolucion'] ?: '',
            'FechaEmision' => $fechaEmision,
            'FechaAprobacion' => $fechaAprobacion,
            'FechaVencimiento' => $fechaVencimiento,
            'Estado' => $estadoFormateado,
            'Fabricante' => $row['Fabricante'] ?: '',
            'PaisOrigen' => $row['PaisOrigen'] ?: '',
            'FactoryLocation' => $row['FactoryLocation'] ?: ''
        ];
    }

    // Estadísticas
    $totalCodigos = count($codigosLimpios);
    $totalEncontrados = count($codigosEncontrados);
    $totalNoEncontrados = count($codigosNoEncontrados);

    echo json_encode([
        'success' => true,
        'data' => $data,
        'estadisticas' => [
            'total' => $totalCodigos,
            'encontrados' => $totalEncontrados,
            'no_encontrados' => $totalNoEncontrados,
            'vencidos' => $vencidos,
            'vigentes' => $vigentes
        ],
        'codigos_encontrados' => $codigosEncontrados,
        'codigos_no_encontrados' => $codigosNoEncontrados
    ]);

} catch (Exception $e) {
    error_log("Error en procesar_codigos_importaciones.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar los códigos: ' . $e->getMessage()
    ]);
}
?>