<?php

// Datos de conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor de base de datos no está en localhost
$username = "tu_usuario"; // Reemplaza con tu nombre de usuario de la base de datos
$password = "tu_contraseña"; // Reemplaza con tu contraseña de la base de datos
$dbname = "tu_base_de_datos"; // Reemplaza con el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombreUsuario = $_POST["nombre_usuario"];
$contrasena = $_POST["contrasena"];

// Consulta para buscar al usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombreUsuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // El usuario existe
  $row = $result->fetch_assoc();
  $contrasenaHash = $row["contrasena"];

  // Verificar la contraseña
  if (password_verify($contrasena, $contrasenaHash)) {
    // Contraseña correcta, iniciar sesión
    session_start();
    $_SESSION["nombre_usuario"] = $nombreUsuario;
    header("Location: index.html"); // Redirigir a la página principal
    exit();
  } else {
    // Contraseña incorrecta
    echo "Contraseña incorrecta.";
  }
} else {
  // El usuario no existe
  echo "Nombre de usuario no encontrado.";
}

$conn->close();

?>