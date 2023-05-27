<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header text-center">
        Salas
    </div>
    <div class="card-body">
        <div class="row">
            <?php
            include "../conexion.php";
            $query = mysqli_query($conexion, "SELECT * FROM salas WHERE estado = 1");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <div class="col-md-3 shadow-lg">
                        <div class="col-12">
                            <img src="../assets/img/salas.jpg" class="product-image" alt="Product Image">
                        </div>
                        <h6 class="my-3 text-center"><span class="badge badge-info"><?php echo $data['nombre']; ?></span></h6>

                        <div class="mt-4">
                            <a class="btn btn-primary btn-block btn-flat" href="mesas.php?id_sala=<?php echo $data['id']; ?>&mesas=<?php echo $data['mesas']; ?>">
                                <i class="far fa-eye mr-2"></i>
                                Mesas
                            </a>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permisos.php');
}
?>