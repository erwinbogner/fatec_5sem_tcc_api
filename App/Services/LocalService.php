<?php 

    namespace App\Services;
    use App\Models\Local;

    class LocalService {

        public function get($id = null){
            if( $id){
                return Local::getLocal($id);
            } else {
                return Local::getLocalAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return Local::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }
