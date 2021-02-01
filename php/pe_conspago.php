<?php 
	session_start();
	include_once 'funciones.php';

	if (isset($_SESSION) && !empty($_SESSION) && isset($_SESSION["idUsuario"])) {

		$debeMostrarse = false;

		if (isset($_POST) && !empty($_POST)) {
			if (isset($_POST["fechaInicio"]) && !empty($_POST["fechaInicio"]) && isset($_POST["fechaFin"]) && !empty($_POST["fechaInicio"])) {
				$fechaInicio = $_POST["fechaInicio"];
				$fechaFin = $_POST["fechaFin"];
			} else {
				$fechaInicio = "0000-00-00";
				$fechaFin = "3000-12-31"; 
			}

			$usuario = $_SESSION["idUsuario"];

			$pagos = consultarTotalPagos($fechaInicio, $fechaFin, $usuario);
			$debeMostrarse = true;

		}

	} else {
		echo "<p>Parece que tienes que iniciar sesión primero... Haz <a href='pe_login.php'>click aquí</a> para iniciar sesión</p>"; // En caso de que no se redireccione automáticamente
		header("location: pe_login.php");
	}
?>
<html>
<head>
	<title>Consultar pagos realizados</title>
	<meta charset="utf-8" />
	<meta name="author" value="Daniel Ganzález Carretero" />
</head>
<body>

	<form  action='<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>' method="POST">
		<label>Fecha de Inicio:</label>
		<input type="date" name="fechaInicio" /><br />

		<label>Fecha de Fin:</label>
		<input type="date" name="fechaFin" /><br />

		<input type="submit" value="Consultar ventas" />
	</form>

	<?php 
		if ($debeMostrarse) {
			if ($pagos == null) {
				echo "<p>Parece que no se realizó ningún pago sobre esas fechas...</p>";
			} else {
				echo "<table border='1' cellpadding='3'><tr><th>Fecha</th><th>Cantidad Pagada</th></tr>";

				foreach ($pagos as $pago) {
					echo "<tr><td>" . $pago["paymentDate"] . "</td><td>" . $pago["amount"] . "</td></tr>";
				}
			}
		}
	?>

</body>
