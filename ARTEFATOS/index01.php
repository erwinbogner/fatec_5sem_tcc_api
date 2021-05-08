<?php

    ini_set('display_errors', 1);

    require_once 'classes/class.conexao.php';
    require_once 'classes/class.crud.php';

    $conexao = Conexao::getConexao();
    Crud::setConexao($conexao);
    Crud::setTabela( 'estoque');
    
    $uri = basename($_SERVER[ 'REQUEST_URI']);
    echo '<br>URI: ' . $uri;

    echo '<br> use:<br>
        PUT  => informe id, qtde e tipo de movimentação para inserção de movimentos<br>
        GET  => informe /id ou /* para todas as movimentações a retornar<br>
        POST => informe descricao para inserção de novo produto<br>
    ';

    if( $_SERVER[ 'REQUEST_METHOD'] == 'POST'){
        echo '<br>Chegou via POST<br>';

        //var_dump($_POST);
        $achou = true;
        foreach( $_POST as $key => $value){
            $campos[$key] = $value;
            $retorno = Crud::isCampoTabela('estoque',$key); 
            if( !$retorno){
                $achou = false;
            }
        }
        if( $achou){
            $campos['saldo_atual'] =0;
            $retorno = Crud::insert($campos);
            var_dump( $retorno);
        } else {
            echo 'erro, dados incorretos !!!';
        }
        //dados ->id        -automatico
        //      ->descricao -?????
        //      ->saldo     -0
        //var_dump($campos);
        
        //$dados = json_encode($campos);
        //var_dump($dados);
        
        //$dados2 = [ 'NOME' => 'erwin', 'EMAIL' => 'e-mail', 'TIPO' => 'FISICA' ];
        //var_dump($dados2);
        
        //var_dump($campos);
        //var_dump($dados);
        //try{
            //$retorno = Crud::RecebeTabela('estoque'); 
            //$retorno = Crud::isCampoTabela('estoque','descricao'); 
            //if( $retorno){
            //    echo 'Achou';
            //} else {
            //    echo 'erro';
           // }
            //$retorno = Crud::insert($campos);
            //echo $retorno['msg'];
            //var_dump($retorno);
        //} catch {
            
        //}
        //echo $retorno;
        //var_dump($retorno);
        
        //$nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
        //$tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : '';
        //$email = (isset($_POST['email'])) ? $_POST['email'] : '';

        //echo '<br>[Iniciando inserção]<br>';
        //for($i=1;$i<=1000;$i++){
        //    $nome  = 'Nome '. $i . ' - ' . $tipo_pessoa;
        //    $email = 'nome'.$i.'@';
        //    $dados = [ 'NOME' => $nome, 'EMAIL' => $email, 'TIPO' => $tipo_pessoa ];
        //    //echo '<br>'.$nome;
        //    Crud::insert($dados);

        
        //echo "<br>Nome: {$campos['nome']} - Tipo: {$campos['tipo']} - E-mail: {$campos['email']}";
    } elseif( $_SERVER[ 'REQUEST_METHOD'] == 'GET') {
        echo 'Chegou via GET';
            
        if($uri == '*'){
            $filtro   = [];
            $consulta = "SELECT * FROM movimentacao_estoque;";
        } else {
            $filtro   = [ 'ID' => $uri];
            $consulta = "SELECT * FROM movimentacao_estoque where id_produto = :ID;";
        }
        
        //$filtro  = [];
        $dados  = Crud::select($consulta, $filtro, true);
        //var_dump($dados);
        echo '<pre>';
        print_r( $dados);
        
        //$cont   = 0;
        //if( $dados == true){
        //    foreach($dados as $key){
        //        echo '<br>ID: '.$key->ID .' - Nome: '. $key->NOME . ' - e-mail: '. $key->EMAIL. '[Tipo'.$key->TIPO .'] - ROTA_ID: '.$key->ID_ROTA;
        //        $cont++;
        //    }
        //    echo "<br>[Qtde Registros: {$cont}]<br>";
        //}
       
        
        //$retorno = [ 'status' => 404, 'mensagem' => 'Falta parametros obrigatorios.' ];
        //echo json_encode($retorno);        
        //http_response_code($retorno['status']);
    } elseif( $_SERVER[ 'REQUEST_METHOD'] == 'PUT') {
        echo '<br>Chegou via PUT<br>';

        $dados_put = file_get_contents( 'php://input');
        parse_str( $dados_put, $_PUT);

        if( !isset($_PUT['id']) OR !isset($_PUT['qtde']) OR !isset($_PUT['tipo'])){
            echo 'erro, dados para lançamento incorretos';
        } else {

            $prod = (isset($_PUT['id'])) ? $_PUT['id'] : '';
            $qtde = (isset($_PUT['qtde'])) ? $_PUT['qtde'] : '';
            $tipo = (isset($_PUT['tipo'])) ? $_PUT['tipo'] : '';
        
            echo "<br>Produto: {$prod} - Tipo: {$tipo} - QTDE: {$qtde}";
            $consulta = "SELECT * FROM estoque where ID = :ID;";
            $filtro   = [ 'ID' => $prod];
            $dados  = Crud::select($consulta, $filtro, true);
            //var_dump( $dados);
            if( isset($dados['status'])){
                if( $dados['status'] == 404){
                    echo '<br>Produto não encontrado !!!';
                }
                //var_dump( $dados);
            } else {
                //inserindo dados
                $saldo_atual = Crud::RecebeSaldoEstoque($prod);
                if( $tipo == '-'){
                    $saldo_novo  = $saldo_atual -$qtde;
                } else {
                    $saldo_novo  = $saldo_atual +$qtde;
                }
                
                $dados1 = [ 'id_produto' => intval($prod), 'saldo_antes' => $saldo_atual, 'saldo_depois' => $saldo_novo, 'qtde' => $qtde, 'tipo' => $tipo ];
                //$dados1 = [ 'id_produto' => 12, 'saldo_antes' => 9.22, 'saldo_depois' => 15.2, 'qtde' => 14.7, 'tipo' => '+'];
                //var_dump( $dados1);
                Crud::setTabela('movimentacao_estoque');
                Crud::insert($dados1);
                //atualizando dados
                Crud::setTabela('estoque');
                $retorno = Crud::update(['saldo_atual' => $saldo_novo], ['ID' => $prod ] );

                //foreach( $_POST as $key => $value){
                //    $campos[$key] = $value;
                //    $retorno = Crud::isCampoTabela('estoque',$key); 
                //}
        

            }
            //
            //var_dump( $dados);
       

        }
    } elseif( $_SERVER[ 'REQUEST_METHOD'] == 'DELETE') {
        echo '<br>Chegou via DELETE<br>';

        echo "Vocë quer excluir o ID {$uri} ?";
    } else {
        echo '<br>Chegou por outra via<br>';
    }

    //echo '<br> <pre>';
    //var_dump( $_SERVER);
