<?php 

    namespace App\Services;
    use App\Models\Tabela;

    class TabelaService {

        public function get($id = null){
            if( $id === 'all'){
                return Tabela::getTabelaAll();
            } else {    
                return Tabela::getTabela($id);
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Tabela::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }


    }