<!--Marco Santiago-->
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <div>Obtener stock</div>

		<form name='formulario' action='pe_consprodstock.php' method='post'>
            <label for="producto">Producto</label>
            <select name="producto">
            <?php
                include_once("funciones.php");
                
                try {
                    $productos=get_productos($conexion);

                    for ($a=0; $a < count($productos); $a++) { 
                        echo("<option>".$productos[$a]["productName"]."</option>");
                    }
                }
                catch(PDOException $e) {
                    echo ("Error recuperar productos: -->".$e->getMessage()."</br>");
                }
            ?>
            </select>
            <input type="submit" name="enviar" id="enviar" value="Enviar">
            <?php
                if (!isset($_POST) || empty($_POST)) {
            ?>
            <?php
                } else {
                    $producto=$_POST["producto"];
                    $stock=get_stock($producto);
                    
                    echo($stock[0]['quantityInStock']);
                }
            ?>
		</form>
    </body>
</html>