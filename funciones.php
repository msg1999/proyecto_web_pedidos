<?php 

include_once 'conn.php';

function obtenerAcceso($username, $password)  {
	// Daniel González Carretero
	// La función comprueba si existe un usuario $username con una contraseña $password
	// Devuelve el ID del usuario, si el usuario y contraseña son correctos, NULL si no lo son.

	global $conexion;

	try {
		$obtenerID = $conexion->prepare("SELECT id FROM admin WHERE username = :username AND passcode = :password");
		$obtenerID->bindParam(":username", $username);
		$obtenerID->bindParam(":password", $password);
		$obtenerID->execute();

		return $obtenerID->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
	}

}

?>
