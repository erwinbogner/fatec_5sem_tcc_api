<?php 

    namespace App\Services;
    use App\Models\Modelo;

    class ModeloService {

        public function get($id = null){
            if( $id){
                return Modelo::getModelo($id);
            } else {
                return Modelo::getModeloAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Modelo::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }
