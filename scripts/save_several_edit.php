<?php
require_once __DIR__ . '/../config/database.php';

try {
    $conn = getDbConnection();

    // Recoge los datos del formulario
    $idsSelected = $_POST['idsSelected'] ?? [];
    if (!is_array($idsSelected)) {
        $idsSelected = explode(',', $idsSelected);
    }

    $rs = $_POST["newRs"] ?? null;
    $resolucion = $_POST["newResolution"] ?? null;
    $emision = $_POST["newEmition"] ?? null;
    $aprobacion = $_POST["newAproval"] ?? null;
    $vencimiento = $_POST["newExpiredDate"] ?? null;
    $estadors = $_POST["newState"] ?? null;
    $observaciones = $_POST["newObservation"] ?? null;
    $etiqueta = $_POST["newEtiqueta"] ?? null;
    $usuariomod = $_POST["usuariomod"] ?? null;

    $conn->beginTransaction();

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
            $tmpArchivo = $archivos['tmp_name'][$i];

            if (!empty($tmpArchivo)) {
                $nombreArchivoUnico = uniqid() . '-' . $nombreArchivo;
                $ruta = "../upload/rs/" . $nombreArchivoUnico;

                if (move_uploaded_file($tmpArchivo, $ruta)) {
                    $archivosSubidos[] = [
                        'Descripcion' => $nombreArchivo,
                        'Ruta' => $ruta
                    ];
                }
            }
        }
    }

    // Recorremos todos los IDs
    foreach ($idsSelected as $modalID) {
        // Construir UPDATE dinámico solo con campos que tienen valores (PATCH)
        $setClauses = [];
        $params = [':modalID' => $modalID];

        if (!empty($rs)) {
            $setClauses[] = 'RegNumero_AE = :rs';
            $params[':rs'] = $rs;
        }
        if (!empty($resolucion)) {
            $setClauses[] = 'RegResolucion_AE = :resolucion';
            $params[':resolucion'] = $resolucion;
        }
        if (!empty($emision)) {
            $setClauses[] = 'RegFechaEmision_AE = :emision';
            $params[':emision'] = $emision;
        }
        if (!empty($aprobacion)) {
            $setClauses[] = 'RegFechaAprobacion_AE = :aprobacion';
            $params[':aprobacion'] = $aprobacion;
        }
        if (!empty($vencimiento)) {
            $setClauses[] = 'RegFechaVencimiento_AE = :vencimiento';
            $params[':vencimiento'] = $vencimiento;
        }
        if (!empty($estadors)) {
            $setClauses[] = 'RegEstado_AE = :estadors';
            $params[':estadors'] = $estadors;
        }
        if (!empty($observaciones)) {
            $setClauses[] = 'RegObservacion_AE = :observaciones';
            $params[':observaciones'] = $observaciones;
        }
        if (!empty($etiqueta)) {
            $setClauses[] = 'Etiqueta_AE = :etiqueta';
            $params[':etiqueta'] = $etiqueta;
        }

        // Solo ejecutar UPDATE si hay campos para actualizar
        if (!empty($setClauses)) {
            // Siempre agregar usuario y fecha de modificación
            $setClauses[] = 'RegUsuarioModificacion_AE = :usuariomod';
            $setClauses[] = 'RegFechaModificacion_AE = getdate()';
            $params[':usuariomod'] = $usuariomod;

            $sql_update01 = 'UPDATE Sdt_RegistroSanitario_AE SET ' . implode(', ', $setClauses) . ' WHERE RegID_AE = :modalID';
            $stmt = $conn->prepare($sql_update01);
            $stmt->execute($params);
        }

        // Insertar referencias de los archivos subidos
        foreach ($archivosSubidos as $archivo) {
            $stmt2->execute([
                ':RegID' => $modalID,
                ':Descripcion' => $archivo['Descripcion'],
                ':Ruta' => $archivo['Ruta'],
                ':UsuarioCarga' => $usuariomod
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