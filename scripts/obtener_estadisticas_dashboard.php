<?php
// Script para obtener estadísticas del dashboard
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

try {
    // Conexión a la base de datos
    $conn = getDbConnection();

    $response = [
        'rs_hc' => [],
        'rs_ae' => [],
        'totales' => []
    ];

    // Obtener estadísticas de RS HC (vw_rs) - Solo registros sanitarios únicos
    $sql_hc = "SELECT RegEstadoVencimiento, COUNT(DISTINCT RegNumero) as total 
               FROM vw_rs 
               GROUP BY RegEstadoVencimiento
               ORDER BY RegEstadoVencimiento";

    $stmt_hc = $conn->query($sql_hc);
    $rs_hc_data = $stmt_hc->fetchAll(PDO::FETCH_ASSOC);

    $total_hc = 0;
    foreach ($rs_hc_data as $row) {
        $estado = $row['RegEstadoVencimiento'] ?? 'SIN ESTADO';
        $total = (int) $row['total'];
        $response['rs_hc'][$estado] = $total;
        $total_hc += $total;
    }
    $response['totales']['rs_hc'] = $total_hc;

    // Obtener estadísticas de RS AE (vw_rs_ae) - Solo registros sanitarios únicos
    $sql_ae = "SELECT RegEstado_AE, COUNT(DISTINCT RegNumero_AE) as total 
               FROM vw_rs_ae 
               GROUP BY RegEstado_AE
               ORDER BY RegEstado_AE";

    $stmt_ae = $conn->query($sql_ae);
    $rs_ae_data = $stmt_ae->fetchAll(PDO::FETCH_ASSOC);

    $total_ae = 0;
    foreach ($rs_ae_data as $row) {
        $estado = $row['RegEstado_AE'] ?? 'SIN ESTADO';
        $total = (int) $row['total'];
        $response['rs_ae'][$estado] = $total;
        $total_ae += $total;
    }
    $response['totales']['rs_ae'] = $total_ae;

    // Total general
    $response['totales']['general'] = $total_hc + $total_ae;

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}
?>