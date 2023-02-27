<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost";
$usuario = "rafaelhinestrosa";
$contrasenia = "Yv4*1z6c";
$nombreBaseDatos = "rafaelhinestrosa";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);

//Listar clientes
if (isset($_GET["consultar"])) {
    $sqlClientes = mysqli_query($conexionBD, "SELECT * FROM clientes WHERE estado= 'alta'");
    if (mysqli_num_rows($sqlClientes) > 0) {
        $clientes = mysqli_fetch_all($sqlClientes, MYSQLI_ASSOC);
        echo json_encode($clientes);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Listar clientes Baja
if (isset($_GET["consultarBaja"])) {
    $sqlClientes = mysqli_query($conexionBD, "SELECT * FROM clientes WHERE estado= 'baja'");
    if (mysqli_num_rows($sqlClientes) > 0) {
        $clientes = mysqli_fetch_all($sqlClientes, MYSQLI_ASSOC);
        echo json_encode($clientes);
    } else {
        echo json_encode([["success" => 0]]);
    }
}


//Listar polizas de cliente
if (isset($_GET["consultarPolizas"])) {
    $id = $_GET["consultarPolizas"];
    $sqlPolizas = mysqli_query($conexionBD, "SELECT * FROM polizas WHERE id_cliente=$id AND estadoPoliza= 'alta'");
    if (mysqli_num_rows($sqlPolizas) > 0) {
        $polizas = mysqli_fetch_all($sqlPolizas, MYSQLI_ASSOC);
        echo json_encode($polizas);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Datos de poliza
if (isset($_GET["consultarPoliza"])) {
    $id = $_GET["consultarPoliza"];
    $sqlPolizas = mysqli_query($conexionBD, "SELECT * FROM polizas WHERE id=$id");
    if (mysqli_num_rows($sqlPolizas) > 0) {
        $polizas = mysqli_fetch_all($sqlPolizas, MYSQLI_ASSOC);
        echo json_encode($polizas);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Listar polizas
if (isset($_GET["listaPolizas"])) {
    $sqlPolizas = mysqli_query($conexionBD, "SELECT * FROM polizas WHERE estadoPoliza= 'alta'");
    if (mysqli_num_rows($sqlPolizas) > 0) {
        $polizas = mysqli_fetch_all($sqlPolizas, MYSQLI_ASSOC);
        echo json_encode($polizas);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Listar polizas Baja
if (isset($_GET["listaPolizasBaja"])) {
    $sqlPolizas = mysqli_query($conexionBD, "SELECT * FROM polizas WHERE estadoPoliza= 'baja'");
    if (mysqli_num_rows($sqlPolizas) > 0) {
        $polizas = mysqli_fetch_all($sqlPolizas, MYSQLI_ASSOC);
        echo json_encode($polizas);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Consultar Cliente
if (isset($_GET["consultarCliente"])) {
    $id = $_GET["consultarCliente"];
    $sqlCliente = mysqli_query($conexionBD, "SELECT * FROM clientes WHERE id=$id");
    if (mysqli_num_rows($sqlCliente) > 0) {
        $cliente = mysqli_fetch_all($sqlCliente, MYSQLI_ASSOC);
        echo json_encode($cliente);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Insertar Cliente
if (isset($_GET["insertar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $apellidos = $data->apellidos;
    $telefono = $data->telefono;
    $localidad = $data->localidad;
    $cp = $data->cp;
    $provincia_id = $data->provincia;
    $entidad = $data->entidad;
    $estado = $data->estado;
    if (($nombre != "") && ($apellidos != "") && ($telefono != "") && ($localidad != "") && ($cp != "") && ($provincia_id != "") && ($entidad != "") && ($estado != "")) {
        $sqlProvincia = mysqli_query($conexionBD, "SELECT provincia FROM provincias WHERE id = '$provincia_id'");
        $provincia = mysqli_fetch_array($sqlProvincia)['provincia'];
        $sqlClientes = mysqli_query($conexionBD, "INSERT INTO clientes(nombre,apellidos,telefono,localidad,cp,provincia,entidad,estado,provincia_nombre) VALUES('$nombre','$apellidos','$telefono','$localidad','$cp','$provincia_id','$entidad','$estado','$provincia') ");
        echo json_encode(["success" => 1]);
    }
    exit();
}

//Insertar Poliza
if (isset($_GET["insertarPoliza"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id_cliente = $data->id_cliente;
    $numeroPoliza = $data->numeroPoliza;
    $importe = $data->importe;
    $fecha = $data->fecha;
    $estado = $data->estado;
    $observaciones = $data->observaciones;
    $estadoPoliza = $data->estadoPoliza;
    if (($id_cliente != "") && ($numeroPoliza != "") && ($importe != "") && ($fecha != "") && ($estado != "") && ($observaciones != "") && ($estadoPoliza != "")) {
        $sqlClientes = mysqli_query($conexionBD, "INSERT INTO polizas(id_cliente,numero_poliza,importe_recibo,fecha,estado,observaciones,estadoPoliza)
        VALUES('$id_cliente','$numeroPoliza','$importe','$fecha','$estado','$observaciones','$estadoPoliza') ");
        echo json_encode(["success" => 1]);
    }
    exit();
}

//Comprobar usuario existente
if (isset($_GET["login"])) {
    $email = mysqli_real_escape_string($conexionBD, $_GET["login"]);
    $sqlUsuarios = mysqli_query($conexionBD, "SELECT * FROM usuarios WHERE email='$email'");
    if (mysqli_num_rows($sqlUsuarios) > 0) {
        $usuarios = mysqli_fetch_all($sqlUsuarios, MYSQLI_ASSOC);
        echo json_encode($usuarios);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

//borrarClientes
if (isset($_GET["borrar"])) {
    $sqlClientes = mysqli_query($conexionBD, "UPDATE clientes SET estado='baja' WHERE id=" . $_GET["borrar"]);
    if ($sqlClientes) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

//altaClientes
if (isset($_GET["alta"])) {
    $sqlClientes = mysqli_query($conexionBD, "UPDATE clientes SET estado='alta' WHERE id=" . $_GET["alta"]);
    if ($sqlClientes) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

//borrarPoliza
if (isset($_GET["borrarPoliza"])) {
    $sqlPolizas = mysqli_query($conexionBD, "UPDATE polizas SET estadoPoliza='baja' WHERE id=" . $_GET["borrarPoliza"]);
    if ($sqlPolizas) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

//altaPoliza
if (isset($_GET["altaPoliza"])) {
    $sqlPolizas = mysqli_query($conexionBD, "UPDATE polizas SET estadoPoliza='alta' WHERE id=" . $_GET["altaPoliza"]);
    if ($sqlPolizas) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

//Provincias
if (isset($_GET["consultar_provincias"])) {
    $sqlProvincias = mysqli_query($conexionBD, "SELECT * FROM provincias");
    if (mysqli_num_rows($sqlProvincias) > 0) {
        $provincias = mysqli_fetch_all($sqlProvincias, MYSQLI_ASSOC);
        echo json_encode($provincias);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

//Localidades
if (isset($_GET["consultar_localidades"]) && isset($_GET["provincia"])) {
    $provincia = $_GET["provincia"];
    $sqlLocalidades = mysqli_query($conexionBD, "SELECT municipio FROM municipios WHERE provincia='$provincia'");
    if (mysqli_num_rows($sqlLocalidades) > 0) {
        $localidades = mysqli_fetch_all($sqlLocalidades, MYSQLI_ASSOC);
        echo json_encode($localidades);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

// Método para obtener el nombre de una provincia por id
if (isset($_GET["obtener_nombre_provincia"])) {
    $idProvincia = $_GET["obtener_nombre_provincia"];
    $sqlProvincia = mysqli_query($conexionBD, "SELECT provincia FROM provincias WHERE id = $idProvincia");
    if (mysqli_num_rows($sqlProvincia) > 0) {
        $provincia = mysqli_fetch_assoc($sqlProvincia);
        echo json_encode($provincia);
    } else {
        echo json_encode(["success" => 0]);
    }
}

// Editar Cliente
if (isset($_GET["actualizar_cliente"])) {

    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    $nombre = $data->nombre;
    $apellidos = $data->apellidos;
    $telefono = $data->telefono;
    $localidad = $data->localidad;
    $cp = $data->cp;
    $provincia_id = $data->provincia;
    $entidad = $data->entidad;
    $sqlProvincia = mysqli_query($conexionBD, "SELECT provincia FROM provincias WHERE id = '$provincia_id'");
    $provincia = mysqli_fetch_array($sqlProvincia)['provincia'];
    $sqlClientes = mysqli_query($conexionBD, "UPDATE clientes SET nombre='$nombre',apellidos='$apellidos',telefono='$telefono',localidad='$localidad',cp='$cp',provincia='$provincia_id',entidad='$entidad',provincia_nombre='$provincia' WHERE id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}

// Editar Poliza
if (isset($_GET["actualizar_poliza"])) {

    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    $id_cliente = $data->id_cliente;
    $numero_poliza = $data->numero_poliza;
    $importe_recibo = $data->importe_recibo;
    $fecha = $data->fecha;
    $estado = $data->estado;
    $observaciones = $data->observaciones;
    $estadoPoliza = $data->estadoPoliza;

    $sqlPolizas = mysqli_query($conexionBD, "UPDATE polizas SET id_cliente='$id_cliente',numero_poliza='$numero_poliza',importe_recibo='$importe_recibo',fecha='$fecha',estado='$estado',observaciones='$observaciones',estadoPoliza='$estadoPoliza' WHERE id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}

// // Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
// if (isset($_GET["consultar"])){
//     $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM empleados WHERE id=".$_GET["consultar"]);
//     if(mysqli_num_rows($sqlEmpleaados) > 0){
//         $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
//         echo json_encode($empleaados);
//         exit();
//     }
//     else{  echo json_encode(["success"=>0]); }
// }
// //borrar pero se le debe de enviar una clave ( para borrado )
// if (isset($_GET["borrar"])){
//     $sqlEmpleaados = mysqli_query($conexionBD,"DELETE FROM empleados WHERE id=".$_GET["borrar"]);
//     if($sqlEmpleaados){
//         echo json_encode(["success"=>1]);
//         exit();
//     }
//     else{  echo json_encode(["success"=>0]); }
// }
// //Inserta un nuevo registro y recepciona en método post los datos de nombre y correo
// if(isset($_GET["insertar"])){
//     $data = json_decode(file_get_contents("php://input"));
//     $nombre=$data->nombre;
//     $correo=$data->correo;
//         if(($correo!="")&&($nombre!="")){
            
//     $sqlEmpleaados = mysqli_query($conexionBD,"INSERT INTO empleados(nombre,correo) VALUES('$nombre','$correo') ");
//     echo json_encode(["success"=>1]);
//         }
//     exit();
// }
// // Actualiza datos pero recepciona datos de nombre, correo y una clave para realizar la actualización
// if(isset($_GET["actualizar"])){
    
//     $data = json_decode(file_get_contents("php://input"));

//     $id=(isset($data->id))?$data->id:$_GET["actualizar"];
//     $nombre=$data->nombre;
//     $correo=$data->correo;
    
//     $sqlEmpleaados = mysqli_query($conexionBD,"UPDATE empleados SET nombre='$nombre',correo='$correo' WHERE id='$id'");
//     echo json_encode(["success"=>1]);
//     exit();
// }
// // Consulta todos los registros de la tabla empleados
// $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM empleados ");
// if(mysqli_num_rows($sqlEmpleaados) > 0){
//     $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
//     echo json_encode($empleaados);
// }
// else{ echo json_encode([["success"=>0]]); }


// 
