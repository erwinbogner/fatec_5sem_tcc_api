<?php

    namespace App\Models;
    use App\Classes\Crud;

    class User{
        private static $table = 'tabUsuario';
        private static $RegistroExiste = [ 'email', 'cpf_cnpj', 'apelido'];
    
        public static function getUser(int $id){
            $sql = 'select * from '.self::$table.' where id=:id;';
            $arrayCondicao = [ 'id' => $id];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, false);
            if( $stmt) {
                return $stmt;
            }else {
                throw new \Exception( "Nenhum usuário encontrado !");
            }
        }    

        public static function getUserAll(){
           $sql = 'select * from '.self::$table.';';
            $arrayCondicao = [];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, true);
            if( $stmt) {
                return $stmt;
            } else {
                throw new \Exception( "Nenhum usuário encontrado !");
            }
        }    
    
        public static function insert($dados){
            if( Crud::isCampoTabela(self::$table, $dados)){
                if( !Crud::RegistroJaExiste( self::$table, self::$RegistroExiste, $dados)){
                    $stmt = Crud::insert(self::$table, $dados);
                    //$stmt = false;
                    if( $stmt) {
                        return 'Usuario inserido com sucesso !';
                    } else {
                        throw new \Exception( "Falha ao inserir usuário !");
                    }
                } else {
                    throw new \Exception( "Usuario já esta cadastrado !");    
                }
            } else {
                throw new \Exception( "Falha campos nao existem !");
            }
        }    
        public static function VerificaUsuario($apelido, $senha){
            $sql = 'select id, ativo from '.self::$table.' where apelido=:apelido and senha=:senha;';
            //var_dump($sql);
            $arrayCondicao = [ 'apelido' => $apelido, 'senha' => $senha ];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, true);
            if( $stmt) {
                //var_dump($stmt[0]->ativo);
                if( $stmt[0]->ativo == 'A'){
                    return $stmt[0]->id;
                } else {
                    throw new \Exception( "Usuário inativo !");
                }
                //return $stmt;
            } else {
                throw new \Exception( "Usuário ou senha invalios !");
            }
         }    
     
     
    }
