<h1>Proyecto web pedidos - GRUPO 2 </h1>
<h1>Integrantes</h1>
	<ul>
		<li>BLÁZQUEZ ÁLVAREZ, JORGE</li>
		<li>CATALINAS BUSTOS, JOSÉ</li>
		<li>FERNÁNDEZ GARCÍA, PEDRO</li>
		<li>GONZÁLEZ CARRETERO, DANIEL</li>
		<li>RANERA MARTÍN, SILVIA</li>
		<li>SANTIAGO GONZÁLEZ, MARCO (Jefe Equipo)</li>		
	</ul>
<h1>Asignacion del proyecto</h1>

<table>
	<tr>
		<td>Apartado</td>
		<td>Asignaciones</td>
		<td>Estado</td>
  	</tr>
	<tr>
		<td>1-Login</td>
		<td>Dani</td>
		<td>Finalizado</td>
  	</tr>
  	<tr>
    		<td>2-Realizar pedidos</td>
	  	<td>Pedro</td>
	  	<td>Finalizado</td>
  	</tr>
  	<tr>
    		<td>3-Consulta pedidos</td>
	  	<td>Silvia/Jorge</td>
	  	<td>Finalizado</td>
	</tr>
	<tr>
		<td>4-Consulta stock(producto)</td>
		<td>Marco</td>
		<td>Finalizado</td>
	</tr>
	<tr>
		<td>5-Consulta stock(linea producto)</td>
		<td>Jorge</td>
		<td>Finalizado</td>
	</tr>
	<tr>
		<td>6-Consulta unidades totales</td>
		<td>Dani</td>
		<td>Finalizado</td>
	</tr>
	<tr>
		<td>7-Consulta clientes</td>
		<td>Jose/Dani</td>
		<td>Finalizado</td>
	</tr>
	<tr>
		<td>Refactorizacion</td>
		<td>Marco</td>
		<td>Finalizado</td>
	</tr>
		<tr>
		<td>Pago</td>
		<td>Marco</td>
		<td>Finalizado</td>
	</tr>
</table>

<h1>Normativas sobre el desarrollo</h1>
El proyecto se desarrollara en PDO con el fichero ya subido en este git llamado "conn.php".</br>
Tambien sera desarrollada con sesiones para poder tener interaccion con la misma.</br>
Cada apartado se subira a un branch, el cual se refactorizara y se subira a 'Main'.

<h1>Changelog</h1>
v2.0: </br>
-Implementacion de pago, consecuencia downgrade de php7.0 a 5.6.40</br>
-Reprogramado en mysqli en vez de PDO por fallo en compatibilidad php7.0
</br>
<h1>Errores conocidos v2.0</h1>
*pagos.php linea 10 no funciona;</br>
*precio pagos.php en claro</br>
*cambio en el php.ini por fallo de character-set(imagen en about)</br>
*solo compatible en php < 7.0</br>
