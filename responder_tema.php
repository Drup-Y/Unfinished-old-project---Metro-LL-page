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
$idTema = $_POST["id_tema"];
$respuesta = $_POST["respuesta"];
$autor = $_SESSION["nombre_usuario"];

// Consulta para insertar la nueva respuesta en la base de datos
$sql = "INSERT INTO respuestas (id_tema, mensaje, autor) VALUES ($idTema, '$respuesta', '$autor')";

if ($conn->query($sql) === TRUE) {
  echo "Respuesta enviada exitosamente.";
  header("Location: tema.html?id=" . $idTema); // Redirigir al tema
  exit();
} else {
  echo "Error al enviar la respuesta: " . $conn->error;
}

$conn->close();

?>