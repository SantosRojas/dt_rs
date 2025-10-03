<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

// Verificar que se recibieron datos
if (!isset($_POST['datos']) || empty($_POST['datos'])) {
    die('No se recibieron datos para exportar');
}

$datos = json_decode($_POST['datos'], true);
$estadisticas = json_decode($_POST['estadisticas'], true);

if (!$datos) {
    die('Error al procesar los datos');
}

// Configurar headers para descarga de Excel
$filename = 'Resultados_Importacion_' . date('Y-m-d_H-i-s') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Crear el output
$output = fopen('php://output', 'w');

// BOM para UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Escribir información de resumen
fputcsv($output, ['RESUMEN DE IMPORTACIÓN - ' . date('Y-m-d H:i:s')], ';');
fputcsv($output, [''], ';');
fputcsv($output, ['Usuario:', $_SESSION['usuario']], ';');
fputcsv($output, ['Total procesados:', $estadisticas['total']], ';');
fputcsv($output, ['Encontrados:', $estadisticas['encontrados']], ';');
fputcsv($output, ['No encontrados:', $estadisticas['no_encontrados']], ';');
fputcsv($output, ['Errores:', $estadisticas['errores']], ';');
fputcsv($output, [''], ';');
fputcsv($output, ['RESULTADOS DETALLADOS'], ';');
fputcsv($output, [''], ';');

// Campos específicos que queremos exportar (sin duplicar el código que ya aparece como "Código Buscado")
$campos_especificos = ['ArtDescripcion', 'RegistroSanitario', 'FechaVencimiento', 'PaisFabricacion', 'FactoryLocation'];

// Headers de la tabla
$headers = ['Código Buscado', 'Estado'];
$headers = array_merge($headers, $campos_especificos);
$headers[] = 'Estado Producto';
fputcsv($output, $headers, ';');

// Datos
foreach ($datos as $fila) {
    $row = [$fila['codigo_buscado'], $fila['estado']];
    
    // Determinar estado del producto
    $estado_producto = 'N/A';
    if ($fila['estado'] === 'encontrado' && isset($fila['datos_vista']['FechaVencimiento'])) {
        $fecha_vencimiento = $fila['datos_vista']['FechaVencimiento'];
        if ($fecha_vencimiento) {
            try {
                $fecha = new DateTime($fecha_vencimiento);
                $hoy = new DateTime();
                $hoy->setTime(0, 0, 0);
                $fecha->setTime(0, 0, 0);
                $estado_producto = ($fecha < $hoy) ? 'VENCIDO' : 'VIGENTE';
            } catch (Exception $e) {
                $estado_producto = 'FECHA INVÁLIDA';
            }
        }
    }
    
    // Agregar campos específicos
    if ($fila['estado'] === 'encontrado' && isset($fila['datos_vista']) && is_array($fila['datos_vista'])) {
        foreach ($campos_especificos as $campo) {
            $valor = $fila['datos_vista'][$campo] ?: '';
            
            // Formatear fecha de vencimiento
            if ($campo === 'FechaVencimiento' && $valor) {
                try {
                    $fecha = new DateTime($valor);
                    $valor = $fecha->format('d/m/Y');
                    if ($estado_producto === 'VENCIDO') {
                        $valor .= ' (VENCIDO)';
                    }
                } catch (Exception $e) {
                    // Usar valor original si no se puede formatear
                }
            }
            
            $row[] = $valor;
        }
    } else {
        // Llenar con valores vacíos si no hay datos
        foreach ($campos_especificos as $campo) {
            $row[] = '';
        }
    }
    
    // Agregar estado del producto
    $row[] = $estado_producto;
    
    fputcsv($output, $row, ';');
}

fclose($output);
exit;
?>