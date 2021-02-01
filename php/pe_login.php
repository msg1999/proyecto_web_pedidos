<!--Daniel Gonzalez-->
<?php 
	session_start();

	if (isset($_POST) && !empty($_POST) && !isset($_SESSION["idUsuario"])) {

		include_once 'funciones.php';

		$idUsuario = obtenerAcceso($_POST["username"], $_POST["password"]);

		if ($idUsuario == null) {
			echo "<p style='color: red'>El nombre de usuario y contraseña no coinciden</p>";
		} else {
			$_SESSION["idUsuario"] = $idUsuario;
		}
		
	}
	
	// 28/01/2021 -> Daniel González Carretero, a petición del cliente
	if (isset($_SESSION["idUsuario"])) {
		if (file_exists("../index.php")) header("location: ./../index.php");
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
