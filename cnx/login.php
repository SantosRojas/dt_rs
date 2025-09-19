<?php
    $serverName = "pe01-wsqlprd01.bbmag.bbraun.com";
    $connectionOptions = array(
        "Database" => "DP_BBRAUN_SAP",
        "Uid" => "sa_bbmpe",
        "PWD" => "ItPeru22$#"
    );

    //Establecer la conexión
    $conn = new PDO("sqlsrv:server=$serverName; Database = $connectionOptions[Database]", $connectionOptions['Uid'], $connectionOptions['PWD']);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recibir los datos del formulario de login
    $username = $_POST['usuario'];
    $password = $_POST['clave'];

    //Crear la consulta SQL
    $sql = "SELECT * FROM Sdt_Usuario WHERE usuario = :username AND clave = :password and estado=1";

    //Preparar la declaración
    $stmt = $conn->prepare($sql);

    //Vincular los parámetros
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    //Ejecutar la consulta
    $stmt->execute();

    //Obtener los resultados
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Verificar si el usuario existe
    if(count($results) > 0){
        // Iniciar sesión
        session_start();
        $row = $results[0];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['nombres'] = $row['nombres'];
        $_SESSION['apellidos'] = $row['apellidos'];
        $_SESSION['cargo'] = $row['cargo'];
        $_SESSION['area'] = $row['area'];
		$_SESSION['nivel'] = $row['nivel'];

        //Redirigir a home.php
        header('Location: ../home.php');
        exit;
    } else {
        // Mostrar mensaje de error
        echo "Usuario o contraseña incorrectos";
        // Redirigir a index.php
        header('Location: ../index.php');
        exit;		
    }
}
?>