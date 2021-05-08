<?php 

    namespace App\Services;
    use App\Models\Pessoa;

    class PessoaService {

        public function get($id = null){
            if( $id){
                return Pessoa::getPessoa($id);
            } else {
                return Pessoa::getPessoaAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Pessoa::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }
