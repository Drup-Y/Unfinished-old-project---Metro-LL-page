<?php
session_start();

// Obtener el ID del tema
$idTema = $_GET["id"];

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_de_datos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener la información del tema
$sql = "SELECT * FROM temas WHERE id = $idTema";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo "<h2>" . $row["titulo"] . "</h2>";
  echo "<p>Por: " . $row["autor"] . " - " . $row["fecha_creacion"] . "</p>";
  echo "<p>" . $row["mensaje"] . "</p>";

  // Mostrar formulario para responder al tema (si ha iniciado sesión)
  if (isset($_SESSION["nombre_usuario"])) {
    echo "<form action='responder_tema.php' method='post'>";
    echo "<input type='hidden' name='id_tema' value='" . $idTema . "'>";
    echo "<label for='respuesta'>Responder:</label><br>";
    echo "<textarea id='respuesta' name='respuesta' required></textarea><br><br>";
    echo "<input type='submit' value='Responder'>";
    echo "</form>";
  }

  // Mostrar las respuestas al tema
  $sql = "SELECT * FROM respuestas WHERE id_tema = $idTema ORDER BY fecha_creacion ASC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h3>Respuestas</h3>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>";
      echo "<p>Por: " . $row["autor"] . " - " . $row["fecha_creacion"] . "</p>";
      echo "<p>" . $row["mensaje"] . "</p>";
      echo "</li>";
    }
    echo "</ul>";
  }
} else {
  echo "<p>Tema no encontrado.</p>";
}

$conn->close();

?>