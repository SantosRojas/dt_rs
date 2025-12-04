<?php
//require_once("cnx/cnx.php");

$serverName = "pe01-wsqlprd01.bbmag.bbraun.com";
$connectionOptions = array(
	"Database" => "DP_BBRAUN_SAP",
	"Uid" => "sa_bbmpe",
	"PWD" => "ItPeru22$#"
);

try {
	//Establecer la conexión
	$conn = new PDO("sqlsrv:server=$serverName; Database = $connectionOptions[Database]", $connectionOptions['Uid'], $connectionOptions['PWD']);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Recoge los datos del formulario
	$productos = json_decode($_POST["productos"], true);
	$rs = $_POST["newrs"];
	$resolucion = $_POST["newresolucion"];
	$emision = !empty($_POST["newemision"]) ? $_POST["newemision"] : null;
	$aprobacion = !empty($_POST["newaprobacion"]) ? $_POST["newaprobacion"] : null;
	$vencimiento = !empty($_POST["newvencimiento"]) ? $_POST["newvencimiento"] : null;
	$observaciones = $_POST["newobservaciones"];
	$estadors = $_POST["newestadors"];
	$etiqueta = $_POST["newetiqueta"] ?? null;
	$ucreacion = $_POST["newucreacion"];

	// Iniciar transacción
	$conn->beginTransaction();

	$sql_insert01 = "INSERT INTO Sdt_RegistroSanitario_AE 
		(ArtID_AE, ArtCodigo_AE, RegNumero_AE, RegResolucion_AE, RegFechaEmision_AE, RegFechaAprobacion_AE, 
		RegFechaVencimiento_AE, RegEstado_AE, RegObservacion_AE, RegFechaCreacion_AE, RegUsuarioCreacion_AE, 
		RegFechaModificacion_AE, RegUsuarioModificacion_AE, Etiqueta_AE, IsActive) 
		VALUES (:ArtID, :codigo, :rs, :resolucion, :emision, :aprobacion, :vencimiento, :estadors, 
		:observaciones, getdate(), :ucreacion, null, null, :etiqueta, 1)";

	$stmt = $conn->prepare($sql_insert01);

	$sql_insert02 = "INSERT INTO Std_RSDoc_AE (RegID, Descripcion, Ruta, FechaCarga, UsuarioCarga, Estado) VALUES (:RegID, :Descripcion, :Ruta, getdate(), :UsuarioCarga, 'ACTIVO')";
	$stmt2 = $conn->prepare($sql_insert02);

	$registrosCreados = 0;
	$primerRegistroId = null;

	// Insertar un registro por cada producto seleccionado
	foreach ($productos as $producto) {
		$ArtID = $producto['artId'];
		$codigo = $producto['codigo'];

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
		$stmt->bindParam(':etiqueta', $etiqueta);

		$stmt->execute();
		$registrosCreados++;

		// Guardar el primer ID para asociar los archivos
		if ($primerRegistroId === null) {
			$primerRegistroId = $conn->lastInsertId();
		}
	}

	// Manejar los archivos (asociarlos solo al primer registro)
	if ($primerRegistroId !== null && isset($_FILES['archivos'])) {
		$archivos = $_FILES['archivos'];
		for ($i = 0; $i < count($archivos['name']); $i++) {
			$nombreArchivo = $archivos['name'][$i];
			$tmpArchivo = $archivos['tmp_name'][$i];

			if (!empty($tmpArchivo)) {
				$nombreArchivoUnico = uniqid() . '-' . $nombreArchivo;
				$ruta = "../upload/rs/" . $nombreArchivoUnico;
				move_uploaded_file($tmpArchivo, $ruta);

				$stmt2->bindParam(':RegID', $primerRegistroId);
				$stmt2->bindParam(':Descripcion', $nombreArchivo);
				$stmt2->bindParam(':Ruta', $ruta);
				$stmt2->bindParam(':UsuarioCarga', $ucreacion);
				$stmt2->execute();
			}
		}
	}

	$conn->commit();
	echo "success";

} catch (PDOException $e) {
	if ($conn->inTransaction()) {
		$conn->rollBack();
	}
	echo "Error: " . $e->getMessage();
}

$stmt = null;
$stmt2 = null;
?>