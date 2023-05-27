<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
    include "../conexion.php";
    if (!empty($_POST)) {
        $alert = "";
        $id = $_POST['id'];
        $plato = $_POST['plato'];
        $precio = $_POST['precio'];
        $foto_actual = $_POST['foto_actual'];
        $foto = $_FILES['foto'];
        $fecha = date('YmdHis');
        if (empty($plato) || empty($precio) || $precio < 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            $nombre = null;
            if (!empty($foto['name'])) {
                $nombre = '../assets/img/platos/' . $fecha . '.jpg';
            } else if (!empty($foto_actual) && empty($foto['name'])) {
                $nombre = $foto_actual;
            }

            if (empty($id)) {
                $query = mysqli_query($conexion, "SELECT * FROM platos WHERE nombre = '$plato' AND estado = 1");
                $result = mysqli_fetch_array($query);
                if ($result > 0) {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El plato ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $query_insert = mysqli_query($conexion, "INSERT INTO platos (nombre,precio,imagen) VALUES ('$plato', '$precio', '$nombre')");
                    if ($query_insert) {
                        if (!empty($foto['name'])) {
                            move_uploaded_file($foto['tmp_name'], $nombre);
                        }
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Plato registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                    } else {
                        $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el plato
                  </div>';
                    }
                }
            } else {
                $query_update = mysqli_query($conexion, "UPDATE platos SET nombre = '$plato', precio=$precio, imagen='$nombre' WHERE id = $id");
                if ($query_update) {
                    if (!empty($foto['name'])) {
                        move_uploaded_file($foto['tmp_name'], $nombre);
                    }
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Plato Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        }
    }
    include_once "includes/header.php";
?>
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" autocomplete="off" id="formulario" enctype="multipart/form-data">
                                <?php echo isset($alert) ? $alert : ''; ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="hidden" id="id" name="id">
                                            <input type="hidden" id="foto_actual" name="foto_actual">
                                            <label for="plato" class=" text-dark font-weight-bold">Plato</label>
                                            <input type="text" placeholder="Ingrese nombre del plato" name="plato" id="plato" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="precio" class=" text-dark font-weight-bold">Precio</label>
                                            <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="precio" class=" text-dark font-weight-bold">Foto (512px - 512px)</label>
                                            <input type="file" class="form-control" name="foto" id="foto">
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="">Acciones</label> <br>
                                        <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                        <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Plato</th>
                                        <th>Precio</th>
                                        <th>Imagen</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "../conexion.php";
                                    $query = mysqli_query($conexion, "SELECT * FROM platos WHERE estado = 1");
                                    $result = mysqli_num_rows($query);
                                    if ($result > 0) {
                                        while ($data = mysqli_fetch_assoc($query)) { ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['nombre']; ?></td>
                                                <td><?php echo $data['precio']; ?></td>
                                                <td><img class="img-thumbnail" src="<?php echo ($data['imagen'] == null) ? '../assets/img/default.png' : $data['imagen']; ?>" alt="" width="100"></td>
                                                <td>
                                                    <a href="#" onclick="editarPlato(<?php echo $data['id']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                                    <form action="eliminar.php?id=<?php echo $data['id']; ?>&accion=platos" method="post" class="confirmar d-inline">
                                                        <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permisos.php');
}
?>