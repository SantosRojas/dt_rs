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
$rs = $_POST["rs"];
$resolucion = $_POST["resolucion"];
$emision = $_POST["emision"];
$aprobacion = $_POST["aprobacion"];
$vencimiento = $_POST["vencimiento"];
$observaciones = $_POST["observaciones"];
$estadors = $_POST["estadors"];    
$usuariomod = $_POST["usuariomod"];    

$arttipo = $_POST["arttipo"];    
$origen = $_POST["origen"];    
$fabricante = $_POST["fabricante"];    
$lugarfab = $_POST["lugarfab"];    
$formafarma = $_POST["formafarma"];    
$concentracion = $_POST["concentracion"];    
$presentacion = $_POST["presentacion"];    
$nivelriesgo = $_POST["nivelriesgo"];    
$materialpcb = $_POST["materialpcb"];    
$comercializado = $_POST["comercializado"];    
$labelalemania = $_POST["labelalemania"];    
$exoneracioncm = $_POST["exoneracioncm"];    
$proyectoart = $_POST["proyectoart"];    
$barraun = $_POST["barraun"];    
$barracj = $_POST["barracj"];    
$ean13un = $_POST["ean13un"];    
$ean14cj = $_POST["ean14cj"];    
$gtinun = $_POST["gtinun"];    
$gtincj = $_POST["gtincj"];    
$gmdn = $_POST["gmdn"];    
$umdns = $_POST["umdns"];    
$proyecto = $_POST["proyecto"];    
$proveedor = $_POST["proveedor"];    
$proveedordes = $_POST["proveedordes"];    

$sql_update01 = 'UPDATE Sdt_RegistroSanitario SET RegNumero = :rs, RegResolucion = :resolucion, RegFechaEmision = :emision, '
		. 'RegFechaAprobacion = :aprobacion, RegFechaVencimiento = :vencimiento, RegEstadoVencimiento = :estadors, '
		. 'RegObservaciones = :observaciones, RegUsuarioModificacion = :usuariomod, RegFechaModificacion=getdate() where RegID= :modalID';
				
// Preparar la sentencia
$stmt = $conn->prepare($sql_update01);

// Vincular los parámetros
$stmt->bindParam(':rs', $rs);
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
	

	$sql_update02 = 'UPDATE [dbo].[Sdt_Articulos]
	   SET [ArtTipo] = :arttipo
		  ,[ArtDescripcion] = :descripcion
		  ,[ArtFabricante] = :fabricante
		  ,[ArtOrigen] = :origen
		  ,[ArtLugarFabricacion] = :lugarfab
		  ,[ArtFormaFarma] = :formafarma
		  ,[ArtConcentracion] = :concentracion
		  ,[ArtPresentacion] = :presentacion
		  ,[ArtNivelRiesgo] = :nivelriesgo
		  ,[ArtMaterialPCB] = :materialpcb
		  ,[ArtComercializado] = :comercializado
		  ,[ArtLabelAlemania] = :labelalemania
		  ,[ArtExoneracionCM] = :exoneracioncm
		  ,[ArtBarraUn] = :barraun
		  ,[ArtBarraCj] = :barracj
		  ,[ArtEAN13Un] = :ean13un
		  ,[ArtEAN14Cj] = :ean14cj
		  ,[ArtGTINUn] = :gtinun
		  ,[ArtGTINCj] = :gtincj
		  ,[ArtProyecto] = :proyectoart
		  ,[ArtProveedor] = :proveedor
		  ,[ArtProveedorDes] = :proveedordes
		  ,[ArtCodigoGMDN] = :gmdn
		  ,[ArtCodigoUMDNS] = :umdns
		  ,[ArtProyectoRAAE] = :proyecto
		  ,[ArtFechaModificacion] = getdate()
		  ,[ArtUsuarioModificacion] = :usuariomod
	WHERE [ArtID] = :ArtID';	
	 
	//echo($sql_update02);

	// Preparar la sentencia
	$stmt1 = $conn->prepare($sql_update02);

	// Vincular los parámetros
	$stmt1->bindParam(':arttipo', $arttipo);
	$stmt1->bindParam(':descripcion', $descripcion);
	$stmt1->bindParam(':fabricante', $fabricante);
	$stmt1->bindParam(':origen', $origen);
	$stmt1->bindParam(':lugarfab', $lugarfab);
	$stmt1->bindParam(':formafarma', $formafarma );
	$stmt1->bindParam(':concentracion', $concentracion);
	$stmt1->bindParam(':presentacion', $presentacion);
	$stmt1->bindParam(':nivelriesgo', $nivelriesgo);
	$stmt1->bindParam(':materialpcb', $materialpcb);
	$stmt1->bindParam(':comercializado', $comercializado);
	$stmt1->bindParam(':labelalemania', $labelalemania);
	$stmt1->bindParam(':exoneracioncm', $exoneracioncm);
	$stmt1->bindParam(':barraun', $barraun);
	$stmt1->bindParam(':barracj', $barracj);
	$stmt1->bindParam(':ean13un', $ean13un);
	$stmt1->bindParam(':ean14cj', $ean14cj);
	$stmt1->bindParam(':gtinun', $gtinun);
	$stmt1->bindParam(':gtincj', $gtincj);
	$stmt1->bindParam(':proyectoart', $proyectoart);
	$stmt1->bindParam(':proveedor', $proveedor);
	$stmt1->bindParam(':proveedordes', $proveedordes);
	$stmt1->bindParam(':gmdn', $gmdn);
	$stmt1->bindParam(':umdns', $umdns);
	$stmt1->bindParam(':proyecto', $proyecto);
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