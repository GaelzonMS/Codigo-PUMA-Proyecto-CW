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
            if ($tablaTDV[$t][2]>250)
            {
                echo "Exceso";
            }
            else
            {
                echo $tablaTDV[$t][2];
            }
        } 
        else 
        {
            echo $tablaTDV[$t][$col];
        }
    }
    echo "<br>";
    $t++;
}
?>