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

    $regId = $_POST['regId'];
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';

    if (empty($regId)) {
        echo "Error: ID de registro no proporcionado";
        exit;
    }

    if (empty($usuario)) {
        $usuario = 'Sistema'; // Usuario por defecto si no se proporciona
    }

    // Eliminación lógica: cambiar IsActive a 0 y registrar fecha/usuario de modificación
    $sql = "UPDATE Sdt_RegistroSanitario_AE 
            SET IsActive = 0, 
                RegFechaModificacion_AE = GETDATE(), 
                RegUsuarioModificacion_AE = :usuario 
            WHERE RegID_AE = :regId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':regId', $regId);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "success";
    } else {
        echo "Error: No se encontró el registro o ya fue eliminado";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$stmt = null;
$conn = null;
?>