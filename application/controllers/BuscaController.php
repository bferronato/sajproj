<?php

class BuscaController extends Zend_Controller_Action {

	function preDispatch() {
		$auth = Zend_Auth::getInstance ();
		if (! $auth->hasIdentity ()) {
			$this->_redirect ( 'auth/login' );
		}

		// Configura o timeout para expirar a sessao
		$authNamespace = new Zend_Session_Namespace('auth');
        // clear the identity of a user who has not accessed a controller for
        // longer than a timeout period.
        if (isset($authNamespace->timeout) && time() > $authNamespace->timeout) {
            Zend_Auth::getInstance()->clearIdentity();
        } else {
            // User is still active - update the timeout time.
            $authNamespace->timeout = time() + 1800;
            // Store the request URI so that an authentication after a timeout
            // can be directed back to the pre-timeout display.  The base URL needs to
            // be stripped off of the request URI to function properly.
            $authNamespace->requestUri = substr($this->_request->getRequestUri(),
                strlen(Zend_Controller_Front::getInstance()->getBaseUrl()));
        }
        // If the user has no identity here, there has either been a time out or the user has
        // not logged in yet.
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('auth/login');
        }

	}

	function init() {
		Zend_Loader::loadClass ( 'Processo' );
		Zend_Loader::loadClass ( 'Cliente' );
		Zend_Loader::loadClass ( 'Parte' );
		Zend_Loader::loadClass ( 'Natureza' );
		Zend_Loader::loadClass ( 'Comarca' );
		Zend_Loader::loadClass ( 'Vara' );
		//Zend_Loader::loadClass ( 'Contato' );
	}

	public function buscarAction() {
		$this->view->title = "Processos";
		$date = new Zend_Date();
		$this->view->datahora = $date;

		$post = Zend_Registry::get ( 'post' );
		$chave = ( string ) $post->parametrobusca;

		$pesquisaPor = ( string ) $post->pesquisaPor;
		$this->view->pesquisaPor = $pesquisaPor;

		mail( 'bferronato@gmail.com', 'Busca realizada', "Pesquisa por: {$pesquisaPor} Valor: {$chave}"  );

		switch ($pesquisaPor) {
			case 'numeroProcesso':
				$where = "numero_processo LIKE '%$chave%'";
				$processo = new Processo();
				$processos = $processo->fetchAll( $where );
				$this->view->processos = $processos;
				$this->view->nmprocessos = $processos->count();
				break;
			case 'nomeCliente':
				$where = "nome LIKE '%$chave%' or matricula LIKE '%$chave%'";
				$cliente = new Cliente();
				$clientes = $cliente->fetchAll( $where );
				$this->view->clientes = $clientes;
				$this->view->nmclientes = $clientes->count();
				break;
			case 'nomeParte':
				if ( !empty( $chave )) {
					$where = "nome_parte LIKE '%$chave%'";
					$parte = new Parte();
					$order = "nome_parte asc";
					$partes = $parte->fetchAll( $where, $order );
					$this->view->partes = $partes;
					$this->view->nmpartes = $partes->count();
				} else {
					$this->view->partes = array();
					$this->view->nmpartes = 0;
					$this->view->message = 'Digite um parâmetro de busca';
				}
				break;
			default:
					$processo = new Processo();
					$processos = $processo->fetchAll();
					$this->view->nmprocessos = $processos->count();

					$cliente = new Cliente();
					$clientes = $cliente->fetchAll();
					$this->view->nmclientes = $clientes->count();

					$parte = new Parte();
					$partes = $parte->fetchAll();
					$this->view->nmpartes = $partes->count();

					$natureza = new Natureza();
					$naturezas = $natureza->fetchAll();
					$this->view->nmnaturezas = $naturezas->count();

					$comarca = new Comarca();
					$comarcas = $comarca->fetchAll();
					$this->view->nmcomarcas = $comarcas->count();

					$vara = new Vara();
					$varas = $vara->fetchAll();
					$this->view->nmvaras = $varas->count();

//					$contatos = new Contato();
//					$contatos = $contatos->fetchAll();
//					$this->view->nmcontatos = $contatos->count();
		}

		//$cliente = new Cliente();
		/*$where = "nome LIKE '%$chave%'";
		$where .= " or cpf LIKE '%$chave%'";
		$where .= " or cnpj LIKE '%$chave%'";
		$where .= " or endereco LIKE '%$chave%'";
		$where .= " or estado LIKE '%$chave%'";
		$where .= " or cidade LIKE '%$chave%'";
		$where .= " or telefone LIKE '%$chave%'";
		$where .= " or email LIKE '%$chave%'";*/

		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "buscar" );
	}

}

?>