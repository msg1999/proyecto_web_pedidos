<!DOCTYPE html>
<html>
	<head>
		<title>Consulta</title>
		<meta charset="utf-8" />
		<meta name="author" value="Silvia Ranera" />
	</head>
	<body>

	<form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
		<label for="customer">Cliente:</label>
		<select name='customer' required>
			<option selected disabled value="noSeleccionado">Selecciona un Cliente</option>
			<?php
				include_once 'funciones.php';

				$customers = devolverCustomers();
				foreach($customers as $customer) {
                    echo "<option value='". $customer["customerNumber"] ."'>[". $customer["customerNumber"] ."]: ". $customer["customerName"] ."</option>";
                }
			?>
		</select><br>

		<input type="submit" name="submit" value="Consultar Pedidos" />
	</form>

		<?php	
			// Archivo de funciones ya incluído en la línea 15

			if (isset($_POST) && !empty($_POST) && $_POST["customer"] != "noSeleccionado" ) {
				$customerNumber = $_POST["customer"];

				$orders = devolverOrders($customerNumber);

				if ($orders == null) {
					echo "<p>No parece haber ningún pedido realizado por este cliente. Inténtalo de nuevo, quizá es error nuestro.</p>";
				} else {
					echo "<table border='1' cellspan='3'>";
					foreach ($orders as $pedido) {
						echo "<tr><td><p>";
							echo "Pedido nº: <strong>". $pedido["orderNumber"] ."</strong> [". $pedido["orderDate"] ."] &nsbp;&nsbp;->&nsbp;&nsbp;<strong>". $pedido["status"];

						echo "</p><p>";
							echo "[<span>". $pedido["productName"] ."</span>], (". $pedido["priceEach"] ."€ x ". $pedido["quantityOrdered"]. "); Línea de Producto <span>". $pedido["orderLineNumber"] ."</span>";

						echo "</p></td></tr>";

					}
					echo "</table>";
				}

				


			}
		?>	
 	</form>
</body>
</html>
