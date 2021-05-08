<?php 

    namespace App\Services;
    use App\Models\User;

    class UserService {

        public function get($id = null){
            if( $id){
                return User::getUser($id);
            } else {
                return User::getUserAll();
            }
        }
        public function post(){
            //return $_POST;
            $dados = $_POST;
            return User::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }


    }