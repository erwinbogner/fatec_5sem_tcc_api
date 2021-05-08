<?php 

    namespace App\Services;
    use App\Models\Tipo;

    class TipoService {

        public function get($id = null){
            if( $id){
                return Tipo::getTipo($id);
            } else {
                return Tipo::getTipoAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Tipo::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }
