<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
    require_once "../conexion.php";
    $id_user = $_SESSION['idUser'];
    $query = mysqli_query($conexion, "SELECT p.*, s.nombre AS sala, u.nombre FROM pedidos p INNER JOIN salas s ON p.id_sala = s.id INNER JOIN usuarios u ON p.id_usuario = u.id");
    include_once "includes/header.php";
?>
    <div class="card">
        <div class="card-header">
            Historial pedidos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sala</th>
                            <th>Mesa</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Usuario</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) {
                            if ($row['estado'] == 'PENDIENTE') {
                                $estado = '<span class="badge badge-danger">Pendiente</span>';
                            } else {
                                $estado = '<span class="badge badge-success">Completado</span>';
                            }
                        ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['sala']; ?></td>
                                <td><?php echo $row['num_mesa']; ?></td>
                                <td><?php echo $row['fecha']; ?></td>
                                <td><?php echo $row['total']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td>
                                    <a href="#" class="btn"><?php echo $estado; ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permisos.php');
}
?>