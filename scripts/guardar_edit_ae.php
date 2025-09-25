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
$modalID = $_POST["modalID"];
$ArtID = $_POST["ArtID"];
$codigo = $_POST["codigo"];
$descripcion = $_POST["descripcion"];
$fabricante = $_POST["fabricante"];    
$lugarfab = $_POST["lugarfab"];    
$origen = $_POST["origen"];    
$nivelriesgo = $_POST["nivelriesgo"];    
$gmdn = $_POST["gmdn"];    
$etiqueta = $_POST["etiqueta"];    
$esterilidad = $_POST["esterilidad"];    
$nifu = $_POST["nifu"];     
$ean13un = $_POST["ean13un"];    
$ean14cj = $_POST["ean14cj"];    
$gtincj = $_POST["gtincj"];
$cambae = $_POST["cambae"];
$exoneracioncm = $_POST["exoneracioncm"];    
$pdimen = $_POST["pdimen"];     

$rs = $_POST["rs"];
$resolucion = $_POST["resolucion"];
$emision = $_POST["emision"];
$aprobacion = $_POST["aprobacion"];
$vencimiento = $_POST["vencimiento"];
$observaciones = $_POST["observaciones"];
$estadors = $_POST["estadors"];    
$usuariomod = $_POST["usuariomod"];    


$sql_update01 = 'UPDATE Sdt_RegistroSanitario_AE SET RegNumero_AE = :rs, RegResolucion_AE = :resolucion, RegFechaEmision_AE = :emision, '
		. 'RegFechaAprobacion_AE = :aprobacion, RegFechaVencimiento_AE = :vencimiento, RegEstado_AE = :estadors, '
		. 'RegObservacion_AE = :observaciones, RegUsuarioModificacion_AE = :usuariomod, RegFechaModificacion_AE = getdate() where RegID_AE = :modalID';
				
// Preparar la sentencia
$stmt = $conn->prepare($sql_update01);

// Vincular los parámetros
$stmt->bindParam(':rs', $rs); //NUMERO DE REGISTRO
$stmt->bindParam(':resolucion', $resolucion);
$stmt->bindParam(':emision', $emision);
$stmt->bindParam(':aprobacion', $aprobacion);
$stmt->bindParam(':vencimiento', $vencimiento);
$stmt->bindParam(':estadors', $estadors);
$stmt->bindParam(':observaciones', $observaciones);
$stmt->bindParam(':usuariomod', $usuariomod);
$stmt->bindParam(':modalID', $modalID);

// Verificar si la preparación de la sentencia fue exitosa
if ($stmt === false) {
	die("Error al preparar la consulta: " . $conn->error);
}

// Ejecutar la sentencia
if ($stmt->execute()) {
    // Éxito al guardar los cambios en registrosanitario
	

	$sql_update02 = 'UPDATE [dbo].[Sdt_Articulos_ae]
	   SET [ArtDescripcion_AE] = :descripcion
		  ,[ArtFabricante_AE] = :fabricante
		  ,[ArtLugarFabricacion_AE] = :lugarfab
		  ,[ArtPaisOrigen_AE] = :origen
		  ,[NivelRiesgoPeru_AE] = :nivelriesgo
		  ,[Codigo_GMDN_UMDNS] = :gmdn
		  ,[Etiqueta_AE] = :etiqueta
		  ,[EsEsteril_AE] = :esterilidad
		  ,[NumeroIFU_AE] = :nifu
		  ,[CodigoEAN_13] = :ean13un
		  ,[CodigoEAN_14] = :ean14cj
		  ,[CodigoGTIN] = :gtincj
		  ,[Cambios_AE] = :cambae
		  ,[ExoneracionCM_AE] = :exoneracioncm
		  ,[ProblemaDimensiones_AE] = :pdimen
		  ,[FechaModificacion_AE] = getdate()
		  ,[UsuarioModificacion_AE] = :usuariomod
	WHERE [ArtId_AE] = :ArtID';	
	 
	//echo($sql_update02);

	// Preparar la sentencia
	$stmt1 = $conn->prepare($sql_update02);

	// Vincular los parámetros
	$stmt1->bindParam(':descripcion', $descripcion);
	$stmt1->bindParam(':fabricante', $fabricante);
	$stmt1->bindParam(':origen', $origen);
	$stmt1->bindParam(':lugarfab', $lugarfab);
	$stmt1->bindParam(':nivelriesgo', $nivelriesgo);
	$stmt1->bindParam(':exoneracioncm', $exoneracioncm);
	$stmt1->bindParam(':ean13un', $ean13un);
	$stmt1->bindParam(':ean14cj', $ean14cj);
	$stmt1->bindParam(':gtincj', $gtincj);
	$stmt1->bindParam(':esterilidad', $esterilidad);
	$stmt1->bindParam(':gmdn', $gmdn);
	$stmt1->bindParam(':nifu', $nifu);
	$stmt1->bindParam(':cambae', $cambae);
	$stmt1->bindParam(':pdimen', $pdimen);
	$stmt1->bindParam(':etiqueta', $etiqueta);
	$stmt1->bindParam(':usuariomod', $usuariomod);
	$stmt1->bindParam(':ArtID', $ArtID);
		

	// Verificar si la preparación de la sentencia fue exitosa
	if ($stmt1 === false) {
		die("Error al preparar la consulta: " . $conn->error);
	}

	// Ejecutar la sentencia
	if ($stmt1->execute()) {
		// Éxito al guardar los cambios en articulos

		// Preparar la sentencia para insertar en Std_RSDoc
		$sql_insert02 = "INSERT INTO Std_RSDoc_AE (RegID, Descripcion, Ruta, FechaCarga, UsuarioCarga, Estado) VALUES (:RegID, :Descripcion, :Ruta, getdate(), :UsuarioCarga, 'ACTIVO')";
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
				$stmt2->bindParam(':RegID', $modalID);
				$stmt2->bindParam(':Descripcion', $nombreArchivo);  // Usar el nombre original del archivo
				$stmt2->bindParam(':Ruta', $ruta);
				$stmt2->bindParam(':UsuarioCarga', $usuariomod);
				$stmt2->execute();
			}
		}

		echo "success";
	} else {
		// Error al guardar los cambios
		echo "error" . $stmt->error;
	}

}

// Cerrar las sentencias
$stmt = null;
$stmt2 = null;

?>