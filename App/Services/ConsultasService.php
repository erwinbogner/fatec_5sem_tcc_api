<?php 

    namespace App\Services;
    use App\Models\Local;
    use App\Models\Tipo;
    use App\Models\User;
    use App\Models\Marca;
    use App\Models\Veiculo;
    use App\Models\Modelo;
    use App\Models\Pessoa;

    class ConsultasService {

        public function get($id = null){
            //var_dump( $id);
            switch( $id) {
                case 'listamarca':
                    return Marca::getLista('id,MarcaVeiculo');
                case 'listamodelo':
                    return Modelo::getLista('id,TipoVeiculo');
                case 'listalocal':
                    return Local::getLista('id,TipoLocal,descricao,cep,estado');
                case 'listatipo':
                     return Tipo::getLista('id,TipoMovto');
                case 'listaveiculo':
                    return Veiculo::getLista('id,apelido,placaVeiculo,nomeVeiculo');
                case 'listapessoa':
                    return Pessoa::getLista('id,nomePessoa,cpf_cnpj,tel1Pessoa,emailPessoa,ufPessoa');
                case 'VerificaUser':                    
                        //return User::VerificaUsuario( $id; $senha);
            }
        }
        public function post($id = null){
            //return $_POST;
            $dados = $_POST;
            if( isset($dados['apelido']) AND isset($dados['senha'])){
                $apelido = $dados['apelido'];
                $senha   = $dados['senha'];
                return User::VerificaUsuario($apelido, $senha);
            } else {
                throw new \Exception( "Dados fornecidos não conferem !");
            }
            //var_dump($id);
            //var_dump($dados);
            //return Local::insert($dados);
        }
        public function update(){

        }
        public function delete(){

        }

    }



//select veic.apelido as apelido, veic.placaVeiculo as placa, veic.nomeVeiculo as veiculo from tabVeiculos veic;


