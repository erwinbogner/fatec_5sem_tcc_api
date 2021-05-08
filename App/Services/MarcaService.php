<?php 

    namespace App\Services;
    use App\Models\Marca;

    class MarcaService {

        public function get($id = null){
            if( $id){
                return Marca::getMarca($id);
            } else {
                return Marca::getMarcaAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Marca::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }
