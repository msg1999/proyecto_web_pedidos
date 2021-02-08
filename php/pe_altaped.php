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
                        include "conn.php";
                        include "funciones.php";
                        
                        
                        // Si le damos al botón de Volver al Menú, nos devuleve al menñu principal (arreglado 05/02/2021 Pedro Fernández)
                        if (isset($_POST['index'])) {
                            header("location: ../index.php");
                        }
                        $query="SELECT productName from products where quantityInStock > 0";
                        $productos = $conn->query ($query);
                        
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
                        $carr=$carr->fetch_array();
                            echo   "<tr>
                                <td>".$carr[0]."</td>
                                <td>".$cantidad."</td>
                            </tr>";}
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
            <?php
            if (isset($_POST['comprar'])) {
                $nuevaURL="pagos.php/?total=".$total."&variable2=valor2";
                echo "<script>window.location.replace(\"pagos.php/?total=".$total."&checknumber=".$_POST['checknumber']."\");</script>";
                #echo "<script>window.location.replace(\"pagos.php\");</script>";

            }
                ?>
            </form>
        </div>
    </body>
</html>