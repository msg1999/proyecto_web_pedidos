<?php 
	$servername = "localhost";
	$username = "root";
	$password = "rootroot";
	$database = "pedidos";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); 	 	 	 	 	 	
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        echo("OK"); 	 	 	 	
	} catch (PDOException $ex) {
		echo $ex->getMessage();
	}
	
?>