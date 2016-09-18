<?php

class NaturezaController extends Zend_Controller_Action {
	
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
		Zend_Loader::loadClass ( 'Natureza' );
	}
	
	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;
	
		if (! empty ( $cadastrar )) {
			$natureza = new Natureza();
			$nome = ( string ) ($post->nome);
			$dados = array ('nome' => $nome );
			
			$return = $natureza->insert($dados);
			if ($return) {
				$this->view->message = "Natureza " . $nome . " cadastrada com sucesso";	
			} else {
				$this->view->message = "Erro ao cadastrar o natureza";
			}			
		}
		$this->view->action = "cadastrar";
		$this->view->textButton = "Cadastrar";
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}
	
	public function visualizarAction() {
		$this->view->title = "Processos";
		
		$post = Zend_Registry::get ( 'post' );
		$chave = ( string ) $post->parametrobusca;
		
		$natureza = new Natureza();
		$where = "nome LIKE '%$chave%'";
		
		$naturezas = $natureza->fetchAll ( $where );
		
		$this->view->naturezas = $naturezas;
		$this->view->nmnaturezas = $naturezas->count();

		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
	}
	
	public function editarAction() {
		$post = Zend_Registry::get ( 'post' );		
		$cadastrar = ( string ) $post->cadastrar;
	
		if (! empty ( $cadastrar )) {
			$natureza = new Natureza();
			$id = $post->id;
			$where = "id = " . $id;
			$dados = array ('nome' => ( string ) ($post->nome) );
			
			$return = $natureza->update($dados, $where);
			if ($return) {
				$this->view->message = "Atualização realizada com sucesso";	
			} else {
				$this->view->message = "Erro ao atualizar a natureza";
			}			
		} else {
			$id = $this->getRequest()->getParam("id");
				
			if (empty($id)) {
				$this->_redirect('/');
				exit;
			}
	
			if(!empty($id)) {
				$natureza = new Natureza();		
				$where = "id = " . $id;
				$natureza = $natureza->fetchAll($where);
			}
			
			foreach ($natureza as $dados) {
				$this->view->id = $dados["id"];
				$this->view->nome = $dados["nome"];
			}
		}
		$this->view->action = "editar";
		$this->view->textButton = "Editar";
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
			$natureza = new Natureza();	
			$where = "id = " . $id;
			try {
				$return = $natureza->delete($where);
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
		$this->view->title = "Naturezas";
		
		$natureza = new Natureza();		
		$naturezas = $natureza->fetchAll();
		$this->view->naturezas = $naturezas;
		$this->view->nmnaturezas = $naturezas->count();
		
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "visualizar" );
	}
}

?>