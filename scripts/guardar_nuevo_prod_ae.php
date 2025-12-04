<?php
require_once __DIR__ . '/../config/database.php';

//Establecer la conexión
$conn = getDbConnection();

// Recoge los datos del formulario - Campos obligatorios
$codigo = $_POST["newcodigo"];
$descripcion = $_POST["newdescripcion"];
$origen = $_POST["neworigen"];
$fabricante = $_POST["newfabricante"];
$lugarfab = $_POST["newlugarfab"];
$nivelriesgo = $_POST["newnivelriesgo"];
$ucreacion = $_POST["newucreacion"];

// Campos opcionales
$exoneracioncm = !empty($_POST["newexoneracioncm"]) ? $_POST["newexoneracioncm"] : null;
$esterilidad = !empty($_POST["newesterilidad"]) ? $_POST["newesterilidad"] : null;
$ean14 = !empty($_POST["newean14"]) ? $_POST["newean14"] : null;
$ean13 = !empty($_POST["newean13"]) ? $_POST["newean13"] : null;
$gtin = !empty($_POST["newgtin"]) ? $_POST["newgtin"] : null;
$gmdn = !empty($_POST["newgmdn"]) ? $_POST["newgmdn"] : null;
$ifu = !empty($_POST["newifu"]) ? $_POST["newifu"] : null;
$problemadim = !empty($_POST["newproblemadim"]) ? $_POST["newproblemadim"] : null;
$cambios = !empty($_POST["newcambios"]) ? $_POST["newcambios"] : null;

$sql_insert01 = "INSERT INTO Sdt_Articulos_AE (
    ArtCodigo_AE, 
    ArtDescripcion_AE, 
    ArtPaisOrigen_AE, 
    ArtFabricante_AE, 
    ArtLugarFabricacion_AE, 
    NivelRiesgoPeru_AE,
    ExoneracionCM_AE,
    EsEsteril_AE,
    CodigoEAN_14,
    CodigoEAN_13,
    CodigoGTIN,
    Codigo_GMDN_UMDNS,
    NumeroIFU_AE,
    ProblemaDimensiones_AE,
    Cambios_AE,
    FechaCreacion_AE, 
    UsuarioCreacion_AE
) VALUES(
    :codigo, 
    :descripcion, 
    :origen, 
    :fabricante, 
    :lugarfab, 
    :nivelriesgo,
    :exoneracioncm,
    :esterilidad,
    :ean14,
    :ean13,
    :gtin,
    :gmdn,
    :ifu,
    :problemadim,
    :cambios,
    getdate(), 
    :ucreacion
)";

// Preparar la sentencia
$stmt = $conn->prepare($sql_insert01);

// Vincular los parámetros obligatorios
$stmt->bindParam(':codigo', $codigo);
$stmt->bindParam(':descripcion', $descripcion);
$stmt->bindParam(':origen', $origen);
$stmt->bindParam(':fabricante', $fabricante);
$stmt->bindParam(':lugarfab', $lugarfab);
$stmt->bindParam(':nivelriesgo', $nivelriesgo);
$stmt->bindParam(':ucreacion', $ucreacion);

// Vincular los parámetros opcionales
$stmt->bindParam(':exoneracioncm', $exoneracioncm);
$stmt->bindParam(':esterilidad', $esterilidad);
$stmt->bindParam(':ean14', $ean14);
$stmt->bindParam(':ean13', $ean13);
$stmt->bindParam(':gtin', $gtin);
$stmt->bindParam(':gmdn', $gmdn);
$stmt->bindParam(':ifu', $ifu);
$stmt->bindParam(':problemadim', $problemadim);
$stmt->bindParam(':cambios', $cambios);

// Verificar si la preparación de la sentencia fue exitosa
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Ejecutar la sentencia
if ($stmt->execute()) {
    // Éxito al guardar los cambios
    echo "success";
} else {
    // Error al guardar los cambios
    echo "error" . $stmt->error;
}

// Cerrar las sentencias
$stmt = null;
?>