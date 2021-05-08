<?php

    namespace App\Models;
    use App\Classes\Crud;

    class Marca{
        private static $table = 'tabMarcaVeiculo';
        private static $RegistroExiste = [ 'MarcaVeiculo'];
    
        public static function getMarca(int $id){
            $sql = 'select * from '.self::$table.' where id=:id;';
            $arrayCondicao = [ 'id' => $id];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, false);
            if( $stmt) {
                return $stmt;
            }else {
                throw new \Exception( "Nenhuma marca encontrada !");
            }
        }    

        public static function getMarcaAll(){
           $sql = 'select * from '.self::$table.';';
            $arrayCondicao = [];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, true);
            if( $stmt) {
                return $stmt;
            } else {
                throw new \Exception( "Nenhuma marca encontrada !");
            }
        }    
    
        public static function insert($dados){
            if( Crud::isCampoTabela(self::$table, $dados)){
                if( !Crud::RegistroJaExiste( self::$table, self::$RegistroExiste, $dados)){
                    $stmt = Crud::insert(self::$table, $dados);
                    //$stmt = false;
                    if( $stmt) {
                        return 'Marca fabricante inserido com sucesso !';
                    } else {
                        throw new \Exception( "Falha ao inserir a marca !");
                    }
                } else {
                    throw new \Exception( "Marca já esta cadastrada !");    
                }
            } else {
                throw new \Exception( "Falha campos nao existem !");
            }
        }    
        public static function getLista( $campos, $id = null ){
            $sql = 'select '.$campos.' from '.self::$table.';';// where id=:id;';
            //echo $sql;
            $arrayCondicao = [ 'id' => $id];
            //$arrayCondicao = [];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, true);
            if( $stmt) {
                return $stmt;
            }else {
                throw new \Exception( "Nenhum local encontrado !");
            }
        }
    
    }
