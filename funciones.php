<?php 

include_once 'conn.php';

function obtenerAcceso($username, $password)  {
	// Daniel González Carretero
	// La función comprueba si existe un usuario $username con una contraseña $password
	// Devuelve el ID del usuario, si el usuario y contraseña son correctos, NULL si no lo son.

	global $conexion;

	try {
		$obtenerID = $conexion->preprare("SELECT id FROM admin WHERE username = :username && passcode = :password");
		$obtenerID->bindParam(":username", $username);
		$obtenerID->bindParam(":password", $password);
		$obtenerID->execute(PDO::FETCH_ASSOC);

		return $obtenerID->fetch(PDO:);
	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
	}

}

?>
