<HTML>
    <HEAD> <TITLE> Funciones </TITLE>
     <meta charset="utf-8">   
    </HEAD>
    <BODY>
<?php

# Función : realizarConsulta
        # Parámetros: 
        #   - $conn: La conexion PDO
        #   - $consulta: La sentencia select a ejecutar
        #
        # Funcionalidad: ejecuta un select y devuelve el resultado
        # 
        #
        # Retorna: $selec, array asociativo con el resultado de la consulta sql 
        #
        # Código realizado por Jorge Blazquez Alvarez
        # Fecha 15-01-2021
function realizarConsulta($conexion, $consulta){
    $con = $conexion->prepare($consulta);
    $con->execute();
    $select=$con->fetchAll(PDO::FETCH_ASSOC);
    return $select;
}

# Función : realizarConsultaUnValor
        # Parámetros: 
        #   - $conn: La conexion PDO
        #   - $consulta: La sentencia select a ejecutar
        #
        # Funcionalidad: ejecuta un select y devuelve el resultado
        # 
        #
        # Retorna: $selec, el valor de la consulta 
        #
        # Código realizado por Jorge Blazquez Alvarez
        # Fecha 15-01-2021
function realizarConsultaUnValor($conexion, $consulta){
    $con = $conexion->prepare($consulta);
    $con->execute();
    $selec=$con->fetchColumn();
    return $selec;
}
?>

  


  