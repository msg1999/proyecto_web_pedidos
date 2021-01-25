<!--Jorge Blazquez-->
<HTML lang="es">
    <HEAD>
        <TITLE> Consulta Stock  </TITLE>
        <meta charset="utf-8">
        <meta name="author" content="Jorge Blazquez">
        <style type="text/css">
			table, th, td {
				border: 1px solid black;
			}
        </style>
    </HEAD>
    <BODY>
        <?php
            include_once 'funciones.php';
            if (!isset($_POST) || empty($_POST)) {
                echo "<form action='pe_constock.php' method='post'><label for='pline'>Linea de Producto</label>
                            <select name='pline'>";
                $productline=consultaProductLine();
                foreach ($productline as $linea) {
                    echo "<option value='".$linea['productLine']."'>".$linea['productLine']."</option>";
                }
                echo "</select><input type='submit' value='Consultar Stock'></form>";
            }else{
                echo "<table><tr><th>Producto</th><th>Stock</th></tr>";
                $productos=consultaStock($_POST['pline']);
                foreach ($productos as $producto) {
                    echo "<tr><td>".$producto['productName']."</td><td>".$producto['quantityInStock']."</td></tr>";
                }
                echo "</table>";
            }
        ?>
    </BODY>
</HTML>