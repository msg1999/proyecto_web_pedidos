<?php 
	session_start();

	if (isset($_POST) && !empty($_POST)) {

		include_once 'funciones.php';

		$idUsuario = obtenerAcceso($_POST["username"], $_POST["password"]);

		if ($idUsuario == null) {
			echo "<p style='color: red'>El nombre de usuario y contraseña no coinciden</p>";
		} else {
			$_SESSION["idUsuario"] = $idUsuario;
		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inicio de Sesión</title>
	<meta charset="utf-8" />
	<meta name="author" value="Daniel Ganzález Carretero" />
</head>
<body>

	<form  action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' method="POST">
		<label>Nombre de Usuario:</label>
		<input type="text" name="username" required /><br />

		<label>Contraseña:</label>
		<input type="password" name="password" required /><br />

		<input type="submit" value="Iniciar Sesión" />
	</form>

</body>
</html>