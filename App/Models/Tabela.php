<?php

    namespace App\Models;
    use App\Classes\Crud;

    class Tabela{
        private static $table = '';
    
        public static function getTabela($tabela){
            $stmt = Crud::RecebeTabela($tabela);
            if( $stmt) {
                return $stmt;
            }else {
                throw new \Exception( "Tabela não encontrado !");
            }
        }    

        public static function getTabelaAll(){
            $stmt = Crud::RecebeTabelasAll();
            if( $stmt) {
                return $stmt;
            }else {
                throw new \Exception( "Tabela não encontrado !");
            }
        }    
    
        public static function insert($dados){
            //$connPdo = new \PDO( DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS );
        
            //$sql = 'insert into '.self::$table.' (nome, email, password) values (:no, :em, :pa);';
            
            $stmt = Crud::insert(self::$table, $dados);
            //$stmt = $connPdo->prepare($sql);
            //$stmt->bindValue(':no', $dados['nome']);
            //$stmt->bindValue(':em', $dados['email']);
            //$stmt->bindValue(':pa', $dados['senha']);
            //$stmt->execute();

            //var_dump( $stmt->fetch(\PDO::FETCH_ASSOC));
            //if($stmt->rowCount() >0){
            //    return 'Usuario inserido com sucesso !';
            //} else {
            //    throw new \Exception( "Falha ao inserir usuário !");
            //}
            if( $stmt) {
                return 'Usuario inserido com sucesso !';
            } else {
               throw new \Exception( "Falha ao inserir usuário !");
            }
        }    
    
    }
