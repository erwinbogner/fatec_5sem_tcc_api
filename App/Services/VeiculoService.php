<?php 

    namespace App\Services;
    use App\Models\Veiculo;

    class VeiculoService {

        public function get($id = null){
            if( $id){
                return Veiculo::getVeiculo($id);
            } else {
                return Veiculo::getVeiculoAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Veiculo::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }
