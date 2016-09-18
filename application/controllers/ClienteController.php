<?php

class ClienteController extends Zend_Controller_Action {

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
		Zend_Loader::loadClass ( 'Cliente' );
	}

	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;

		// Caracteres a serem removidos dos campos do formulario
		$caracterRemover = array("/", "(", ")", "-", ".");

		if( !empty( $cadastrar ) ) {
			$cliente = new Cliente();

			$dados = array ('nome' => html_entity_decode($post->nome),
							'data_nascimento' => $this->_helper->data->isoFormat( $post->dataNascimento ),
							'matricula' => html_entity_decode($post->matricula),
							'funcao' => html_entity_decode($post->funcao),
							'inventariante' => html_entity_decode($post->inventariante),
							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
							'rg' => ( string ) str_replace($caracterRemover, "", $post->rg),
							'endereco' => html_entity_decode($post->endereco),
							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
							'cidade' => html_entity_decode( $post->cidade),
							'estado' => html_entity_decode( $post->estado),
							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
							'celular' => ( string ) str_replace($caracterRemover, "", $post->celular),
							'email' => ( string ) $post->email,
							'empresa' => ( string ) html_entity_decode($post->empresa),
                            'data_cadastro' => date('Y-m-d'),
//							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
							'telefone_comercial' => ( string ) str_replace($caracterRemover, "", $post->telefoneComercial),
							'email_comercial' => ( string ) $post->emailComercial,
							'banco' => ( string ) html_entity_decode($post->banco),
							'agencia' => html_entity_decode( $post->agencia ),
							'conta' => ( string ) html_entity_decode($post->conta),
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
			$cliente = new Cliente();
			$id = $post->id;
			$where = "id = " . $id;

			// Caracteres a serem removidos dos campos do formulario
			$caracterRemover = array("/", "(", ")", "-", ".");

			$dados = array ('nome' => ( string ) utf8_decode(html_entity_decode($post->nome)),
							'data_nascimento' => $this->_helper->data->isoFormat( $post->dataNascimento ),
							'matricula' => (string) utf8_decode(html_entity_decode($post->matricula)),
							'funcao' => html_entity_decode($post->funcao),
							'inventariante' => utf8_decode(html_entity_decode($post->inventariante)),
							'cpf' => ( string ) str_replace($caracterRemover, "", $post->cpf),
							'rg' => ( string ) str_replace($caracterRemover, "", $post->rg),
							'endereco' => ( string ) utf8_decode(html_entity_decode($post->endereco)),
							'cep' => ( string ) str_replace($caracterRemover, "", $post->cep),
							'cidade' => ( string ) utf8_decode(html_entity_decode($post->cidade)),
							'estado' => html_entity_decode( $post->estado),
							'telefone' => ( string ) str_replace($caracterRemover, "", $post->telefone),
							'celular' => ( string ) str_replace($caracterRemover, "", $post->celular),
							'email' => ( string ) $post->email,
							'empresa' => ( string ) utf8_decode(html_entity_decode($post->empresa)),
                            'data_cadastro' => date('Y-m-d'),
//							'cnpj' => ( string ) str_replace($caracterRemover, "", $post->cnpj),
							'telefone_comercial' => ( string ) str_replace($caracterRemover, "", $post->telefoneComercial),
							'email_comercial' => ( string ) $post->emailComercial,
							'banco' => ( string ) utf8_decode(html_entity_decode($post->banco)),
							'agencia' => (string) utf8_decode(html_entity_decode( $post->agencia )),
							'conta' => ( string ) utf8_decode(html_entity_decode($post->conta)),
							'descricao' => ( string ) utf8_decode(html_entity_decode($post->descricao )));

			$return = $cliente->update($dados, $where);
			if ($return) {
				$this->view->message = "Atualização realizada com sucesso";
			} else {
				$this->view->message = "Erro ao atualizar o cliente";
			}
		} else {
			$id = $this->getRequest()->getParam("id");

			if (empty($id)) {
				$this->_redirect('/');
				exit;
			}

			if(!empty($id)) {
				$cliente = new Cliente();
				$where = "id = " . $id;
				$clientes = $cliente->fetchAll($where);
			}

			foreach ($clientes as $dados) {
				$this->view->id = $dados["id"];
				$this->view->nome = utf8_encode($dados["nome"]);
				$this->view->dataNascimento = $this->_helper->data->ptFormat( $dados["data_nascimento"] );
				$this->view->matricula = utf8_decode(html_entity_decode($dados["matricula"]));
				$this->view->funcao = $dados["funcao"];
				$this->view->inventariante = utf8_decode(htmlentities( $dados["inventariante"]));
				$this->view->cpf = $dados["cpf"];
				$this->view->rg = $dados["rg"];
				$this->view->endereco = utf8_encode($dados["endereco"]);
				$this->view->cep = $dados["cep"];
				$this->view->cidade = utf8_encode($dados["cidade"]);
				$this->view->estado = $dados["estado"];
				$this->view->telefone = $dados["telefone"];
				$this->view->celular = $dados["celular"];
				$this->view->email = $dados["email"];
				$this->view->empresa = utf8_decode(htmlentities($dados["empresa"]));
//				$this->view->cnpj = $dados["cnpj"];
				$this->view->telefoneComercial = $dados["telefone_comercial"];
				$this->view->emailComercial = $dados["email_comercial"];
				$this->view->banco = utf8_decode(html_entity_decode($dados["banco"]));
				$this->view->agencia = utf8_decode(html_entity_decode($dados["agencia"]));
				$this->view->conta = utf8_decode(html_entity_decode($dados["conta"]));
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

		$cliente = new Cliente();
		$where = "nome LIKE '%$chave%'";
		$where .= " or cpf LIKE '%$chave%'";
//		$where .= " or cnpj LIKE '%$chave%'";
		$where .= " or endereco LIKE '%$chave%'";
		$where .= " or estado LIKE '%$chave%'";
		$where .= " or cidade LIKE '%$chave%'";
		$where .= " or telefone LIKE '%$chave%'";
		$where .= " or email LIKE '%$chave%'";

		// Verificar erro no banco
		$limit = 466;
		$limit = 0;

		$order = array("nome ASC");
		$clientes = $cliente->fetchAll ( $where, $order, $limit );

		$this->view->clientes = $clientes;
		$this->view->nmclientes = $clientes->count();


//		{'recordsReturned':10,
//		'totalRecords':10,
//		'startIndex':0,'sort':null,'dir':'asc','pageSize':504,'records':[



		foreach( $clientes as $key=>$cliente ) {
			$dados[] = "Nome: " . utf8_decode(htmlentities($cliente->nome));
			$dados[] = "Nascimento: " . $this->_helper->data->ptFormat($cliente->data_nascimento);
			$dados[] = "Matr\u00EDcula: " . utf8_decode(htmlentities($cliente->matricula));
			$dados[] = "Inventariante: " . utf8_decode(htmlentities($cliente->inventariante));
			$dados[] = "CPF: " . $this->_helper->mask("CPF", $cliente->cpf);
			//$dados[] = "Endere\u00e7o: ". $cliente->endereco;
			$dados[] = "CEP: " . $this->_helper->mask("CEP", $cliente->cep);
			//$dados[] = "Cidade: " . addslashes(addslashes($cliente->cidade));
			$dados[] = "Estado: " . $cliente->estado;
			$dados[] = "Telefone: " . $this->_helper->mask("FONE", $cliente->telefone);
			$dados[] = "Celular: " . $this->_helper->mask("FONE", $cliente->celular);
			$dados[] = "Email: " . $cliente->email;
			$dados[] = "Empresa: " . utf8_decode(htmlentities($cliente->empresa));
			$dados[] = "Tel. Comercial: " . $this->_helper->mask("FONE", $cliente->telefone_comercial);
			$dados[] = "Email Comercial: " . $cliente->email_comercial;
			$dados[] = "Banco: " . utf8_decode(htmlentities($cliente->banco));
			$dados[] = "Ag\u00eancia: " . utf8_decode(htmlentities($cliente->agencia));
			$dados[] = "Conta: " . utf8_decode(htmlentities($cliente->conta));
			$dados[] = "Descri\u00e7\u00e3o: " . $cliente->agencia;
			$dados[] = "Cadastro/Atualiza\u00e7\u00e3o: " . implode("/",array_reverse(explode("-",$cliente->data_cadastro)));

			$obj = new stdClass();
			//'<a href=\"javascript:void(0);\" onclick=\"javascript:confirmExclusao(\'/sajproj/cliente/excluir/id/\' + " . $cliente->id . ")\"><img src=\"/sajproj/images/excluir.gif\" border=\"0\"></a>
			//&nbsp;<a href=\"/sajproj/cliente/editar/id/" . $cliente->id . "\"><img src=\"/sajproj/images/editar.gif\" border=\"0\"></a>
			//&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:alert(\'" . $this->detalhes[$key] . "\')\"><img src=\"/sajproj/images/visualizar.gif\" title=\"Detalhes\" border=\"0\"></a>',
			$acao = "<a href=\"javascript:void(0);\" onclick=\"javascript:confirmExclusao('/sajproj/cliente/excluir/id/' + " . $cliente->id . ")\"><img src=\"/sajproj/images/excluir.gif\" border=\"0\"></a>"
				  . "&nbsp;<a href=\"/sajproj/cliente/editar/id/" . $cliente->id . "\"><img src=\"/sajproj/images/editar.gif\" border=\"0\"></a>"
				  . "&nbsp;<a onclick=\"javascript:alert('" . join( "\\n", $dados ) . "')\"><img src=\"/sajproj/images/visualizar.gif\" title=\"Detalhes\" border=\"0\"></a>";

				  if(empty($acao)) {
				      $acao = 'okk';
				  }
			$obj->acao = $acao;
			$obj->nome = utf8_decode(htmlentities($cliente->nome));
			$obj->data_nascimento = $this->_helper->data->ptFormat($cliente->data_nascimento);
			$obj->matricula = utf8_decode(htmlentities($cliente->matricula));
			$obj->inventariante = utf8_decode(htmlentities($cliente->inventariante));
			$obj->cpf = $this->_helper->mask("CPF", $cliente->cpf);
			$obj->endereco = utf8_decode(htmlentities($cliente->endereco));
			$obj->cep = $this->_helper->mask("CEP", $cliente->cep);
			$obj->cidade = $cliente->cidade;
			$obj->estado = $cliente->estado;
			$obj->telefone = $this->_helper->mask("FONE", $cliente->telefone);
			$obj->celular = $this->_helper->mask("FONE", $cliente->celular);
			$obj->email = $cliente->email;
			$obj->empresa = utf8_decode(htmlentities($cliente->empresa));
//			$obj->cnpj = $this->_helper->mask("CNPJ", $cliente->cnpj);
			$obj->telefone_comercial = $this->_helper->mask("FONE", $cliente->telefone_comercial);
			$obj->email_comercial = $cliente->email_comercial;
			$obj->banco = utf8_decode(htmlentities($cliente->banco));
			$obj->conta = utf8_decode(htmlentities($cliente->conta));
			$obj->agencia = utf8_decode(htmlentities($cliente->agencia));

//		{key:"nome", label:"Nome", sortable:true},
//				{key:"inventariante", label:"Inventariante", sortable:true},
//				{key:"cpf", label:"CPF", sortable:true},
//				{key:"celular", label:"Celular", sortable:true},
//				{key:"telefone", label:"Telefone", sortable:true},
//				{key:"telefone_comercial", label:"Telefone Comercial", sortable:true},
//				{key:"email", label:"Email", sortable:true}

			//$detalhes[$key] = join( "\\\\n", $dados );
			$objs[] = $obj;

			unset( $dados );
		}

		//$this->view->detalhes = $detalhes;

		$json = array("recordsReturned"=>"10",
		               "totalRecords"=>"10",
		                "startIndex"=>"0",
		                "sort"=>"",
		                "dir"=>"asc",
		                "pageSize"=>"504",
		                "records"=>$objs);

//		echo json_encode($json);
//		die;

		$this->view->json2 = json_encode($json);

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
			$cliente = new Cliente();
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

		$cliente = new Cliente();

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