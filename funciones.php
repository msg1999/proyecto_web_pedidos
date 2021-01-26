<?php 

include_once 'conn.php';

function consultarTotalVentas($fechaInicioBusqueda, $fechaFinBusqueda, $usuarioBusqueda) {
	// Daniel González Carretero
	// La función consulta todas las ventas realizadas por un usuario entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
	// Devuelve un array con las compras realizadas, o NULL si ha habido algún error / no hay ventas entre esas fechas
	
	global $conexion;

	try {
		$obtenerVentas = $conexion->prepare("SELECT productName AS 'nombre', priceEach AS 'precio', COUNT(productName) AS 'unidades' FROM orderdetails LEFT JOIN products ON orderdetails.productcode = products.productcode LEFT JOIN orders ON orders.ordernumber = orderdetails.ordernumber WHERE orders.orderdate >= :fechaInicio AND orders.orderdate <= :fechaFin AND orders.customernumber = :usuario GROUP BY productName");
		$obtenerVentas->bindParam(":fechaInicio", $fechaInicioBusqueda);
		$obtenerVentas->bindParam(":fechaFin", $fechaFinBusqueda);
		$obtenerVentas->bindParam(":usuario", $usuarioBusqueda);
		$obtenerVentas->execute();

		return $obtenerVentas->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		return null;
	}


}
