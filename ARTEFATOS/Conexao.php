<?php
    //define('HOST'    ,'localhost');
    //define('DBNAME'  ,'poo');
    //define('USER'    ,'root');
    //define('PASSWORD','Maria123*');
    
    namespace App\Classes;

    class Conexao{
        private static $pdo;

        private function __construct(){}

        public static function getConexao(){
            if(!isset(self::$pdo)):
                $opcoes = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$pdo = new \PDO('mysql:host='. DBHOST.';dbname='.DBNAME.';', DBUSER, DBPASS, $opcoes);
                //self::$pdo = new PDO('mysql:host='.HOST.';dbname='.DBNAME.';', USER, PASSWORD);
            endif;
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return self::$pdo;
        }

    }

    