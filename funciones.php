<?php 
  include_once 'conn.php';
  
  function consultarTotalPagos($fechaInicioBusqueda, $fechaFinBusqueda, $usuarioBusqueda) {
    // Daniel González Carretero
    // La función consulta todos los pagos realizados entre las fechas $fechaInicioBusqueda y $fechaFinBusqueda
    // Devuelve un array con los pagos realizados, o NULL si ha habido algún error / no hay pagos entre esas fechas
    
    global $conexion;

    try {
        $obtenerVentas = $conexion->prepare("SELECT paymentDate, amount FROM payments WHERE customerNumber = :usuario AND payments.paymentDate >= :fechaInicio AND payments.paymentDate <= :fechaFin");
        $obtenerVentas->bindParam(":fechaInicio", $fechaInicioBusqueda);
        $obtenerVentas->bindParam(":fechaFin", $fechaFinBusqueda);
        $obtenerVentas->bindParam(":usuario", $usuarioBusqueda);
        $obtenerVentas->execute();

        return $obtenerVentas->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        return null;
    }
}
?>
