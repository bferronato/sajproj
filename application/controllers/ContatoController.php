<?php

class ContatoController extends Zend_Controller_Action {
	
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
		Zend_Loader::loadClass ( 'Contato' );
	}
	
	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;
		
		// Caracteres a serem removidos dos campos do formulario
		$caracterRemover = array("/", "(", ")", "-", ".");
	
		if (! empty ( $cadastrar )) {
			$contato = new Contato();
			$dados = array ('nome' => ( string ) ($post->nome),
							'inventariante' => ( string ) ($post->inventariante),
							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
							'endereco' => ( string )$post->endereco,
							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
							'cidade' => ( string ) $post->cidade,
							'estado' => ( string ) $post->estado,
							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
							'celular' => ( string ) str_replace($caracterRemover, "", $post->celular),
							'email' => ( string ) $post->email,
							'empresa' => ( string ) $post->empresa,
							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
							'telefone_comercial' => ( string ) str_replace($caracterRemover, "", $post->telefoneComercial),
							'email_comercial' => ( string ) $post->emailComercial,
							'banco' => ( string ) $post->banco,
							'agencia' => ( string ) $post->agencia,
							'conta' => ( string ) $post->conta,
							'descricao' => ( string ) $post->descricao );

			$return = $contato->insert($dados);
			if ($return) {
				$this->view->message = "Cadastro realizado com sucesso";
			} else {
				$this->view->message = "Erro ao cadastrar o cliente";
			}
		}
		
		$this->view->title = "Cadastro de Contato";
		$this->view->subtitulo = "Cadastro de Contato";
		$this->view->action = "cadastrar";
		$this->view->textButton = "Cadastrar";
		
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}
	
	public function editarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;

		if (! empty ( $cadastrar )) {
			$contato = new Contato();
			$id = $post->id;
			$where = "id = " . $id;
			
			// Caracteres a serem removidos dos campos do formulario
			$caracterRemover = array("/", "(", ")", "-", ".");
		
			$dados = array ('nome' => ( string ) ($post->nome),
							'inventariante' => ( string ) ($post->inventariante),
							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
							'endereco' => ( string )$post->endereco,
							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
							'cidade' => ( string ) $post->cidade,
							'estado' => ( string ) $post->estado,
							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
							'celular' => ( string ) str_replace($caracterRemover, "", $post->celular),
							'email' => ( string ) $post->email,
							'empresa' => ( string ) $post->empresa,
							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
							'telefone_comercial' => ( string ) str_replace($caracterRemover, "", $post->telefoneComercial),
							'email_comercial' => ( string ) $post->emailComercial,
							'banco' => ( string ) $post->banco,
							'agencia' => ( string ) $post->agencia,
							'conta' => ( string ) $post->conta,
							'descricao' => ( string ) $post->descricao );
			
			$return = $contato->update($dados, $where);
			if ($return) {
				$this->view->message = "Atualização realizada com sucesso";	
			} else {
				$this->view->message = "Erro ao atualizar a cliente";
			}			
		} else {
			$id = $this->getRequest()->getParam("id");
				
			if (empty($id)) {
				$this->_redirect('/');
				exit;
			}
	
			if(!empty($id)) {
				$contato = new Contato();	
				$where = "id = " . $id;
				$contatos = $contato->fetchAll($where);
			}
			
			foreach ($contatos as $dados) {
				$this->view->id = $dados["id"];
				$this->view->nome = $dados["nome"];
				$this->view->inventariante = $dados["inventariante"];
				$this->view->cpf = $dados["cpf"];				
				$this->view->endereco = $dados["endereco"];
				$this->view->cep = $dados["cep"];
				$this->view->cidade = $dados["cidade"];
				$this->view->estado = $dados["estado"];
				$this->view->telefone = $dados["telefone"];
				$this->view->celular = $dados["celular"];
				$this->view->email = $dados["email"];
				$this->view->empresa = $dados["empresa"];
				$this->view->cnpj = $dados["cnpj"];
				$this->view->telefoneComercial = $dados["telefone_comercial"];
				$this->view->emailComercial = $dados["email_comercial"];
				$this->view->banco = $dados["banco"];
				$this->view->agencia = $dados["agencia"];
				$this->view->conta = $dados["conta"];
				$this->view->descricao = $dados["descricao"];
			}
		}
		
		$this->view->title = "Edição de Contatos";
		$this->view->subtitulo = "Edição de Contatos";
		$this->view->action = "editar";
		$this->view->textButton = "Editar";
		
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}
	
	public function visualizarAction() {
		$this->view->title = "Contatos";
		
		$post = Zend_Registry::get ( 'post' );
		$chave = ( string ) $post->parametrobusca;
		
		$contato = new Contato();
		$where = "nome LIKE '%$chave%'";
		$where .= " or cpf LIKE '%$chave%'";
		$where .= " or endereco LIKE '%$chave%'";
		$where .= " or estado LIKE '%$chave%'";
		$where .= " or cidade LIKE '%$chave%'";
		$where .= " or telefone LIKE '%$chave%'";
		$where .= " or email LIKE '%$chave%'";
		$where .= " or empresa LIKE '%$chave%'";
		$where .= " or cnpj LIKE '%$chave%'";
		$where .= " or telefone_comercial LIKE '%$chave%'";
		$where .= " or email_comercial LIKE '%$chave%'";
		$where .= " or banco LIKE '%$chave%'";
		$where .= " or agencia LIKE '%$chave%'";
		$where .= " or conta LIKE '%$chave%'";

		$order = array("nome ASC");
		$contatos = $contato->fetchAll ( $where, $order );
		//$processos = $processo->fetchAll ( $where );
		
		$this->view->contatos = $contatos;
		$this->view->nmcontatos = $contatos->count();

		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
	}
	
	function excluirAction() {
		// Id do processo a excluir
		$id = $this->getRequest()->getParam("id");
			
		if (empty($id)) {
			$this->_redirect('/');
			exit;
		}

		if(!empty($id)) {
			$contato = new Contato();
			$where = "id = " . $id;
			try {
				$return = $contato->delete($where);
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
		
		$contato = new Contato();
		$contatos = $contato->fetchAll();
		$this->view->contatos = $contatos;
		$this->view->nmcontatos = $contatos->count();
		
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "visualizar" );
	}
}

?>