<?php

class VaraController extends Zend_Controller_Action {
	
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
		Zend_Loader::loadClass ( 'Vara' );
	}
	
	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;
	
		if (! empty ( $cadastrar )) {
			$vara = new Vara();
			$nome = ( string ) ($post->nome);
			$dados = array ('nome' => $nome );
			
			$return = $vara->insert($dados);
			if ($return) {
				$this->view->message = "Vara " . $nome . " cadastrada com sucesso";	
			} else {
				$this->view->message = "Erro ao cadastrar vara";
			}			
		}
		$this->view->action = "cadastrar";
		$this->view->textButton = "Cadastrar";
		$this->view->subtitulo = "Cadastro de nova vara";
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}
	
	public function visualizarAction() {
		$this->view->title = "Varas";
		
		$post = Zend_Registry::get ( 'post' );
		$chave = ( string ) $post->parametrobusca;
		
		$vara = new Vara();
		$where = "nome LIKE '%$chave%'";
		
		$varas = $vara->fetchAll ( $where );
		
		$this->view->varas = $varas;
		$this->view->nmvaras = $varas->count();

		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
	}
	
	public function editarAction() {
		$post = Zend_Registry::get ( 'post' );		
		$cadastrar = ( string ) $post->cadastrar;
	
		if (! empty ( $cadastrar )) {
			$vara = new Vara();
			$id = $post->id;
			$where = "id = " . $id;
			$dados = array ('nome' => ( string ) ($post->nome) );
			
			$return = $vara->update($dados, $where);
			if ($return) {
				$this->view->message = "Atualização realizada com sucesso";	
			} else {
				$this->view->message = "Erro ao atualizar a vara";
			}			
		} else {
			$id = $this->getRequest()->getParam("id");
				
			if (empty($id)) {
				$this->_redirect('/');
				exit;
			}
	
			if(!empty($id)) {
				$vara = new Vara();	
				$where = "id = " . $id;
				$vara = $vara->fetchAll($where);
			}
			
			foreach ($vara as $dados) {
				$this->view->id = $dados["id"];
				$this->view->nome = $dados["nome"];
			}
		}
		$this->view->action = "editar";
		$this->view->textButton = "Editar";
		$this->view->subtitulo = "Edição de varas";
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}
	
	function excluirAction() {
		// Id do processo a excluir
		$id = $this->getRequest()->getParam("id");
			
		if (empty($id)) {
			$this->_redirect('/');
			exit;
		}

		if(!empty($id)) {
			$vara = new Vara();
			$where = "id = " . $id;
			
			try {
				$return = $vara->delete($where);
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
		$this->view->title = "Varas";
		
		$vara = new Vara();
		$varas = $vara->fetchAll();
		$this->view->varas = $varas;
		$this->view->nmvaras = $varas->count();
		
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "visualizar" );
	}
}

?>