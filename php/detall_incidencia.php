<?php

if ($conn->connect_error) {
    echo "<p>Error de connexió: " . htmlspecialchars($conn->connect_error) . "</p>";
    die("Error de connexió: " . $conn->connect_error);
}
function mostrar_incidencia($conn){
    $id_departament = $_POST['id_departament'];
    $data_fin = $_POST['data_fin'];
    $prioridad = $_POST['prioridad'];
    $descripcio = $_POST['descripcio'];

    $sql = "SELECT * FROM incidencia
    ORDER BY prioridad DESC";
}
$stmt->close();


