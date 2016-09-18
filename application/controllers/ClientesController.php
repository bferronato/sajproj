<?php

class ClientesController extends Zend_Controller_Action {

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
		Zend_Loader::loadClass ( 'Clientes' );
	}

	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;

		// Caracteres a serem removidos dos campos do formulario
		$caracterRemover = array("/", "(", ")", "-", ".");

		if (! empty ( $cadastrar )) {
			$cliente = new Clientes();
//			$dados = array ('nome' => ( string ) ($post->nome),
//							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
//							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
//							'endereco' => ( string )$post->endereco,
//							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
//							'cidade' => ( string ) $post->cidade,
//							'estado' => ( string ) $post->estado,
//							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
//							'email' => ( string ) $post->email,
//							'descricao' => ( string ) $post->descricao );

			$dados = array ('nome' => html_entity_decode($post->nome),
							'inventariante' => html_entity_decode($post->inventariante),
							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
							'endereco' => html_entity_decode($post->endereco),
							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
							'cidade' => html_entity_decode( $post->cidade),
							'estado' => html_entity_decode( $post->estado),
							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
							'celular' => ( string ) str_replace($caracterRemover, "", $post->celular),
							'email' => ( string ) $post->email,
							'empresa' => ( string ) $post->empresa,
//							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
							'telefone_comercial' => ( string ) str_replace($caracterRemover, "", $post->telefoneComercial),
							'email_comercial' => ( string ) $post->emailComercial,
							'banco' => ( string ) $post->banco,
							'agencia' => html_entity_decode( $post->agencia ),
							'conta' => ( string ) $post->conta,
							'descricao' => html_entity_decode( $post->descricao ) );

			$return = $cliente->insert($dados);
			if ($return) {
				$this->view->message = "Cadastro realizado com sucesso";
			} else {
				$this->view->message = "Erro ao cadastrar o cliente";
			}
		}

		$this->view->title = "Cadastro de Cliente";
		$this->view->subtitulo = "Cadastro de Cliente";
		$this->view->action = "cadastrar";
		$this->view->textButton = "Cadastrar";

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}

	public function editarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;

		if (! empty ( $cadastrar )) {
			$cliente = new Clientes();
			$id = $post->id;
			$where = "id = " . $id;

//			echo '<pre>';
//			print_r($post);
//			die;

//			echo $post->nome;
//			echo '<br>';
//			echo htmlspecialchars_decode($post->nome);
//			echo '<br>';
//			echo html_entity_decode($post->nome);
//			die;

			// Caracteres a serem removidos dos campos do formulario
			$caracterRemover = array("/", "(", ")", "-", ".");

//			$dados = array ('nome' => html_entity_decode($post->nome),
//							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
//							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
//							'endereco' => ( string )$post->endereco,
//							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
//							'cidade' => ( string ) $post->cidade,
//							'estado' => ( string ) $post->estado,
//							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
//							'email' => ( string ) $post->email,
//							'descricao' => ( string ) $post->descricao);

			$dados = array ('nome' => html_entity_decode($post->nome),
							'inventariante' => html_entity_decode($post->inventariante),
							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
							'endereco' => html_entity_decode($post->endereco),
							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
							'cidade' => html_entity_decode( $post->cidade),
							'estado' => html_entity_decode( $post->estado),
							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
							'celular' => ( string ) str_replace($caracterRemover, "", $post->celular),
							'email' => ( string ) $post->email,
							'empresa' => ( string ) $post->empresa,
//							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
							'telefone_comercial' => ( string ) str_replace($caracterRemover, "", $post->telefoneComercial),
							'email_comercial' => ( string ) $post->emailComercial,
							'banco' => ( string ) $post->banco,
							'agencia' => html_entity_decode( $post->agencia ),
							'conta' => ( string ) $post->conta,
							'descricao' => html_entity_decode( $post->descricao ) );

			$return = $cliente->update($dados, $where);
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
				$cliente = new Clientes();
				$where = "id = " . $id;
				$clientes = $cliente->fetchAll($where);
			}

			foreach ($clientes as $dados) {
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
//				$this->view->cnpj = $dados["cnpj"];
				$this->view->telefoneComercial = $dados["telefone_comercial"];
				$this->view->emailComercial = $dados["email_comercial"];
				$this->view->banco = $dados["banco"];
				$this->view->agencia = $dados["agencia"];
				$this->view->conta = $dados["conta"];
				$this->view->descricao = $dados["descricao"];
			}
		}

		$this->view->title = "Edição de Cliente";
		$this->view->subtitulo = "Edição de Cliente";
		$this->view->action = "editar";
		$this->view->textButton = "Editar";

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}

	public function visualizarAction() {
		$this->view->title = "Processos";

		$post = Zend_Registry::get ( 'post' );
		$chave = ( string ) $post->parametrobusca;

		$cliente = new Clientes();
		$where = "nome LIKE '%$chave%'";
		$where .= " or cpf LIKE '%$chave%'";
//		$where .= " or cnpj LIKE '%$chave%'";
		$where .= " or endereco LIKE '%$chave%'";
		$where .= " or estado LIKE '%$chave%'";
		$where .= " or cidade LIKE '%$chave%'";
		$where .= " or telefone LIKE '%$chave%'";
		$where .= " or email LIKE '%$chave%'";

		$order = array("nome ASC");
		$clientes = $cliente->fetchAll ( $where, $order );
		//$processos = $processo->fetchAll ( $where );

		//echo '<pre>';
		//print_r($clientes);

		$this->view->clientes = $clientes;
		$this->view->nmclientes = $clientes->count();

		foreach( $clientes as $key=>$cliente ) {
			$dados[] = 'Nome: ' . $cliente->nome;
			$dados[] = 'Inventariante: ' . $cliente->inventariante;
			$dados[] = 'CPF: ' . $this->_helper->mask('CPF', $cliente->cpf);
			$dados[] = 'Endere\u00e7o: ' . $cliente->endereco;
			$dados[] = 'CEP: ' . $this->_helper->mask('CEP', $cliente->cep);
			$dados[] = 'Cidade: ' . $cliente->cidade;
			$dados[] = 'Estado: ' . $cliente->estado;
			$dados[] = 'Telefone: ' . $this->_helper->mask('FONE', $cliente->telefone);
			$dados[] = 'Celular: ' . $this->_helper->mask('FONE', $cliente->celular);
			$dados[] = 'Email: ' . $cliente->email;
			$dados[] = 'Empresa: ' . $cliente->empresa;
//			$dados[] = 'CNPJ: ' . $this->_helper->mask('CNPJ', $cliente->cnpj);
			$dados[] = 'Tel. Comercial: ' . $this->_helper->mask('FONE', $cliente->telefone_comercial);
			$dados[] = 'Email Comercial: ' . $cliente->email_comercial;
			$dados[] = 'Banco: ' . $cliente->banco;
			$dados[] = 'Ag\u00eancia: ' . $cliente->agencia;
			$dados[] = 'Conta: ' . $cliente->conta;
			$dados[] = 'Descri\u00e7\u00e3o: ' . $cliente->descricao;

			$detalhes[$key] = join( '\\\\n', $dados );
			unset( $dados );
		}

//echo 		$primaryString = $this->_helper->generateRandom(14, true, false, false);

//		// cria uma string rand™mica atravŽs do mŽtodo direct apenas com letras
//        $primaryString = $this->_helper->generateRandom(14, true, false, false);
//
//        // resgata o helper, retornando uma inst‰ncia do helper
//        $helper = $this->_helper->getHelper('generateRandom');
//        // ou $helper = $this->_helper->generateRandom;
//        // cria um password seguro atravŽs de um dos mŽtodos do helper
//        $secondString = $helper->hardPassword();
//        // cria uma string numŽrica chamand o mŽtodo numeric()
//        $thirdString = $helper->numeric(10);


		$this->view->detalhes = $detalhes;

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
			$cliente = new Clientes();
			$where = "id = " . $id;
			try {
				$return = $cliente->delete($where);
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

		$cliente = new Clientes();

		$where = null;
		$order = array("nome ASC");

		$clientes = $cliente->fetchAll( $where, $order );
		$this->view->clientes = $clientes;
		$this->view->nmclientes = $clientes->count();

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "visualizar" );
	}

}

?>