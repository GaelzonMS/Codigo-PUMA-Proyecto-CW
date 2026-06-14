<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "moodle_enp_ete"
);

if (!$conn) {
    die("Error de conexión");
}
?>