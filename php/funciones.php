<?php
    //Code refactor by: Marco Santiago
    include_once('conn.php');


    function obtenerAcceso($username, $password)  {
        global $conn;
    
        $query="SELECT id FROM admin WHERE username = \"$username\" AND passcode = \"$password\"";
        $obtenerID=$conn->query ($query);

        return $obtenerID;

    
    }

    function consultarTotalVentas($fechaInicioBusqueda, $fechaFinBusqueda, $usuarioBusqueda) {
        global $conn;

        echo ($fechaInicioBusqueda.$fechaFinBusqueda.$usuarioBusqueda);

        #$query="SELECT productName AS 'nombre', priceEach AS 'precio', COUNT(productName) AS 'unidades' FROM orderdetails LEFT JOIN products ON orderdetails.productcode = products.productcode LEFT JOIN orders ON orders.ordernumber = orderdetails.ordernumber WHERE orders.orderdate >=  $fechaInicioBusqueda AND orders.orderdate <= $fechaFinBusqueda AND orders.customernumber = $usuarioBusqueda['id'] GROUP BY productName";
        $query="SELECT productName AS 'nombre', priceEach AS 'precio', COUNT(productName) AS 'unidades' FROM orderdetails LEFT JOIN products ON orderdetails.productcode = products.productcode LEFT JOIN orders ON orders.ordernumber = orderdetails.ordernumber ";

        $obtenerVentas=$conn->query($query);

        return $obtenerVentas;

}

function realizarConsulta($conn, $query){
    $query=$conn->query($query);

    return $query;
}

