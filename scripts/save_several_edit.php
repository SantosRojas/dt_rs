<?php
$serverName = "pe01-wsqlprd01.bbmag.bbraun.com";
$connectionOptions = array(
    "Database" => "DP_BBRAUN_SAP",
    "Uid" => "sa_bbmpe",
    "PWD" => "ItPeru22$#"
);

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database={$connectionOptions['Database']}", $connectionOptions['Uid'], $connectionOptions['PWD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recoge los datos del formulario
    $idsSelected = $_POST['idsSelected'] ?? [];
    if (!is_array($idsSelected)) {
        $idsSelected = explode(',', $idsSelected);
    }

    $rs           = $_POST["newRs"] ?? null;
    $resolucion   = $_POST["newResolution"] ?? null;
    $emision      = $_POST["newEmition"] ?? null;
    $aprobacion   = $_POST["newAproval"] ?? null;
    $vencimiento  = $_POST["newExpiredData"] ?? null;
    $estadors     = $_POST["newState"] ?? null;
    $observaciones= $_POST["newObservation"] ?? null;
    $usuariomod   = $_POST["usuariomod"] ?? null;

    $conn->beginTransaction();

    // Update
    $sql_update01 = 'UPDATE Sdt_RegistroSanitario_AE SET 
        RegNumero_AE = :rs, 
        RegResolucion_AE = :resolucion, 
        RegFechaEmision_AE = :emision, 
        RegFechaAprobacion_AE = :aprobacion, 
        RegFechaVencimiento_AE = :vencimiento, 
        RegEstado_AE = :estadors, 
        RegObservacion_AE = :observaciones, 
        RegUsuarioModificacion_AE = :usuariomod, 
        RegFechaModificacion_AE = getdate() 
        WHERE RegID_AE = :modalID';

    $stmt = $conn->prepare($sql_update01);

    // Insert archivos
    $sql_insert02 = "INSERT INTO Std_RSDoc_AE (RegID, Descripcion, Ruta, FechaCarga, UsuarioCarga, Estado) 
                     VALUES (:RegID, :Descripcion, :Ruta, getdate(), :UsuarioCarga, 'ACTIVO')";
    $stmt2 = $conn->prepare($sql_insert02);

    foreach ($idsSelected as $modalID) {
        // Ejecutar update
        $stmt->execute([
            ':rs'            => $rs,
            ':resolucion'    => $resolucion,
            ':emision'       => $emision,
            ':aprobacion'    => $aprobacion,
            ':vencimiento'   => $vencimiento,
            ':estadors'      => $estadors,
            ':observaciones' => $observaciones,
            ':usuariomod'    => $usuariomod,
            ':modalID'       => $modalID
        ]);

        // Insertar archivos si existen
        if (!empty($_FILES['archivos']['name'][0])) {
            $archivos = $_FILES['archivos'];
            for ($i = 0; $i < count($archivos['name']); $i++) {
                $nombreArchivo = $archivos['name'][$i];
                $tmpArchivo    = $archivos['tmp_name'][$i];
                $nombreArchivoUnico = uniqid() . '-' . $nombreArchivo;
                $ruta = "../upload/rs/" . $nombreArchivoUnico;

                if (move_uploaded_file($tmpArchivo, $ruta)) {
                    $stmt2->execute([
                        ':RegID'       => $modalID,
                        ':Descripcion' => $nombreArchivo,
                        ':Ruta'        => $ruta,
                        ':UsuarioCarga'=> $usuariomod
                    ]);
                }
            }
        }
    }

    $conn->commit();
    echo "Actualizaciￃﾳn completada correctamente.";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Error en la conexiￃﾳn o ejecuciￃﾳn: " . $e->getMessage();
}
?>
