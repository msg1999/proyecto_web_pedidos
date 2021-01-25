<?php 

include_once 'conn.php';

function obtenerAcceso($username, $password)  {
	// Daniel González Carretero
	// La función comprueba si existe un usuario $username con una contraseña $password
	// Devuelve el ID del usuario, si el usuario y contraseña son correctos, NULL si no lo son.

	global $conexion;

	try {
		$obtenerID = $conexion->prepare("SELECT id FROM admin WHERE username = :username && passcode = :password");
		$obtenerID->bindParam(":username", $username);
		$obtenerID->bindParam(":password", $password);
		$obtenerID->execute();

		return $obtenerID->fetch(PDO::FETCH_ASSOC)["id"];
	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
	}

}

function consultarTotalVentas($fechaInicioBusqueda, $fechaFinBusqueda) {
	// Daniel González Carretero
	// La función consulta todas las ventas realizadas entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
	// Devuelve un array con las compras realizadas, o NULL si ha habido algún error / no hay ventas entre esas fechas
	
	global $conexion;

	try {
		$obtenerVentas = $conexion->prepare("SELECT productName AS 'nombre', priceEach AS, COUNT(productName) AS 'unidades' FROM orderdetails LEFT JOIN products ON orderdetails.productcode = products.productcode LEFT JOIN orders ON orders.ordernumber = orderdetails.ordernumber WHERE orders.orderdate >= :fechaInicio AND orders.orderdate <= :fechaFin GROUP BY productName");
		$obtenerVentas->bindParam(":fechaInicio", $fechaInicioBusqueda);
		$obtenerVentas->bindParam(":fechaFin", $fechaFinBusqueda);
		$obtenerVentas->execute();

		return $obtenerVentas->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		return null;
	}


}