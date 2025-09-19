<?php
//require_once("cnx/cnx.php");

$serverName = "pe01-wsqlprd01.bbmag.bbraun.com";
$connectionOptions = array(
        "Database" => "DP_BBRAUN_SAP",
        "Uid" => "sa_bbmpe",
        "PWD" => "ItPeru22$#"
);

//Establecer la conexión
$conn = new PDO("sqlsrv:server=$serverName; Database = $connectionOptions[Database]", $connectionOptions['Uid'], $connectionOptions['PWD']);
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// Recoge los datos del formulario
$query_update1 = isset($_POST["query_update1"]) ? $_POST["query_update1"] : "";
$query_update2 = isset($_POST["query_update2"]) ? $_POST["query_update2"] : "";
$success = true;

// Verificar si las consultas están vacías
if ($query_update1 !== "" && $query_update1 !== "UPDATE Sdt_Articulos SET WHERE ArtCodigo = ''") {
    // Preparar la sentencia
    $stmt = $conn->prepare($query_update1);

    // Verificar si la preparación de la sentencia fue exitosa
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Ejecutar la sentencia
    if (!$stmt->execute()) {
        $success = false;
    }

    // Cerrar las sentencias
    $stmt = null;
}

if ($query_update2 !== "" && $query_update2 !== "UPDATE Sdt_RegistroSanitario SET WHERE ArtCodigo = ''") {
    // Preparar la sentencia
    $stmt = $conn->prepare($query_update2);

    // Verificar si la preparación de la sentencia fue exitosa
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Ejecutar la sentencia
    if (!$stmt->execute()) {
        $success = false;
    }

    // Cerrar las sentencias
    $stmt = null;
}

if ($success) {
    echo "success";
} else {
    echo "error";
}
?>