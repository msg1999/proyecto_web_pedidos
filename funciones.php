<?php 

include_once 'conn.php';

function devolverCustomers() {
	// Daniel González Carretero
    // La función devuelve todos los Customers existentes en la tabla 'customers'
    // Devuelve un array con los customers, o NULL si ha habido algún error

	global $conn;

	try {
		$obtenerCustomers = $conn->prepare("SELECT customerNumber, customerName FROM customers");
		$obtenerCustomers->execute();

		return $obtenerCustomers->fetchAll(PDO::FETCH_ASSOC);

	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
		return null;
	}
}

function devolverOrders($customer) {
	// Daniel González Carretero
    // La función devuelve todos los pedidos (y su información) realizados por un customer
    // Devuelve un array con los pedidos, o NULL si ha habido algún error / no hay pedidos de ese customer

	global $conn;

	try {

		$obtenerPedidos = $conn->prepare("SELECT orders.orderNumber AS 'orderNumber', orders.orderDate AS 'orderDate', orders.status AS 'status', orderdetails.orderLineNumber AS 'orderListNumber', orderdetails.quantityOrdered AS 'quantityOrdered', orderdetails.priceEach AS 'priceEach', products.productName AS 'productName' FROM orders LEFT JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber LEFT JOIN products ON orderdetails.productCode = products.productCode WHERE customerNumber = :customer ORDER BY orderdetails.orderLineNumber");
		$obtenerPedidos->bindParam(":customer", $customer);
		$obtenerPedidos->execute();

		return $obtenerPedidos->fetchAll(PDO::FETCH_ASSOC);

	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
		return null;
	}

}

?>