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

    $rs            = $_POST["newRs"] ?? null;
    $resolucion    = $_POST["newResolution"] ?? null;
    $emision       = $_POST["newEmition"] ?? null;
    $aprobacion    = $_POST["newAproval"] ?? null;
    $vencimiento   = $_POST["newExpiredDate"] ?? null;
    $estadors      = $_POST["newState"] ?? null;
    $observaciones = $_POST["newObservation"] ?? null;
    $usuariomod    = $_POST["usuariomod"] ?? null;

    $conn->beginTransaction();

    // --- UPDATE de los registros seleccionados ---
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

    // --- INSERT de archivos ---
    $sql_insert02 = "INSERT INTO Std_RSDoc_AE 
        (RegID, Descripcion, Ruta, FechaCarga, UsuarioCarga, Estado) 
        VALUES (:RegID, :Descripcion, :Ruta, getdate(), :UsuarioCarga, 'ACTIVO')";
    $stmt2 = $conn->prepare($sql_insert02);

    // Subir los archivos una sola vez y guardar rutas
    $archivosSubidos = [];
    if (!empty($_FILES['archivos']['name'][0])) {
        $archivos = $_FILES['archivos'];
        for ($i = 0; $i < count($archivos['name']); $i++) {
            $nombreArchivo = $archivos['name'][$i];
            $tmpArchivo    = $archivos['tmp_name'][$i];

            if (!empty($tmpArchivo)) {
                $nombreArchivoUnico = uniqid() . '-' . $nombreArchivo;
                $ruta = "../upload/rs/" . $nombreArchivoUnico;

                if (move_uploaded_file($tmpArchivo, $ruta)) {
                    $archivosSubidos[] = [
                        'Descripcion' => $nombreArchivo,
                        'Ruta'        => $ruta
                    ];
                }
            }
        }
    }

    // Recorremos todos los IDs
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

        // Insertar referencias de los archivos subidos
        foreach ($archivosSubidos as $archivo) {
            $stmt2->execute([
                ':RegID'       => $modalID,
                ':Descripcion' => $archivo['Descripcion'],
                ':Ruta'        => $archivo['Ruta'],
                ':UsuarioCarga'=> $usuariomod
            ]);
        }
    }

    $conn->commit();
    echo "success";

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Error en la conexion o en la ejecucucion: " . $e->getMessage();
}
?>
