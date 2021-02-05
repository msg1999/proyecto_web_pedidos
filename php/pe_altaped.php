<!-- PEDRO FERNANDEZ GARCIA -->

<!-- Fragmento de PHP de sesión -->
<?php
    session_start();
    if (!isset($_SESSION["idUsuario"])) {
      exit("No estas logeado");
    }

    // Si el carrito no ha sido inicializando, lo creamos
    if (!isset($_SESSION['SHOPPING_CART']))
        $_SESSION['SHOPPING_CART'] = array();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title> Alta Pedidos </title>
    </head>

    <body>
        <h1> Alta de pedidos </h1>
        <div>
            <form name="compra" method="post" action="pe_altaped.php">
                <label>Selecciona el producto</label>
                <select name="producto">
                    <option></option>

                    <!-- Fragmento de PHP con un select para sacar los productos a un desplegable -->
                    <?php
                        include "./conn.php";
                        include "./funciones.php";
                        
                        
                        // Si le damos al botón de Volver al Menú, nos devuleve al menñu principal (arreglado 05/02/2021 Pedro Fernández)
                        if (isset($_POST['index'])) {
                            header("location: ../index.php");
                        }

                        $productos = realizarConsulta($conn, "SELECT productName from products where quantityInStock > 0");

                        foreach($productos as $prods){
                            echo "<option value='".$prods['productName']."'>".$prods['productName']."</option>";
                        }

                    ?>
                </select><br>
                Cantidad a comprar<input type="text" name="cantidad">
                <br>
                <button type="submit" name="anadir" value="anadir">Añadir Artículo</button>
                <button type='submit' name='borrar' value='borrar'>Borrar Carrito</button>
                <button type="submit" name="index" value="index">Volver al Menú</button><br> <!-- (arreglado 05/02/2021 Pedro Fernández) -->
            

            <?php   
                if (isset($_POST['anadir'])) {          
                    //Comprobamos que hay stock suficiente para añadir al carrito
                    $nombrep = $_POST['producto'];
                    $cantidadp = realizarConsultaUnValor($conn, "SELECT quantityInStock AS CANTIDAD FROM products WHERE productName = '$nombrep'");

                    if (isset($_POST['anadir'])) {          
                        //Comprobamos que hay stock suficiente para añadir al carrito
                        $nombrep = $_POST['producto'];
                        $cantidadp = realizarConsultaUnValor($conn, "SELECT quantityInStock AS CANTIDAD FROM products WHERE productName = '$nombrep'");
    
                        //En caso de que haya suficiente cantidad, añadimos el producto al carrito
                        if ($cantidadp > $_POST['cantidad']){
                            if (!isset($_SESSION['SHOPPING_CART'][$_POST['producto']])){    // Si el producto no esta en el carrito, lo añadimos y le asignamos la cantidad que introduca el usuario (arreglado 05/02/2021 Pedro Fernández)
                                $_SESSION['SHOPPING_CART'][$_POST['producto']] = $_POST['cantidad'];
                                echo "Producto añadido al carrito";
                            }
                            else{   // Si el producto existe en el carrito, le sumamos la cantidad que el usuario quiera a la cantidad que ya está guardada (arreglado 05/02/2021 Pedro Fernández)
                                $_SESSION['SHOPPING_CART'][$_POST['producto']] = $_POST['cantidad'] + $_SESSION['SHOPPING_CART'][$_POST['producto']];
                                echo "Producto añadido al carrito";
                            }
                        } 
                        else{
                           echo "No hay suficiente stock";
                        }
                    }
                }

                // Si le damos al boton de borrar, reinicia el array de la sesión y se vacía el carrito (arreglado 05/02/2021 Pedro Fernández)
                if (isset($_POST['borrar'])) {
                    $_SESSION['SHOPPING_CART'] = array();
                }

                //Tabla del carrito
                echo "<br><br><table border='1'>";
                echo "<thead>
                <tr>
                    <th colspan='2'>CARRITO DE COMPRA</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Nombre del producto</th>
                    <th>Cantidad</th>
                </tr>";
                foreach ($_SESSION['SHOPPING_CART'] as $id => $cantidad) {
                    $carr = realizarConsultaUnValor($conn, "SELECT productName FROM products where productName = '$id'");
                    echo   "<tr>
                                <td>".$carr."</td>
                                <td>".$cantidad."</td>
                            </tr>";
                }
                echo "</tbody></table>";
                echo "<br>";

                // Vamos a calcular el total de la compra
                $total = 0;
                foreach ($_SESSION['SHOPPING_CART'] as $n1 => $c1) {
                    // Consultamos el precio del producto
                    $prod_price = realizarConsulta($conn, "SELECT buyPrice as PRICE from products where productName = '$n1'");
                    foreach ($prod_price as $pp) {
                        $pprice = $pp['PRICE'];          // Precio del producto
                    }
                    $total = $total + ($pprice * $c1);
                }

                echo "Total: $total €<br><br>";
            ?>
            
            Introduce Check Number <input type="text" name="checknumber"><br><br>

            <button type="submit" name="comprar" value="comprar">Comprar artículos</button>
            </form>

            <?php
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
            ?>
        </div>
    </body>
</html>