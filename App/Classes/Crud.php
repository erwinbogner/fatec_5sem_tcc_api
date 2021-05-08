<?php

namespace App\Classes;
use App\Classes\Conexao;

class Crud{

    private static $pdo;
    private static $tabela;

    public static function setConexao($conexao){
        self::$pdo = $conexao;
    }

    public static function setTabela($tabela){
        self::$tabela = $tabela;
    }

    private static function montaSQLConsulta($arrayDados){
        //var_dump($arrayDados);
        //rotina não ativa, precisa fazer
        $colunas = implode(', ', array_keys($arrayDados));
        $parametros = ':'.implode(', :',array_keys($arrayDados));
        $sql  = 'SELECTINSERT INTO '. self::$tabela;
        $sql .= '('. $colunas . ') VALUES(' . $parametros . ');';
        return $sql;
    }
    
    private static function montaSQLInsert($arrayDados){
        //var_dump($arrayDados);
        $colunas = implode(', ', array_keys($arrayDados));
        $parametros = ':'.implode(', :',array_keys($arrayDados));
        $sql  = 'INSERT INTO '. self::$tabela;
        $sql .= '('. $colunas . ') VALUES(' . $parametros . ');';
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
    public static function insert($tabela, $arrayDados){
        $pdo = Conexao::getConexao();

        self::setTabela($tabela);

        $sql = self::montaSQLInsert($arrayDados);
        //var_dump($sql);

        $stm = $pdo->prepare($sql);
        foreach($arrayDados as $key => $value){
            $stm->bindValue( ':' . $key, $value);
        }
        $resultado = $stm->execute();
        //echo '1: '. $resultado;
        //var_dump($resultado);
        if( $resultado){
            return [ 'dados' => 'OK... dados inseridos com sucesso !!!' ];
        } else {
            return false;
        }
        
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

    public static function select($tabela, $sql, $arrayCondicao, $fetchAll){
        //echo '<br>1 '.$sql;
        //echo '<br>2 '.$tabela;
        //echo '<br>3 '.$arrayCondicao;
        //var_dump($sql);
        //var_dump($arrayCondicao);
        $pdo = Conexao::getConexao();

        self::setTabela($tabela);
        $stm = $pdo->prepare($sql);
        foreach($arrayCondicao as $key => $value){
            $stm->bindValue( ':'.$key, $value);
        }
        $stm->execute();
        //var_dump($sql);
        if($fetchAll){
            return $stm->fetchAll(\PDO::FETCH_OBJ);
        } else {
            return $stm->fetch(\PDO::FETCH_OBJ);
        }
    }

    public static function RecebeTabela($tabela){
        $pdo = Conexao::getConexao();
        self::setTabela($tabela);
        $sql = "show columns from ".$tabela.";";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        //var_dump( $stm);
        //if( $stm->rowCount() <= 0){
        $retorno = $stm->fetchAll(\PDO::FETCH_OBJ);
        //var_dump($retorno);
        //if( $retorno){
        return $retorno;
        //} else {
        //    return false;
        //}
    }

    public static function RecebeTabelasAll(){
        $pdo = Conexao::getConexao();
        
        $sql = "show tables;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        //var_dump( $stm);
        //if( $stm->rowCount() <= 0){
        $retorno = $stm->fetchAll(\PDO::FETCH_OBJ);
        //var_dump($retorno);
        //if( $retorno){
        return $retorno;
        //} else {
        //    return false;
        //}
    }
    
    public static function RegistroJaExiste( $tabela, $Registro, $arrayDados){
        $pdo = Conexao::getConexao();
        
        $sql = 'select * from '.$tabela. ' where ';
        $i = 1;
        $arrayNovo = [];

        for( $i=0; $i<count($Registro); $i++){            
            $sql .= $Registro[$i] . '=:'.$Registro[$i]. ' AND ';

            $achou = false;
            foreach( $arrayDados as $campoBD => $valor){
                if( $Registro[$i] == $campoBD){
                    //echo '<br> key: '.$campoBD.' - valor: '.$valor;
                    $achou = true;
                    //$stm->bindValue(':'.$usuario[$i], $campoBD);
                    $arrayNovo[$Registro[$i]] = $valor;
                }
            }
        }
        $sql  = rtrim($sql, ' AND ');
        $stm = $pdo->prepare($sql);

        foreach($arrayNovo as $key => $value){
            $stm->bindValue( ':' . $key, $value);
        }
        $stm->execute();
        $resultado = $stm->fetchAll(\PDO::FETCH_OBJ);
        if( $resultado){
            return true;
        } else {
            return false;
        }
    }

    public static function isCampoTabela($tabela, $Campo){
        $tabelaBD = Crud::RecebeTabela($tabela);
        //var_dump( $Campo);
        //'$achou = true;
        foreach( $Campo as $CampoSite => $ $ValorSite) {
            $achou = false;
            //echo '<br>Campo site: '.$CampoSite;
            foreach( $tabelaBD as $campoBD => $valor){
                //echo $campoBD;
                //echo $valor->Field;
                //echo $valor;
                if( $valor->Field == $CampoSite){
                    $achou = true;
                    break;
                }
            }
            if( $achou == false){
                break;
            }
        }
        return $achou;
    }

}