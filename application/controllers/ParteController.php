<?php

class ParteController extends Zend_Controller_Action {

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
		Zend_Loader::loadClass ( 'Parte' );
		Zend_Loader::loadClass ( 'Cliente' );
		Zend_Loader::loadClass ( 'Processo' );
	}

	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;

		if (! empty ( $cadastrar )) {
			$parte = new Parte();
			$dados = array ('tb_cliente_id' => $post->idcliente,
							'tb_processo_id' => $post->idprocesso,
							'nome_parte' => $post->nome,
							'numero_matricula' => $post->matricula,
							'cpf' => ( string ) $post->cpf,
							'rg' => ( string ) $post->rg,
							'banco' => ( string ) $post->banco,
							'agencia' => ( string ) $post->agencia,
							'conta' => ( string ) $post->conta,
			);

			$return = $parte->insert($dados);
			if ($return) {
				$this->view->message = "Parte cadastrada com sucesso";
			} else {
				$this->view->message = "Erro ao cadastrar parte";
			}
		}

		$processo = new Processo();
		$processos = $processo->fetchAll();
		$this->view->processos = $processos;

		$cliente = new Cliente();
		$clientes = $cliente->fetchAll();
		$this->view->clientes = $clientes;

		$this->view->title = "Cadastro de Parte";
		$this->view->subtitulo = "Cadastro de Parte";
		$this->view->action = "cadastrar";
		$this->view->textButton = "Cadastrar";

		$this->view->postidprocesso = $post->idprocesso;
		$this->view->postidcliente = $post->idcliente;

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}

	public function visualizarAction() {
		$this->view->title = "Visualização de Partes";

		$post = Zend_Registry::get ( 'post' );
		$chave = ( string ) $post->parametrobusca;



		/*$actionTable = new Action ( );
		$actionQuery = $actionTable->select ()->setIntegrityCheck ( false )->// allows joins
from ( $actionTable )->join ( 'user', 'user.id = action.user_id' );
		$joinedRowset = $actionTable->fetchAll ( $actionQuery );
		foreach ( $joinedRowset as $joinedRow ) {
			print_r ( $joinedRow->toArray () );
		}*/
		/*$where1 = "nome LIKE '%$chave%'";
		$where1.= "or cpf LIKE '%$chave%'";
		$where1.= "or cnpj LIKE '%$chave%'";
		$where1.= "or endereco LIKE '%$chave%'";
		$where1.= "or cidade LIKE '%$chave%'";
		$where1.= "or estado LIKE '%$chave%'";
		$where1.= "or telefone LIKE '%$chave%'";
		$where1.= "or email LIKE '%$chave%'";
		$cliente = new Cliente();
		$clientes = $cliente->fetchAll($where1);

		$where1 = "";
		//foreach ($clientes as $cliente) {
		//	$where1 .= " or tb_cliente_id LIKE '%$cliente->id%'";
		//}
		// Retira o 'or' inicial
		$where1 = substr($where1, 3);*/

		//$where = $where1 . " or nome_parte LIKE '%$chave%'";


		$where = "nome_parte LIKE '%$chave%'";
		$where .= " or numero_matricula LIKE '%$chave%'";
		$where .= " or cpf LIKE '%$chave%'";
		$where .= " or rg LIKE '%$chave%'";


		//SELECT * FROM tb_parte pa join tb_processo pro on pa.tb_processo_id = pro.id where pro.numero_processo = 023010278349

		$parte = new Parte();
		$order = 'nome_parte ASC';
		$partes = $parte->fetchAll ( $where, $order );

		/*$bugsTable = new Bugs();
		$bugsRowset = $bugsTable->fetchAll(array('bug_status = ?' => 'NEW'));
		$bug1 = $partes->current();

		$reporter = $bug1->findParentRow('Accounts');*/


		$this->view->partes = $partes;
		$this->view->nmpartes = $partes->count();

		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
	}

	public function editarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;

		if (! empty ( $cadastrar )) {
			$parte = new Parte();
			$id = $post->id;
			$where = "id = " . $id;
			$dados = array ('tb_cliente_id' => $post->idcliente,
							'tb_processo_id' => $post->idprocesso,
							'nome_parte' => $post->nome,
							'numero_matricula' => $post->matricula,
							'cpf' => ( string ) $post->cpf,
							'rg' => ( string ) $post->rg,
							'banco' => ( string ) $post->banco,
							'agencia' => ( string ) $post->agencia,
							'conta' => ( string ) $post->conta,
			);

			$return = $parte->update($dados, $where);
			if ($return) {
				$this->view->message = "Atualização realizada com sucesso";
			} else {
				$this->view->message = "Erro ao atualizar a parte";
			}
		} else {
			$id = $this->getRequest()->getParam("id");

			if (empty($id)) {
				$this->_redirect('/');
				exit;
			}

			if(!empty($id)) {
				$parte = new Parte();
				$where = "id = " . $id;
				$partes = $parte->fetchAll($where);
			}



			foreach ($partes as $dados) {
				$this->view->id = $dados["id"];
				$this->view->idCliente = $dados["tb_cliente_id"];
				$this->view->idProcesso = $dados["tb_processo_id"];
				$this->view->nome = $dados["nome_parte"];
				$this->view->matricula = $dados["numero_matricula"];
				$this->view->cpf = $dados["cpf"];
				$this->view->rg = $dados["rg"];
				$this->view->banco = $dados["banco"];
				$this->view->agencia = $dados["agencia"];
				$this->view->conta = $dados["conta"];
			}
		}

		$processo = new Processo();
		$processos = $processo->fetchAll();
		$this->view->processos = $processos;

		$cliente = new Cliente();
		$clientes = $cliente->fetchAll();
		$this->view->clientes = $clientes;

		$this->view->title = "Edição de Parte";
		$this->view->subtitulo = "Edição de Parte";
		$this->view->action = "editar";
		$this->view->textButton = "Editar";

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}

	function excluirAction() {
		// Id da parte a excluir
		$id = $this->getRequest()->getParam("id");

		if (empty($id)) {
			$this->_redirect('/');
		}

		if(!empty($id)) {
			$parte = new Parte();
			$where = "id = " . $id;

			try {
				$return = $parte->delete($where);
			} catch (Exception $e) {
				$return = false;
			}
			if ($return) {
				$this->view->message = "Exclusão realizada com sucesso";
			} else {
				$this->view->message = "Não foi possível excluir o registro selecionado.<br />";
				$this->view->message.= "O registro selecionado pode estar sendo utilizado por algum processo.";
			}
		}

		$this->view->title = "Partes";

		$parte = new Parte();
		$partes = $parte->fetchAll();
		$this->view->partes = $partes;
		$this->view->nmpartes = $partes->count();

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "visualizar" );
	}
}

?>