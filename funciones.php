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



function consultaProductLine()  {
	// Jorge Blazquez Alvarez
	// La función consulta todas las lineas de producto existentes
	// Devuelve las lineas de producto en un array

	global $conexion;

	try {
		$obtenerProdLine = $conexion->prepare("SELECT productLine FROM productLines");
		$obtenerProdLine->execute();
		return $obtenerProdLine->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
	}
}
function consultaStock($productline)  {
	// Jorge Blazquez Alvarez
	// La función consulta todas las lineas de producto existentes
	// Devuelve las lineas de producto en un array
	global $conexion;

	try {
		$obtenerProd = $conexion->prepare("SELECT productName, quantityInStock FROM products WHERE productLine = :productLine ORDER BY quantityInStock DESC");
		$obtenerProd->bindParam(":productLine", $productline);
		$obtenerProd->execute();
		return $obtenerProd->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
	}

}
?>
