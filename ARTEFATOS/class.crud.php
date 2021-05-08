<?php

ini_set( 'Display_errors',1);
require_once 'class.conexao.php';

class Crud{

    private static $pdo;
    private static $tabela;

    public static function setConexao($conexao){
        self::$pdo = $conexao;
    }

    public static function setTabela($tabela){
        self::$tabela = $tabela;
    }

    private static function montaSQLInsert($arrayDados){
        $colunas = implode(', ', array_keys($arrayDados));
        $parametros = ':'.implode(', :',array_keys($arrayDados));
        $sql  = 'INSERT INTO '. self::$tabela;
        $sql .= '('. $colunas . ') VALUES(' . $parametros . ')';
        return $sql;
    }
    private static function montaSQLUpdate( $arrayDados, $arrayCondicao){
        $sql = 'UPDATE ' . self::$tabela . ' SET ';
        foreach($arrayDados as $key => $value):
            $sql .= "{$key} = :{$key}, ";
        endforeach;
        $sql  = rtrim($sql, ', ');
        $sql .= ' WHERE ';
        foreach($arrayCondicao as $key => $value):
            $sql .= " {$key} = :{$key} AND";
        endforeach;
        $sql  = rtrim($sql, 'AND');
        return $sql;
    }
    private static function montaSQLDelete ( $arrayCondicao){
        $sql  = 'DELETE FROM ' . self::$tabela . ' WHERE ';
        foreach($arrayCondicao as $key => $value):
            $sql .= " {$key} = :{$key} AND";
        endforeach;
        $sql = rtrim($sql, 'AND');
        return $sql;
    }
    public static function insert($arrayDados){
        $sql = self::montaSQLInsert($arrayDados);
        $stm = self::$pdo->prepare($sql);
        foreach($arrayDados as $key => $value){
            $stm->bindValue( ':' . $key, $value);
        }
        $retorno = $stm->execute();
        if( $retorno == true){
            $retorno = [ 'status' => 201, 'msg' => 'OK... dados inseridos com sucesso !!!' ];
        } else {
            $retorno = [ 'status' => 300, 'msg' => 'ERRO... parametros incorretos !!!' ];
        }
        http_response_code($retorno['status']);
        //return json_encode($retorno);
        return $retorno;
    }
    public static function update($arrayDados, $arrayCondicao){
        $sql = self::montaSQLUpdate($arrayDados, $arrayCondicao);
        $stm = self::$pdo->prepare($sql);
        foreach($arrayDados as $key => $value){
            $stm->bindValue(':'.$key, $value);
        }
        foreach($arrayCondicao as $key => $value){
            $stm->bindValue(':'.$key, $value);
        }
        $retorno = $stm->execute();
        return $retorno;
    }
    public static function delete($arrayCondicao){
        $sql = self::montaSQLDelete($arrayCondicao);
        $stm = self::$pdo->prepare($sql);
        foreach($arrayCondicao as $key => $value){
            $stm->bindValue( ':' . $key, $value);
        }
        $retorno = $stm->execute();
        return $retorno;
    }

    public static function select( $sql, $arrayCondicao, $fetchAll){
        $stm = self::$pdo->prepare($sql);
        foreach($arrayCondicao as $key => $value){
            $stm->bindValue( ':'.$key, $value);
        }
        $stm->execute();
        //var_dump($stm);
        if( $stm->rowCount() <= 0){
            $retorno = [ 'status' => 404, 'mensagem' => 'ERRO... dados não encontratos !!!' ];
            http_response_code($retorno['status']);
        } else { 
            //$retorno = [ 'status' => 200, 'msg' => 'OK... dados consultados com sucesso !!!' ];
            //http_response_code('200');
            if($fetchAll){
                $retorno = $stm->fetchAll(PDO::FETCH_OBJ);
            } else {
                $retorno = $stm->fetch(PDO::FETCH_OBJ);
            }
        }
        return $retorno;
    }

    public static function RecebeTabela($tabela){
        $sql = "show columns from ".$tabela.";";
        $stm = self::$pdo->prepare($sql);
        $stm->execute();
        //var_dump( $stm);
        //if( $stm->rowCount() <= 0){
        $retorno = $stm->fetchAll(PDO::FETCH_OBJ);
        return $retorno;
    }
    
    public static function isCampoTabela($tabela, $Campo){
        $tabelaBD = Crud::RecebeTabela($tabela);
        //var_dump( $tabelaBD);
        $achou = false;
        foreach( $tabelaBD as $campoBD => $valor){
            //echo $campoBD;
            //echo $valor->Field;
            //echo $valor;
            if( $valor->Field == $Campo){
                $achou = true;
            }
        }
        return $achou;

    }

    public static function RecebeSaldoEstoque( $id){
        $sql = "select saldo_atual from estoque where id=:id";
        $stm = self::$pdo->prepare($sql);
        $stm->bindValue( ':id', $id);
        $stm->execute();
        //var_dump($stm);
        $retorno = $stm->fetch(PDO::FETCH_OBJ);
        return $retorno->saldo_atual;
    }

}

