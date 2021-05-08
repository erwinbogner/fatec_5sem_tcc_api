<?php

    namespace App\Models;
    use App\Classes\Crud;

    class Tipo{
        private static $table = 'tabTipoMovto';
        private static $RegistroExiste = [ 'TipoMovto'];
    
        public static function getTipo(int $id){
            $sql = 'select * from '.self::$table.' where id=:id;';
            $arrayCondicao = [ 'id' => $id];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, false);
            if( $stmt) {
                return $stmt;
            }else {
                throw new \Exception( "Nenhum tipo de movimento encontrado !");
            }
        }    

        public static function getTipoAll(){
           $sql = 'select * from '.self::$table.';';
            $arrayCondicao = [];
            $stmt = Crud::select(self::$table, $sql, $arrayCondicao, true);
            if( $stmt) {
                return $stmt;
            } else {
                throw new \Exception( "Nenhum tipo de movimento encontrado !");
            }
        }    
    
        public static function insert($dados){
            if( Crud::isCampoTabela(self::$table, $dados)){
                if( !Crud::RegistroJaExiste( self::$table, self::$RegistroExiste, $dados)){
                    $stmt = Crud::insert(self::$table, $dados);
                    //$stmt = false;
                    if( $stmt) {
                        return 'Tipo movimento inserido com sucesso !';
                    } else {
                        throw new \Exception( "Falha ao inserir tipo movimento !");
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
