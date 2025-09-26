<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre_usuario"])) {
  header("Location: login.html"); // Redirigir a la página de inicio de sesión si no ha iniciado sesión
  exit();
}

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

// Obtener los datos del formulario
$titulo = $_POST["titulo"];
$mensaje = $_POST["mensaje"];
$autor = $_SESSION["nombre_usuario"];

// Consulta para insertar el nuevo tema en la base de datos
$sql = "INSERT INTO temas (titulo, mensaje, autor) VALUES ('$titulo', '$mensaje', '$autor')";

if ($conn->query($sql) === TRUE) {
  echo "Tema creado exitosamente.";
  header("Location: foro.php"); // Redirigir al foro
  exit();
} else {
  echo "Error al crear el tema: " . $conn->error;
}

$conn->close();

?>