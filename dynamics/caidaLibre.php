<?php         
const gravedad = 32;

//__________________________________________________
// Cálculo de distancia
// _________________________________________________
function calcDistancia($tiempo, $veli){
    $dist = $veli*$tiempo+0.5*gravedad * $tiempo ** 2;
    return $dist;
}

//__________________________________________________
// Cálculo de velocidad
// _________________________________________________
function calcVelocidad($tiempo, $veli){
    $velf = $veli+gravedad*$tiempo;
    return $velf;
}

//__________________________________________________
// Crea tabla que contiene tiempo, distancia y velocidad final
// _________________________________________________
function generaTabla(){
    global $tablaTDV;
    $tiempo = 0;
    for ($t = 1; $t <= 10; $t++){
        $tiempo=$tiempo + 1.0; 
        $tablaTDV[$t-1][0] = $tiempo;
        $tablaTDV[$t-1][1] = calcDistancia($tablaTDV[$t-1][0], 0);
        $tablaTDV[$t-1][2] = calcVelocidad($tablaTDV[$t-1][0], 0);
    }

}
$col = 0;
$t = 0;
generaTabla();
echo  "_________________________________________________________<br>";
echo "|Tiempo (seg)     |Posición final     |Velocidad final (ft/s)|<br>";
echo "_________________________________________________________<br>";

while($t < 10){
    for ($col = 0; $col < 3; $col ++){
        echo "|     ";
        if($col == 2){
            if ($tablaTDV[$t][2]>250){
                echo "Exceso";
            }
            else{
                echo $tablaTDV[$t][2];
            }
        } else {
            echo $tablaTDV[$t][$col];
        }
    }
    echo "<br>";
    $t++;
}

?>

-------------------------------------------------------------------------------------------------------


<?php       
const gravedad = 32;
//__________________________________________________
// Cálculo de distancia
// _________________________________________________
function calcDistancia($tiempo, $veli){
    $dist = $veli*$tiempo+0.5*gravedad * $tiempo ** 2;
    return $dist;
}

//__________________________________________________
// Cálculo de velocidad
// _________________________________________________
function calcVelocidad($tiempo, $veli){
    $velf = $veli+gravedad*$tiempo;
    return $velf;
}

//__________________________________________________
// Crea tabla que contiene tiempo, distancia y velocidad final
// _________________________________________________
/*function generaTabla(){
    global $tablaTDV;
    $tiempo = 0;
    for ($t = 1; $t <= 10; $t++){
        $tiempo=$tiempo + 1.0; 
        $tablaTDV[$t-1][0] = $tiempo;
        $tablaTDV[$t-1][1] = calcDistancia($tablaTDV[$t-1][0], 0);
        $tablaTDV[$t-1][2] = calcVelocidad($tablaTDV[$t-1][0], 0);
    }

}

$col = 0;
$t = 0;
generaTabla();
echo  "_________________________________________________________<br>";
echo "|     |l     ||<br>";
echo "_________________________________________________________<br>";

while($t < 10){
    for ($col = 0; $col < 3; $col ++){
        echo "|     ";
        if($col == 2){
            if ($tablaTDV[$t][2]>250){
                echo "Exceso";
            }
            else{
                echo $tablaTDV[$t][2];
            }
        } else {
            echo $tablaTDV[$t][2];
        }
    }
    echo "<br>";
    $t++;
}*/

function generaTabla():array {
    $tabla=[];
    for ($t = 1; $t <= 10; $t++){
        $tiempo=$t;
        $tabla[]=[
            $tiempo,
            calcDistancia($tiempo, 0),
            calcVelocidad($tiempo, 0),
        ];

    }
    return $tabla;
}

$tablaTDV=generaTabla();

$filas=" ";

foreach($tablaTDV as $fila)
{
    if($fila[2]>250)
    {
        $vel="<td>Exceso</td>";
    }
    else
    {
        $vel="<td>".number_format($fila[2],0)."</td>";
    }
}


echo "
<!DOCTYPE html>
<html lang'es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Caida libre</title>
</head>
<body>
    <h1>Caida Libre </h1>
    <table border = '4' >
        <thead>
            <tr>
                <th>Tiempo (seg)</th>
                <th>Posición final</th>
                <th>Velocidad final (ft/s)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>   
        </tbody>
    </table>
</body>
</html>
";


?>