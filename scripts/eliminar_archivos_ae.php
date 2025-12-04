<?php
require_once __DIR__ . '/../config/database.php';

//Establecer la conexión
$conn = getDbConnection();

// Recoge los datos del formulario
$rutadel = $_POST["rutadel"];

$sql_delete01 = 'DELETE Std_RSDoc_AE where Ruta= :rutadel';

// Preparar la sentencia
$stmt = $conn->prepare($sql_delete01);

// Vincular los parámetros
$stmt->bindParam(':rutadel', $rutadel);

// Verificar si la preparación de la sentencia fue exitosa
if ($stmt === false) {
	die("Error al preparar la consulta: " . $conn->error);
}

// Ejecutar la sentencia
if ($stmt->execute()) {
	// Éxito al procesar los cambios

	$old_directory = $rutadel;
	$new_directory = str_replace('upload/rs/', 'upload/rs_trash/', $old_directory);

	if (rename($old_directory, $new_directory)) {
		echo "success";
	} else {
		echo "Hubo un error al mover el archivo.";
	}

} else {
	// Error al guardar los cambios
	echo "error" . $stmt->error;
}

// Cerrar las sentencias
$stmt = null;
$stmt2 = null;

?>