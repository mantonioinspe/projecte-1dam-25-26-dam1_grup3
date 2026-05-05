<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */

// Mostrar todos los errores y advertencias en pantalla
function crear_incidencia($conn)
{
    // Obtenir el noms del formulari
    $id_departament = $_POST['id_departament'];
    $data_fin = $_POST['data_fin'];
    $prioritat = $_POST['prioritat'];
    $descripcio = $_POST['descripcio'];

    $sql_dept = "SELECT nom FROM DEPARTAMENT WHERE id_departament = ?";

    $sentencia->bind_param("s", $id_departament); 
    $sentencia->execute();
    $result = $sentencia->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $nom_dept = $row['nom_dept'];
        echo "Departament trobat: " . $nom_dept;
        $sql = "INSERT INTO incidencia (data_fin, prioritat, descripcio, departament) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
        $stmt->bind_param("sss", $data_fin, $prioritat, $descripcio);
    } else {
        echo "<p class='info'>No es pot assignar una incidencia en departament que no existeix.</p>";
    }
    // Comprovar si el nom no està buit
    // Si l'html està ben escrit això no podria passar en els usuaris normals
    // Igualment SEMPRE s'ha de comprovar tot al backend ja que no tots els usuaris
    // són "bones persones" i des de les web tools es pot canviar tot el front per exemple.
    if (empty($nom)) {
        echo "<p class='error'>La incidencia no pot estar buida.</p>";
        return;
    }
    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        echo "<p class='info'>Incidencia creada amb èxit!</p>";
    } else {
        echo "<p class='error'>Error al crear la incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }
    // Tancar la declaració i la connexió
    $stmt->close();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estils.css">
    <title>Crear incidencia</title>
</head>

<body>
    <h1>Crear incidencia</h1>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_incidencia($conn);
    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        ?>
        <form method="POST" action="crear_incidencia.php">
                <label for="nom_dept">ID departament</label>
                <input type="text" id="id_departament" name="id_departament" placeholder="XXXXXXXXXX" required>
                <label for="nom_dept">Departament</label>
                <input type="text" id="nom_dept" name="nom_dept" placeholder="XXXXXXXXXX" required>
                <label for="descripcio">Descripcio</label>
                <textarea placeholder="Descripció" class="form-control" name="descripcio" id="descripcio" cols="30" rows="10" required></textarea>
                <label for="prioritat">Prioritat</label>
                <input type="text" id="prioritat" name="prioritat" placeholder="XXXXX" required>
                <label for="data_fin">Data limit</label>
                <input type="text" id="data_fin" name="data_fin" required placeholder="YYYY-MM-DD">
                <div class="form-group"><button class="btn btn-success">Crear</button></div>
        </form>
        <?php
    }
    ?>
    <footer id="menu">
        <table>
            <tr>
                <td>
                    <button type="submit" class="form-button"><a href="llistar.php">Temps consumit per departament</a></button>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>

