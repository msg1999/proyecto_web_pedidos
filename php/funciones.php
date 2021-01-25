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

?>
