<?php
include('conexion.php');

$id = $_GET['id'];

$sql = "DELETE FROM restaurants WHERE id = ?";

mysqli_query($conexion, "DELETE FROM restaurants where id=$id");
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
	echo "<script>alert('Registro eliminado exitosamente'); window.location='admin_home.php';</script>";
} else {
	echo "<script>alert('Error al eliminar'); window.location='admin_home.php';</script>";
}