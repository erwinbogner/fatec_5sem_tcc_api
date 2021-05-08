<?php

    //header('Content-Type: application/json');
    require_once '../vendor/autoload.php';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	header("Access-Control-Allow-Headers: Origin, Content-Type");
	//header('Content-Type: application/json');
	header('Access-Control-Max-Age: 86400');
	// header('Content-Type: multipart/form-data');
	//header('Content-Type: application/x-www-form-urlencoded');
	//header('Content-Type: application/json');
    

    // api/users/1
    //var_dump( $_GET[ 'url']);

    if( $_GET['url']){
        $url = explode( '/', $_GET['url'] );

        if( $url[0] === 'api'){
            //...
            //var_dump( $url);
            //array_shift($url);
            $service = 'App\Services\\'.ucfirst( $url[1]).'Service';
            //array_shift($url);

            $method  = strtolower( $_SERVER['REQUEST_METHOD']);
            $campo[]  = $url[2];

            $site['Servico'] = $service;
            $site['Metodo'] = $method;
            $site['id'] = $url[2];

            //echo json_encode( $site);
            //var_dump($site);
            
            try{
                $response = call_user_func_array( array(new $service, $method), $campo);
                //var_dump($response);
                http_response_code(200);
                $data = array('status'=>'Sucesso', 'data'=>$response);
                echo json_encode($data);
                //echo json::encode($data);
                //var_dump( json_encode($data));
                exit;
            } catch (\Exception $e){
                http_response_code(404);
                $data = array('status' => 'ERRO', 'data' => $e->getMessage());
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }

        } else {

        }
        //var_dump( $url);
    } else {
        echo 'vazio';
    }
