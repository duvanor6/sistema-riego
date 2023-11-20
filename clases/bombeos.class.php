<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class bombeos extends conexion {
    private $table = "Bombeos";
    private $id_usuario = "id_usuario";
    private $id_cultivo = "id_cultivo";
    private $fecha = "fecha";
    private $hora = "hora";
    private $tiempo_riego = "tiempo_riego";
    private $humedad_activacion = "humedad_activacion";
    private $humedad_inactivacion = "humedad_inactivacion";
    private $token = "";

    public function listaBombeos($pagina = 1){
        $inicio = 0;
        $cantidad = 100;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) +1;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT * FROM ". $this->table . "limit $inicio,$cantidad";
        $datos = parent::obetenerDatos($query);
        return $datos;
    }

    public function obtenerBombeo($id){
        $query = "SELECT * FROM ". $this->table . "WHERE ID_Bombeo = '$id'";
        return parent::obtenerDatos($query);
    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset[$datos['token']]){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['id_usuario']) || !isset($datos['id_cultivo']) || !isset($datos['fecha']) || !isset($datos['hora']) || !isset($datos['tiempo_riego']) || !isset($datos['humedad_activacion']) || !isset($datos['humedad_inactivacion'])){
                    return $_respuestas->error_400();
                }else{
                    
                echo("token encontrado!");
                    $this->id_usuario = $datos['id_usuario'];
                    $this->id_cultivo = $datos['id_cultivo'];
                    $this->fecha = $datos['fecha'];
                    $this->hora = $datos['hora'];
                    $this->tiempo_riego = $datos['tiempo_riego'];
                    $this->humedad_activacion = $datos['humedad_activacion'];
                    $this->humedad_inactivacion = $datos['humedad_inactivacion'];
                    $resp = $this->insertarBombeo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_bombeo" => $resp
                        );
                        return $respuesta;
                    }else{}
                        echo("Error al insertar encontrado!");
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El token que envio es invalido o ha caducado");
            }
        }
    }

    private function insertarBombeo(){
        $query = "INSERT INTO ". $this->table . " (id_usuario,id_cultivo,fecha,hora,tiempo_riego,humedad_activacion,humedad_inactivacion) values ('" . $this->id_usuario . "','" . $this->id_cultivo . "','" . $this->fecha . "','" . $this->hora . "','" . $this->tiempo_riego . "','" . $this->humedad_activacion . "','" . $this->humedad_inactivacion . "')";

        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas -> error_400();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['id_bombeo'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_bombeo = $datos['id_bombeo'];
                    if(isset($datos['id_usuario'])) { $this->id_usuario = $datos['id_usuario']; }
                    if(isset($datos['id_cultivo'])) { $this->id_cultivo = $datos['id_cultivo']; }
                    if(isset($datos['fecha'])) { $this->fecha = $datos['fecha']; }
                    if(isset($datos['hora'])) { $this->hora = $datos['hora']; }
                    if(isset($datos['tiempo_riego'])) { $this->tiempo_riego = $datos['tiempo_riego']; }
                    if(isset($datos['humedad_activacion'])) { $this->humedad_activacion = $datos['humedad_activacion']; }
                    if(isset($datos['humedad_inactivacion'])) { $this->humedad_inactivacion = $datos['humedad_inactivacion']; }
                    $resp = $this->modificarBombeo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_bombeo" => $this->id_bombeo
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El token que envio es invalido o ha caducado");
            }
        }
    }

    private function modificarBomber(){
        $query = "UPDATE ". $this->table . " SET id_usuario = '" . $this->id_usuario . "', id_cultivo = '" . $this->id_cultivo . "', fecha = '" . $this->fecha . "', hora = '" . $this->hora . "', tiempo_riego = '" . $this->tiempo_riego . "', humedad_activacion = '" . $this->humedad_activacion . "', humedad_inactivacion = '" . $this->humedad_inactivacion . "' WHERE ID_Bombeo = '" . $this->id_bombeo . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset)($datos['toke'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['id_bombeo'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_bombeo = $datos['id_bombeo'];
                    $resp = $this->eliminarBombeo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_bombeo" => $this->id_bombeo
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El token que envio es invalido o ha caducado");
            }
        }
    }

    private function eliminarBombeo(){
        $query = "DELETE FROM ". $this->table . " WHERE ID_Bombeo = '" . $this->id_bombeo . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    private function buscarToken(){
        $query = "SELECT TokenId,IdUsuario,Estado FROM usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

}


?>