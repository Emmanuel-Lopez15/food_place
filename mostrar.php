<!DOCTYPE html>
<htm1>
<head>
    <title>Mostrar Registro</title>
</head>
<body>
    <center>
        <table border="2">
            <thead>
                <tr>
                <th>id</th>
                <th>Nombre</th>
                <th>Imagen</th>
                </tr>

            </thead>
            <tbody>
                <?php
                    include("conexion.php");

                    $query = "SELECT * FROM restaurants";
                    $resultado= $conexion->query($query);
                    while($row = $resultado->fetch_assoc()){
                ?>
                    <tr>
                        <td> <?php echo $row['id']; ?></td>
                        <td> <?php echo $row['name']; ?></td>
                        <td><img | src="data:image/jpg;base64, <?php echo base64_encode($row['imagen']); ?>"/></td>
                    </tr>
                <?php
                    }   
                ?>
            </tbody>
        </table>
    </center>
</body>
</htm1>