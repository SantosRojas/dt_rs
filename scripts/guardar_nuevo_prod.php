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
$codigo = $_POST["newcodigo"];
$descripcion = $_POST["newdescripcion"];
$tipo = $_POST["newtipo"];  
$ucreacion = $_POST["newucreacion"];

$sql_insert01 = "INSERT INTO Sdt_Articulos (ArtCodigo, ArtDescripcion, ArtTipo, ArtFechaCreacion, ArtUsuarioCreacion) VALUES(:codigo, :descripcion, :tipo, getdate(), :ucreacion)";
		
// Preparar la sentencia
$stmt = $conn->prepare($sql_insert01);

// Vincular los parámetros
$stmt->bindParam(':codigo', $codigo);
$stmt->bindParam(':descripcion', $descripcion);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':ucreacion', $ucreacion);

// Verificar si la preparación de la sentencia fue exitosa
if ($stmt === false) {
	die("Error al preparar la consulta: " . $conn->error);
}

// Ejecutar la sentencia
if ($stmt->execute()) {
    // Éxito al guardar los cambios
    // Obtener el ID del último registro insertado
     echo "success";
} else {
    // Error al guardar los cambios
    echo "error" . $stmt->error;
}

// Cerrar las sentencias
$stmt = null;
?>