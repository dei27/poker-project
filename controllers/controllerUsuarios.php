<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/UsuarioModel.php');

function getAllUsuarios() {
    $usuarioModel = new UsuarioModel();
    $usuarios = $usuarioModel->getAllUsuarios();
    return json_encode($usuarios);
}


function getUsuarioById($usuarioId) {
    $claseUsuario = new UsuarioModel();
    $claseUsuario->setId($usuarioId);
    $usuario = $claseUsuario->getUsuarioById();
    return json_encode($usuario);
}

if (isset($_POST['action']) && $_POST['action'] === 'addUsuario') {
    $nickname = isset($_POST['nicknameUsuarioNuevo']) ? htmlspecialchars($_POST['nicknameUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;
    $password = isset($_POST['passwordUsuarioNuevo']) ? htmlspecialchars($_POST['passwordUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;
    $email = isset($_POST['emailUsuarioNuevo']) ? htmlspecialchars($_POST['emailUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;

    $cedulaUsuarioNuevo = isset($_POST['cedulaUsuarioNuevo']) ? htmlspecialchars($_POST['cedulaUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;
    $telefonoUsuarioNuevo = isset($_POST['telefono_usuarioUsuarioNuevo']) ? htmlspecialchars($_POST['telefono_usuarioUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;
    $nacimientoUsuarioNuevo = isset($_POST['nacimientoUsuarioNuevo']) ? htmlspecialchars($_POST['nacimientoUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;
    $ingresoUsuarioNuevo = isset($_POST['ingresoUsuarioNuevo']) ? htmlspecialchars($_POST['ingresoUsuarioNuevo'], ENT_QUOTES, 'UTF-8') : null;

    $role = isset($_POST['roleNuevo']) ? htmlspecialchars($_POST['roleNuevo'], ENT_QUOTES, 'UTF-8') : null;

    $usuarioModel = new UsuarioModel();
    $usuarioModel->setNickName($nickname);
    $usuarioModel->setEmail($email);
    $usuarioModel->setCedula($cedulaUsuarioNuevo);
    $usuarioModel->setTelefonoUsuario($telefonoUsuarioNuevo);
    $usuarioModel->setFechaNacimiento($nacimientoUsuarioNuevo);
    $usuarioModel->setFechaIngreso($ingresoUsuarioNuevo);
    $usuarioModel->setRole($role);

    $password_encriptada = hash('sha512', $password);
    $usuarioModel->setPassword($password_encriptada);

    $result = $usuarioModel->newUsuario();

    switch ($result) {
        case 0:
            header("Location: ../views/usuarios.php?insertedUser=0");
            exit();
            break;
        case 1:
            header("Location: ../views/usuarios.php?insertedUser=1");
            exit();
            break;
        case 100:
            header("Location: ../views/usuarios.php?nickEmail=1");
            exit();
        break;
        case 101:
            header("Location: ../views/usuarios.php?nickname=1");
            exit();
            break;
        case 102:
            header("Location: ../views/usuarios.php?email=1");
            exit();
            break;
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'editUsuario') {
    $id = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);
    $nuevaPassword = isset($_POST['passwordUsuario']) ? ($_POST['passwordUsuario'] !== '' ? htmlspecialchars($_POST['passwordUsuario'], ENT_QUOTES, 'UTF-8') : null) : null;

    $nicknameUsuarioUpdate = isset($_POST['nicknameUsuarioUpdate']) ? ($_POST['nicknameUsuarioUpdate'] !== '' ? htmlspecialchars($_POST['nicknameUsuarioUpdate'], ENT_QUOTES, 'UTF-8') : null) : null;
    $emailUsuarioUpdate = isset($_POST['emailUsuarioUpdate']) ? ($_POST['emailUsuarioUpdate'] !== '' ? htmlspecialchars($_POST['emailUsuarioUpdate'], ENT_QUOTES, 'UTF-8') : null) : null;
    $cedulaUsuarioUpdate = isset($_POST['cedulaUsuarioUpdate']) ? ($_POST['cedulaUsuarioUpdate'] !== '' ? htmlspecialchars($_POST['cedulaUsuarioUpdate'], ENT_QUOTES, 'UTF-8') : null) : null;
    $telefono_usuarioUsuarioUpdate = isset($_POST['telefono_usuarioUsuarioUpdate']) ? ($_POST['telefono_usuarioUsuarioUpdate'] !== '' ? htmlspecialchars($_POST['telefono_usuarioUsuarioUpdate'], ENT_QUOTES, 'UTF-8') : null) : null;
    $nacimientoUsuarioUpdate = isset($_POST['nacimientoUsuarioUpdate']) ? ($_POST['nacimientoUsuarioUpdate'] !== '' ? htmlspecialchars($_POST['nacimientoUsuarioUpdate'], ENT_QUOTES, 'UTF-8') : null) : null;
    $ingresoUsuarioUpdate = isset($_POST['ingresoUsuarioUpdate']) ? ($_POST['ingresoUsuarioUpdate'] !== '' ? htmlspecialchars($_POST['ingresoUsuarioUpdate'], ENT_QUOTES, 'UTF-8') : null) : null;
    $roleUsuario = isset($_POST['role']) ? ($_POST['role'] !== '' ? htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8') : null) : null;

    $usuarioModel = new UsuarioModel();
    $usuarioModel->setId($id);
    $usuarioModel->setNickName($nicknameUsuarioUpdate);
    $usuarioModel->setEmail($emailUsuarioUpdate);
    $usuarioModel->setCedula($cedulaUsuarioUpdate);
    $usuarioModel->setTelefonoUsuario($telefono_usuarioUsuarioUpdate);
    $usuarioModel->setFechaNacimiento($nacimientoUsuarioUpdate);
    $usuarioModel->setFechaIngreso($ingresoUsuarioUpdate);
    $cambioClave = false;
    $cambioRole = false;
    $updatedUsuario = $usuarioModel->updateUsuarioById();

    if ($nuevaPassword !== null && $roleUsuario !== null) {
        // Encriptar la nueva contraseña si existe
        if ($nuevaPassword !== null) {
            $password_encriptada = hash('sha512', $nuevaPassword);
            $usuarioModel->setPassword($password_encriptada);
            $cambioClave = $usuarioModel->updatePasswordById();
        }

        // Actualizar el rol si existe
        if ($roleUsuario !== null) {
            $usuarioModel->setRole($roleUsuario);
            $cambioRole = $usuarioModel->updateRoleById();
        }

        // Verificar si tanto la contraseña como el rol se actualizaron correctamente
        if (isset($cambioClave, $cambioRole) && $cambioClave && $cambioRole) {
            header("Location: ../views/usuarios.php?updatedUser=1");
            exit();
        } else {
            header("Location: ../views/usuarios.php?updatedUser=0");
            exit();
        }
    }

    // Si solo se está actualizando la contraseña
    if ($nuevaPassword !== null || $updatedUsuario) {
        $password_encriptada = hash('sha512', $nuevaPassword);
        $usuarioModel->setPassword($password_encriptada);
        $cambioClave = $usuarioModel->updatePasswordById();

        // Verificar si la contraseña se actualizó correctamente
        if ($cambioClave) {
            header("Location: ../views/usuarios.php?updatedUser=1");
            exit();
        } else {
            header("Location: ../views/usuarios.php?updatedUser=0");
            exit();
        }
    }

    // Si solo se está actualizando el rol
    if ($roleUsuario !== null || $updatedUsuario) {
        $usuarioModel->setRole($roleUsuario);
        $cambioRole = $usuarioModel->updateRoleById();

        // Verificar si el rol se actualizó correctamente
        if ($cambioRole) {
            header("Location: ../views/usuarios.php?updatedUser=1");
            exit();
        } else {
            header("Location: ../views/usuarios.php?updatedUser=0");
            exit();
        }
    }

}


if (isset($_GET['action']) && $_GET['action'] === 'activate' && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $usuarioModel = new UsuarioModel();
    $usuarioModel->setId($id);

    if ($id !== null) {
        $usuarioModel->setHabilitado(1);
        $habilitar = $usuarioModel->habilitarDesahabilitarUsuarioById();

        if($habilitar){
            header("Location: ../views/usuarios.php?habilitar=1");
            exit();
        }else {
            header("Location: ../views/usuarios.php?habilitar=0");
            exit();
        }
    }
    else {
        header("Location: ../views/usuarios.php?habilitar=0");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $usuarioModel = new UsuarioModel();
    $usuarioModel->setId($id);

    if ($id !== null) {
        $usuarioModel->setHabilitado(0);
        $deshabilitar = $usuarioModel->habilitarDesahabilitarUsuarioById();

        if($deshabilitar){
            header("Location: ../views/usuarios.php?deshabilitar=1");
            exit();
        }else {
            header("Location: ../views/usuarios.php?deshabilitar=0");
            exit();
        }
    }
    else {
        header("Location: ../views/usuarios.php?deshabilitar=0");
        exit();
    }
}


if(isset($_POST['action']) && $_POST['action'] == 'validarCamposUsuario') {

    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $modeloUsuarios = new UsuarioModel();
    $existe = $modeloUsuarios->verificarExistenciaCampo($campo, $valor);

    echo json_encode(['existe' => $existe]);
    exit;
}










