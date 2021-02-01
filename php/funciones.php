<?php
    //Code refactor by: Marco Santiago
    include_once('conn.php');

    function get_productos(){
        // Dev by: Marco Santiago.
	// Function: La función obtiene los nombres de todos los productos.
	// Return: array de productos.
        global $conn;

        try{
            $products = $conn->query("SELECT * from products")->fetchAll(PDO::FETCH_ASSOC);
            echo "Gen products succesfully<br>";
            
            return $products;
        }
        catch(Exception $e) {
            echo("Error obtener productos -->".$e->getMessage()."</br>");
        }
    }

    function get_stock($producto){
        // Dev by: Marco Santiago.
	// Function: La función obtiene el stock del producto seleccionado.
        // Return: array de productos.
        global $conn;

        try {
            $stock = $conn->query("SELECT quantityInStock from products where productName ='".$producto."'")->fetchAll(PDO::FETCH_ASSOC);

            return $stock;

        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }

    }

    function obtenerAcceso($username, $password)  {
        // Dev by: Daniel González Carretero
        // Function: La función comprueba si existe un usuario $username con una contraseña $password
        // Return: Devuelve el ID del usuario, si el usuario y contraseña son correctos, NULL si no lo son.
        global $conn;
    
        try {
            $obtenerID = $conn->prepare("SELECT id FROM admin WHERE username = :username AND passcode = :password");
            $obtenerID->bindParam(":username", $username);
            $obtenerID->bindParam(":password", $password);
            $obtenerID->execute();
    
            return $obtenerID->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "<strong>ERROR: </strong> ". $ex->getMessage();
        }
    
    }

    function consultaProductLine()  {
        // Dev by: Jorge Blazquez Alvarez
        // Function: La función consulta todas las lineas de producto existentes
        // Return: Devuelve las lineas de producto en un array
        global $conn;

        try {
            $obtenerProdLine = $conn->prepare("SELECT productLine FROM productLines");
            $obtenerProdLine->execute();
            return $obtenerProdLine->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "<strong>ERROR: </strong> ". $ex->getMessage();
        }
    }
    function consultaStock($productline)  {
        // Dev by: Jorge Blazquez Alvarez
        // Function: La función consulta todas las lineas de producto existentes
        // Return: Devuelve las lineas de producto en un array
        global $conn;

        try {
            $obtenerProd = $conn->prepare("SELECT productName, quantityInStock FROM products WHERE productLine = :productLine ORDER BY quantityInStock DESC");
            $obtenerProd->bindParam(":productLine", $productline);
            $obtenerProd->execute();
            return $obtenerProd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "<strong>ERROR: </strong> ". $ex->getMessage();
        }

    }
    function consultarTotalVentas($fechaInicioBusqueda, $fechaFinBusqueda, $usuarioBusqueda) {
        // Dev by: Daniel González Carretero
        // Function: La función consulta todas las ventas realizadas por un usuario entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
        // Return: Devuelve un array con las compras realizadas, o NULL si ha habido algún error / no hay ventas entre esas fechas
        global $conn;

        try {
            $obtenerVentas = $conn->prepare("SELECT productName AS 'nombre', priceEach AS 'precio', COUNT(productName) AS 'unidades' FROM orderdetails LEFT JOIN products ON orderdetails.productcode = products.productcode LEFT JOIN orders ON orders.ordernumber = orderdetails.ordernumber WHERE orders.orderdate >= :fechaInicio AND orders.orderdate <= :fechaFin AND orders.customernumber = :usuario GROUP BY productName");
            $obtenerVentas->bindParam(":fechaInicio", $fechaInicioBusqueda);
            $obtenerVentas->bindParam(":fechaFin", $fechaFinBusqueda);
            $obtenerVentas->bindParam(":usuario", $usuarioBusqueda['id']);
            $obtenerVentas->execute();

            return $obtenerVentas->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }
    function realizarConsulta($conn, $consulta){
        // Dev by: Pedro Fernandez
        // Ref: Marco Santiago
        // Function: La función consulta todas las ventas realizadas por un usuario entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
        // Return: Devuelve un array con las compras realizadas, o NULL si ha habido algún error / no hay ventas entre esas fechas
        $con = $conn->prepare($consulta);
        $con->execute();
        $select=$con->fetchAll(PDO::FETCH_ASSOC);
        return $select;
    }
    function realizarConsultaUnValor($conn, $consulta){
        // Dev: Pedro Fernandez
        // Ref: Marco Santiago
        // Function: Ejecuta un select y devuelve el resultado
        // Return: $selec, el valor de la consulta
        $con = $conn->prepare($consulta);
        $con->execute();
        $selec=$con->fetchColumn();
        return $selec;
    }

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
	function consultarTotalVentas($fechaInicioBusqueda, $fechaFinBusqueda, $usuarioBusqueda) {
	// Daniel González Carretero
	// La función consulta todas las ventas realizadas por un usuario entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
	// Devuelve un array con las compras realizadas, o NULL si ha habido algún error / no hay ventas entre esas fechas
	
	global $conn;

	try {
		$obtenerVentas = $conn->prepare("SELECT productName AS 'nombre', priceEach AS 'precio', COUNT(productName) AS 'unidades' FROM orderdetails LEFT JOIN products ON orderdetails.productcode = products.productcode LEFT JOIN orders ON orders.ordernumber = orderdetails.ordernumber WHERE orders.orderdate >= :fechaInicio AND orders.orderdate <= :fechaFin AND orders.customernumber = :usuario GROUP BY productName");
		$obtenerVentas->bindParam(":fechaInicio", $fechaInicioBusqueda);
		$obtenerVentas->bindParam(":fechaFin", $fechaFinBusqueda);
		$obtenerVentas->bindParam(":usuario", $usuarioBusqueda);
		$obtenerVentas->execute();

		return $obtenerVentas->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		return null;
	}
}
?>