function consultaStock($productline)  {
    global $conn;

    $query="SELECT productName, quantityInStock FROM products WHERE productLine = $productline ORDER BY quantityInStock DESC";
    $query=$conn->query($query);

    return $query;
    

}

    function get_productos(){
        global $conn;

        $query="SELECT * from products";
        $query=$conn->query($query);

        return $query;
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


    function realizarConsultaUnValor($conn, $query){
        $selec=$conn->query($query);
        return $selec;
    }

    function devolverCustomers() {
	    global $conn;

		$query="SELECT customerNumber, customerName FROM customers";
        $query=$conn->query($query);
    
        return $query;
     }

     function devolverOrders($customer) {
         // Dev: Daniel González Carretero
    	 // Ref: La función devuelve todos los pedidos (y su información) realizados por un customer
         // Function: Devuelve un array con los pedidos, o NULL si ha habido algún error / no hay pedidos de ese customer

	global $conn;

	try {

		$obtenerPedidos = $conn->prepare("SELECT orders.orderNumber AS 'orderNumber', orders.orderDate AS 'orderDate', orders.status AS 'status', orderdetails.orderLineNumber AS 'orderListNumber', orderdetails.quantityOrdered AS 'quantityOrdered', orderdetails.priceEach AS 'priceEach', products.productName AS 'productName' FROM orders LEFT JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber LEFT JOIN products ON orderdetails.productCode = products.productCode WHERE customerNumber = :customer ORDER BY orders.orderNumber DESC, orderdetails.orderLineNumber");
		$obtenerPedidos->bindParam(":customer", $customer);
		$obtenerPedidos->execute();

		return $obtenerPedidos->fetchAll(PDO::FETCH_ASSOC);

	} catch (PDOException $ex) {
		echo "<strong>ERROR: </strong> ". $ex->getMessage();
		return null;
	}

      }
  function consultarTotalPagos($fechaInicioBusqueda, $fechaFinBusqueda, $usuarioBusqueda) {
    // Dev: Daniel González Carretero
    // Ref: La función consulta todos los pagos realizados entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
    // Function: Devuelve un array con los pagos realizados, o NULL si ha habido algún error / no hay pagos entre esas fechas
    
    global $conn;

    try {
        $obtenerVentas = $conn->prepare("SELECT paymentDate, amount FROM payments WHERE customerNumber = :usuario AND payments.paymentDate >= :fechaInicio AND payments.paymentDate <= :fechaFin");
        $obtenerVentas->bindParam(":fechaInicio", $fechaInicioBusqueda);
        $obtenerVentas->bindParam(":fechaFin", $fechaFinBusqueda);
        $obtenerVentas->bindParam(":usuario", $usuarioBusqueda['id']);
        $obtenerVentas->execute();

        return $obtenerVentas->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        return null;
    }
}

function comprar(){
                    // Si le damos al boton de comprar, comprobamos el checkNumber, reducimos el stock de los productos e incluimos la compra en las tablas correspondientes (arreglado 05/02/2021 Pedro Fernández)
                if (isset($_POST['comprar'])) {
                    
                    $exp = '/[A-Z]{2}[0-9]{5}/'; // Expresión regular para el checkNumber
                    $repetido = false; // Boolean para comprobar que no esta repetido
                    if (preg_match($exp, $_POST['checknumber'])) {
                        $consulta = realizarConsulta($conn, "SELECT checkNumber as CN from payments");
                        foreach ($consulta as $cons) {
                            if ($cons['CN'] == $_POST['checknumber']){
                                $repetido = true;
                            }
                        }
                        if ($repetido){
                            echo "El Check Number introducido esta repetido";
                        }
                        else{
                            
                            // Vamos a añadir el pago a la tabla payments
                            $numerocliente = $_SESSION['idUsuario'];
                            $cn = $_POST['checknumber'];
                            $fechahoy = getdate()['year']."-".getdate()['mon']."-".getdate()['mday'];
                            $numerocliente=$numerocliente['id'];
                            $sqlpago = "INSERT INTO payments values ('$numerocliente', '$cn', '$fechahoy', '$total')";
                            $conn->exec($sqlpago);
                            echo "Pago realizado con éxito<br>";
    
                            // Con este foreach actualizamos el stock
                            foreach ($_SESSION['SHOPPING_CART'] as $id1 => $cantidad1) {
                                $sql = "UPDATE products SET quantityInStock = (quantityInStock-$cantidad1) where productName = '$id1'";
                                $conn->exec($sql);
                            }
    
                            // Generamos el siguiente numero de orden
                            $orders = realizarConsulta($conn, "SELECT max(orderNumber) as MAX from orders");
                            
                            foreach ($orders as $o) {
                                $n_order = $o['MAX']+1;
                            }
    
                            // Vamos a incluir la compra en la tabla orders
                            $sql2 = "INSERT into orders values('$n_order', '$fechahoy', '$fechahoy', null, 'Pendiente pago', null, '$numerocliente')";
                            $conn->exec($sql2);
    
                            $cont = 1;
                            // Ahora incluiremos los datos de la compra en la tabla orderDetails

                            foreach ($_SESSION['SHOPPING_CART'] as $name => $cantidad2) {
    
                                // Aqui conseguimos el código de producto con el nombre
                                // Contador para el Line Number
                                $prod_code = realizarConsulta($conn, "SELECT productCode as CODE from products where productName = '$name'");
                                foreach ($prod_code as $p) {
                                    $pcode = $p['CODE'];          // Código del producto
                                }
    
                                // Aqui conseguimos el precio del producto
                                $prod_price = realizarConsulta($conn, "SELECT buyPrice as PRICE from products where productName = '$name'");
                                foreach ($prod_price as $pp) {
                                    $pprice = $pp['PRICE'];          // Precio del producto
                                }
    
                                
                                // Hacemos la inserción en la tabla orderdetails
                                $sql3 = "INSERT INTO orderdetails values ('$n_order', '$pcode', '$cantidad2', '$pprice', $cont)";
                                $conn->exec($sql3);
    
                                $cont = $cont+1;
                            }
    
                            echo "Compra realizada con éxito";

                            $_SESSION['SHOPPING_CART'] = array();       // Reiniciamos el carrito
                        }
                    }
                    else{
                        echo "El Check Number introducido es erróneo (Formato: AA99999)";
                    }
                    
                }
}

?>
