<?php
// 1. Incluir conexión a la base de datos
require_once '../config/database.php';

// 2. Inicializar variables
$cliente = [
    'nombre' => '',
    'correo' => '',
    'telefono' => '',
    'direccion' => '',
    'ciudad' => ''
];
$esNuevo = true;
$mensaje = '';

// 3. Si es edición, cargar datos del cliente
if (isset($_GET['id'])) {
    $esNuevo = false;
    $id = new MongoDB\BSON\ObjectId($_GET['id']);
    $clienteExistente = $db->clientes->findOne(['_id' => $id]);
    if ($clienteExistente) {
        $cliente = (array) $clienteExistente;
    }
}

// 4. Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $cliente = [
        'nombre' => $_POST['nombre'],
        'correo' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'direccion' => $_POST['direccion'],
        'ciudad' => $_POST['ciudad']
    ];

    // Validar datos
    if (empty($cliente['nombre']) || empty($cliente['correo'])) {
        $mensaje = 'Por favor, completa los campos obligatorios.';
    } else {
        try {
            if ($esNuevo) {
                // Insertar nuevo cliente
                $cliente['fecha_registro'] = date('Y-m-d');
                $db->clientes->insertOne($cliente);
            } else {
                // Actualizar cliente existente
                $db->clientes->updateOne(
                    ['_id' => $id],
                    ['$set' => $cliente]
                );
            }
            // Redireccionar a la lista
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $mensaje = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $esNuevo ? 'Nuevo Cliente' : 'Editar Cliente'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="<https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css>" rel="stylesheet">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2><?php echo $esNuevo ? 'Nuevo Cliente' : 'Editar Cliente'; ?></h2>

                <?php if ($mensaje): ?>
                    <div class="alert alert-danger">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo *</label>
                        <input type="email" class="form-control" id="correo" name="correo"
                               value="<?php echo htmlspecialchars($cliente['correo']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono"
                               value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Direccion</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                               value="<?php echo htmlspecialchars($cliente['direccion']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad"
                               value="<?php echo htmlspecialchars($cliente['ciudad']); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js>"></script>
</body>
</html>
