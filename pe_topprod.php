<!DOCTYPE html>
<html>
<head>
	<title>Consultar ventas totales</title>
	<meta charset="utf-8" />
	<meta name="author" value="Daniel Ganzález Carretero" />
</head>
<body>

	<form  action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' method="POST">
		<label>Fecha de Inicio:</label>
		<input type="date" name="fechaInicio" required /><br />

		<label>Fecha de Fin:</label>
		<input type="date" name="fechaFin" required /><br />

		<input type="submit" value="Consultar ventas" />
	</form>

	<?php 

		include_once 'funciones.php';

		if (isset($_POST) && !empty($_POST) && isset($_POST["fechaInicio"]) && isset($_POST["fechaFin"])) {
			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];

			$ventas = consultarTotalVentas($fechaInicio, $fechaFin);

			if ($ventas == null) {
				echo "<p>Parece que no ha habido ninguna venta sobre esas fechas...</p>";
			} else {
				echo "<table border='1' cellpadding='3'><tr><th>Nombre del Producto</th><th>Precio de cada Unidad</th><th>Unidades Vendidas</th></tr>";
				foreach ($ventas as $venta) {
					echo "<tr><td>" . $venta["nombre"] . "</td><td>" . $venta["precio"] . "</td><td>" . $venta["unidades"] . "</td></tr>";
				}
			}
		}

	?>

</body>
</html>