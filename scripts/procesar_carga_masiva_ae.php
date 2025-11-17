<?php
// Limpiar cualquier salida previa
ob_clean();

// Configurar manejo de errores
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$serverName = "pe01-wsqlprd01.bbmag.bbraun.com";
$connectionOptions = array(
    "Database" => "DP_BBRAUN_SAP",
    "Uid" => "sa_bbmpe",
    "PWD" => "ItPeru22$#"
);

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database={$connectionOptions['Database']}", $connectionOptions['Uid'], $connectionOptions['PWD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recibir datos
    $datosExcel = json_decode($_POST['datosExcel'], true);
    $usuario = $_POST['usuario'];

    $exitosos = 0;
    $errores = 0;
    $detalleErrores = [];

    // Preparar las sentencias SQL
    $sqlInsertArticulo = "INSERT INTO Sdt_Articulos_AE 
        (ArtCodigo_AE, ArtDescripcion_AE, ArtFabricante_AE, ArtLugarFabricacion_AE, ArtPaisOrigen_AE, 
        NivelRiesgoPeru_AE, Codigo_GMDN_UMDNS, EsEsteril_AE, NumeroIFU_AE, CodigoEAN_13, CodigoEAN_14, 
        CodigoGTIN, Cambios_AE, ExoneracionCM_AE, ProblemaDimensiones_AE, FechaCreacion_AE, UsuarioCreacion_AE) 
        OUTPUT INSERTED.ArtId_AE
        VALUES 
        (:codigo, :descripcion, :fabricante, :lugarfab, :origen, :nivelriesgo, :gmdn, :esteril, :ifu, 
        :ean13, :ean14, :gtin, :cambios, :exoneracion, :problemas, GETDATE(), :usuario)";

    $sqlInsertRS = "INSERT INTO Sdt_RegistroSanitario_AE 
        (ArtID_AE, ArtCodigo_AE, RegNumero_AE, RegResolucion_AE, RegFechaEmision_AE, RegFechaAprobacion_AE, 
        RegFechaVencimiento_AE, RegEstado_AE, Etiqueta_AE, RegObservacion_AE, RegFechaCreacion_AE, RegUsuarioCreacion_AE) 
        VALUES 
        (:artid, :codigo, :numero, :resolucion, :emision, :aprobacion, :vencimiento, :estado, 
        :etiqueta, :observacion, GETDATE(), :usuario)";

    $stmtArticulo = $conn->prepare($sqlInsertArticulo);
    $stmtRS = $conn->prepare($sqlInsertRS);

    // Saltar la primera fila (encabezados) y procesar el resto
    for ($i = 1; $i < count($datosExcel); $i++) {
        $fila = $datosExcel[$i];

        // Validar que la fila tenga datos
        if (empty($fila[0]) && empty($fila[1])) {
            continue; // Saltar filas vacías
        }

        try {
            $conn->beginTransaction();

            // 1. Insertar Artículo
            $stmtArticulo->execute([
                ':codigo' => $fila[0] ?? '',
                ':descripcion' => $fila[1] ?? '',
                ':fabricante' => $fila[2] ?? '',
                ':lugarfab' => $fila[3] ?? '',
                ':origen' => $fila[4] ?? '',
                ':nivelriesgo' => $fila[5] ?? '',
                ':gmdn' => $fila[6] ?? '',
                ':esteril' => $fila[7] ?? '',
                ':ifu' => $fila[8] ?? '',
                ':ean13' => $fila[9] ?? '',
                ':ean14' => $fila[10] ?? '',
                ':gtin' => $fila[11] ?? '',
                ':cambios' => $fila[12] ?? '',
                ':exoneracion' => $fila[13] ?? '',
                ':problemas' => $fila[14] ?? '',
                ':usuario' => $usuario
            ]);

            // 2. Obtener el ID del artículo insertado
            $result = $stmtArticulo->fetch(PDO::FETCH_ASSOC);
            $artId = $result['ArtId_AE'];

            // 3. Convertir fechas de Excel a formato SQL (si son números de Excel)
            $fechaEmision = convertirFechaExcel($fila[17] ?? null);
            $fechaAprobacion = convertirFechaExcel($fila[18] ?? null);
            $fechaVencimiento = convertirFechaExcel($fila[19] ?? null);

            // 4. Insertar Registro Sanitario usando el ArtId obtenido
            $stmtRS->execute([
                ':artid' => $artId,
                ':codigo' => $fila[0] ?? '',
                ':numero' => $fila[15] ?? '',
                ':resolucion' => $fila[16] ?? '',
                ':emision' => $fechaEmision,
                ':aprobacion' => $fechaAprobacion,
                ':vencimiento' => $fechaVencimiento,
                ':estado' => $fila[20] ?? 'VIGENTE',
                ':etiqueta' => $fila[21] ?? '',
                ':observacion' => $fila[22] ?? '',
                ':usuario' => $usuario
            ]);

            $conn->commit();
            $exitosos++;

        } catch (Exception $e) {
            $conn->rollBack();
            $errores++;
            $detalleErrores[] = "Fila " . ($i + 1) . " - Código: " . ($fila[0] ?? 'N/A') . " - Error: " . $e->getMessage();
        }
    }

    echo json_encode([
        'exitosos' => $exitosos,
        'errores' => $errores,
        'total' => $exitosos + $errores,
        'detalleErrores' => $detalleErrores
    ]);
    exit; // Asegurar que no haya salida adicional

} catch (PDOException $e) {
    echo json_encode([
        'exitosos' => 0,
        'errores' => 0,
        'total' => 0,
        'detalleErrores' => ['Error de conexión: ' . $e->getMessage()]
    ]);
    exit;
}

function convertirFechaExcel($fecha)
{
    if (empty($fecha)) {
        return null;
    }

    // Si es un número (fecha de Excel)
    if (is_numeric($fecha)) {
        // Excel cuenta días desde 1900-01-01 (con ajuste por bug de año bisiesto)
        $unix_date = ($fecha - 25569) * 86400;
        return date('Y-m-d', $unix_date);
    }

    // Si es texto, intentar convertir formato DD/MM/YYYY
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $matches)) {
        return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
    }

    // Si ya está en formato YYYY-MM-DD
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        return $fecha;
    }

    return null;
}
?>