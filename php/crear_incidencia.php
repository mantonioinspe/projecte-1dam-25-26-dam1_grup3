<?php

// Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova incidencia a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_incidencia($conn)
{
    $id_departament = $_POST['id_departament'];
    $nom_dept = $_POST['nom_dept'];
    $data_fin = $_POST['data_fin'];
    $prioritat = $_POST['prioritat'];
    $descripcio = $_POST['descripcio'];
    $sql_check = "SELECT ID_Departament, Nom FROM DEPARTAMENT WHERE ID_Departament = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check === false) {
        die("Error en preparar la consulta de verificació: " . $conn->error);
    }
    $stmt_check->bind_param("s", $id_departament);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    if ($row = $result->fetch_assoc()) {
        $nom_dept_bd = $row['Nom'];
        echo "Departament trobat: " . $nom_dept_bd;
        $sql = "INSERT INTO INCIDENCIA (ID_Departament, Data_FIN, Prioridad, Descripcio) VALUES (?, ?, ?, ?)";
        $sentencia = $conn->prepare($sql);
        if ($sentencia === false) {
            die("Error en preparar la consulta d'inserció: " . $conn->error);
        }
        $sentencia->bind_param("ssss", $id_departament, $data_fin, $prioritat, $descripcio);
    } else {
        echo "<p class='info'>No es pot assignar una incidencia en departament que no existeix.</p>";
        $stmt_check->close();
        return;
    }
    $stmt_check->close();
    if (empty($nom_dept)) {
        echo "<p class='error'>La incidencia no pot estar buida.</p>";
        $sentencia->close();
        return;
    }
    if ($sentencia->execute()) {
        echo "<p class='info'>Incidencia creada amb èxit!</p>";
    } else {
        echo "<p class='error'>Error al crear la incidencia: " . htmlspecialchars($sentencia->error) . "</p>";
    }
    $sentencia->close();
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
    <div class = "encabezado">
        <div class="nav_menu">
            <h1 class="text-center"><a href="index.php">GI3P</a></h1>
            <h1 class="text-center"><a href="index.php">Institut Pedralbes</a></h1>
        </div>
        
    <div class="container mt-4">
        <h1>Crear incidència</h1>
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            crear_incidencia($conn);
        } else {
            ?>
        <form method="POST" action="crear_incidencia.php">
            <label for="id_departament" class="form-label">ID departament</label>
            <input type="text" id="id_departament" name="id_departament" placeholder="1, 2, 3" required>
            <label for="nom_dept" class="form-label">Departament</label>
            <input type="text" id="nom_dept" name="nom_dept" placeholder="Nom del departament" required>
            <label for="descripcio" class="form-label">Descripcio</label>
            <textarea placeholder="Descripció" class="form-control" name="descripcio" id="descripcio" rows="5" required></textarea>
            <div class="mb-3">
                    <label for="prioritat" class="form-label">Prioritat</label>
                    <select class="form-control" name="prioritat" id="prioritat" required>
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                        <option value="Crítica">Crítica</option>
                    </select>
                </div>
            <label for="data_fin" class="form-label">Data limit</label>
            <input type="text" id="data_fin" name="data_fin" required placeholder="YYYY-MM-DD">
                <button class="btn btn-success">Crear</button>
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