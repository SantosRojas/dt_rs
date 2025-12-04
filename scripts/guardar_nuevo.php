<?php
require_once __DIR__ . '/../config/database.php';

//Establecer la conexión
$conn = getDbConnection();

// Recoge los datos del formulario
$ArtID = $_POST["newArtID"];
$codigo = $_POST["newcodigo"];
$rs = $_POST["newrs"];
$resolucion = $_POST["newresolucion"];
$emision = $_POST["newemision"];
$aprobacion = $_POST["newaprobacion"];
$vencimiento = $_POST["newvencimiento"];
$observaciones = $_POST["newobservaciones"];
$estadors = $_POST["newestadors"];
$ucreacion = $_POST["newucreacion"];

$sql_insert01 = "INSERT INTO Sdt_RegistroSanitario VALUES(:ArtID, :codigo, :rs, :resolucion, :emision, :aprobacion, :vencimiento, :estadors, :observaciones, getdate(), :ucreacion, null, null)";

// Preparar la sentencia
$stmt = $conn->prepare($sql_insert01);

// Vincular los parámetros
$stmt->bindParam(':ArtID', $ArtID);
$stmt->bindParam(':codigo', $codigo);
$stmt->bindParam(':rs', $rs);
$stmt->bindParam(':resolucion', $resolucion);
$stmt->bindParam(':emision', $emision);
$stmt->bindParam(':aprobacion', $aprobacion);
$stmt->bindParam(':vencimiento', $vencimiento);
$stmt->bindParam(':estadors', $estadors);
$stmt->bindParam(':observaciones', $observaciones);
$stmt->bindParam(':ucreacion', $ucreacion);

// Verificar si la preparación de la sentencia fue exitosa
if ($stmt === false) {
	die("Error al preparar la consulta: " . $conn->error);
}

// Ejecutar la sentencia
if ($stmt->execute()) {
	// Éxito al guardar los cambios
	// Obtener el ID del último registro insertado
	$lastInsertId = $conn->lastInsertId();

	// Preparar la sentencia para insertar en Std_RSDoc
	$sql_insert02 = "INSERT INTO Std_RSDoc (RegID, Descripcion, Ruta, FechaCarga, UsuarioCarga, Estado) VALUES (:RegID, :Descripcion, :Ruta, getdate(), :UsuarioCarga, 'ACTIVO')";
	$stmt2 = $conn->prepare($sql_insert02);

	// Manejar los archivos
	if (isset($_FILES['archivos'])) {
		$archivos = $_FILES['archivos'];
		for ($i = 0; $i < count($archivos['name']); $i++) {
			$nombreArchivo = $archivos['name'][$i];
			$tmpArchivo = $archivos['tmp_name'][$i];

			// Generar un nombre de archivo único
			$nombreArchivoUnico = uniqid() . '-' . $nombreArchivo;

			// Mover el archivo a la carpeta de destino
			$ruta = "../upload/rs/" . $nombreArchivoUnico;
			move_uploaded_file($tmpArchivo, $ruta);

			// Vincular los parámetros y ejecutar la sentencia
			$stmt2->bindParam(':RegID', $lastInsertId);
			$stmt2->bindParam(':Descripcion', $nombreArchivo);  // Usar el nombre original del archivo
			$stmt2->bindParam(':Ruta', $ruta);
			$stmt2->bindParam(':UsuarioCarga', $ucreacion);
			$stmt2->execute();
		}
	}

	echo "success";
} else {
	// Error al guardar los cambios
	echo "error" . $stmt->error;
}

// Cerrar las sentencias
$stmt = null;
$stmt2 = null;
?>