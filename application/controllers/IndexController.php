<?php

class IndexController extends Zend_Controller_Action {
	
	function preDispatch() {
		$auth = Zend_Auth::getInstance ();
		if (! $auth->hasIdentity ()) {
			$this->_redirect ( '/auth/login' );
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
            $this->_redirect('/auth/login');
        }
	}
	
	function init() {
		Zend_Loader::loadClass ( 'Processo' );
		Zend_Loader::loadClass ( 'Cliente' );
		Zend_Loader::loadClass ( 'Natureza' );
		Zend_Loader::loadClass ( 'Comarca' );
		Zend_Loader::loadClass ( 'Vara' );
		Zend_Loader::loadClass ( 'Parte' );
	}
	
	public function indexAction() {
		$this->view->title = "Visualização de Processos";
		
		$post = Zend_Registry::get ( 'post' );
		//$post = $_POST['parametrobusca'];
		$chave = $this->removerAcento2($post->parametrobusca);
		
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
		foreach ($clientes as $cliente) {
			$where1 .= " or tb_cliente_id LIKE '%$cliente->id%'";
		}
		// Retira o 'or' inicial
		$where1 = substr($where1, 3);
		
		$where2 = "nome LIKE '%$chave%'";
		$natureza = new Natureza();
		$naturezas = $natureza->fetchAll($where2);
		
		$where2 = "";
		foreach ($naturezas as $natureza) {
			$where2 .= " or tb_natureza_id LIKE '%$natureza->id%'";
		}
		// Retira o 'or' inicial
		$where2 =  substr($where2, 3);
		
		
		$where3 = "nome LIKE '%$chave%'";		
		$vara = new Vara();
		$varas = $vara->fetchAll($where3);
		
		$where3 = "";
		foreach ($varas as $vara) {
			$where3 .= " or tb_comarca_id LIKE '%$vara->id%'";
		}
		// Retira o 'or' inicial
		$where3 =  substr($where3, 3);
		
		$where4 = "nome LIKE '%$chave%'";		
		$comarca = new Comarca();
		$comarcas = $comarca->fetchAll($where4);
		
		$where4 = "";
		foreach ($comarcas as $comarca) {
			$where4 .= " or tb_comarca_id LIKE '%$comarca->id%'";
		}
		// Retira o 'or' inicial
		$where4 =  substr($where4, 3);
		
		// Monta as subquerys
		$where1 = ( !empty($where1) ? " or " .$where1 : "");
		$where2 = ( !empty($where2) ? " or " .$where2 : "");
		$where3 = ( !empty($where3) ? " or " .$where3 : "");
		$where4 = ( !empty($where4) ? " or " .$where4 : "");
		
		// Concatena as subquerys
		$where = $where1 . $where2 . $where3 . $where4;
		
		$where .= " or numero_processo LIKE '%$chave%'";
		//SELECT FROM_UNIXTIME(timestamp, '%Y-%m-%d') AS day
		$where .= " or FROM_UNIXTIME(data_distribuicao, '%d/%m/%Y' ) LIKE '%$chave%'";
		$where .= " or FROM_UNIXTIME(data_encerramento, '%d/%m/%Y' ) LIKE '%$chave%'";
		
		// Retira o 'or' inicial
		$where = substr($where, 3);*/
		
		$processo = new Processo();
		$where = "numero_processo LIKE '%$chave%'";
		/*$order = array( $processo->getReference("Cliente") );// getParentTables());
		echo "<pre>";
		print_r($order);// $order;
		die;*/
		// findParentCliente()->nome . " ASC ");
		$processos = $processo->fetchAll( $where );
		$this->view->processos = $processos;
		$this->view->nmprocessos = $processos->count();
				
		//$processo = new Processo ();
		//if (empty($where)) {
		//	$processos = $processo->fetchAll();
		//} else {
		//	$processos = $processo->fetchAll( $where );
		//}
		
		//$this->view->processos = $processos;
		//$this->view->nmprocessos = $processos->count();

		$date = new Zend_Date();
		$this->view->message = $date;
		
		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
	}
	
	function removerAcento2($str){
		$from = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç';
		$to = 'AAAAEEIOOOUUCaaaaeeiooouuc';
		return strtr($str, $from, $to);
	}
	
 	function removeacentos ($var){
       $ACENTOS   = array("À","Á","Â","Ã","à","á","â","ã");
       $SEMACENTOS= array("A","A","A","A","a","a","a","a");
       $var=str_replace($ACENTOS,$SEMACENTOS, $var);
      
       $ACENTOS   = array("È","É","Ê","Ë","è","é","ê","ë");
       $SEMACENTOS= array("E","E","E","E","e","e","e","e");
       $var=str_replace($ACENTOS,$SEMACENTOS, $var);
       $ACENTOS   = array("Ì","Í","Î","Ï","ì","í","î","ï");
       $SEMACENTOS= array("I","I","I","I","i","i","i","i");
       $var=str_replace($ACENTOS,$SEMACENTOS, $var);
      
       $ACENTOS   = array("Ò","Ó","Ô","Ö","Õ","ò","ó","ô","ö","õ");
       $SEMACENTOS= array("O","O","O","O","O","o","o","o","o","o");
       $var=str_replace($ACENTOS,$SEMACENTOS, $var);
     
       $ACENTOS   = array("Ù","Ú","Û","Ü","ú","ù","ü","û");
       $SEMACENTOS= array("U","U","U","U","u","u","u","u");
       $var=str_replace($ACENTOS,$SEMACENTOS, $var);
       
       $ACENTOS   = array("Ç","ç","ª","º","°");
       $SEMACENTOS= array("C","c","a.","o.","o.");
       $var=str_replace($ACENTOS,$SEMACENTOS, $var);      

       return $var;
	}
    
	public function cadastrarAction() {
		$post = Zend_Registry::get ( 'post' );
		$cadastrar = ( string ) $post->cadastrar;
		
		$nmprocesso = $post->nmprocesso;
		
		$where = "numero_processo = '$nmprocesso'";
		$processo = new Processo();
		$processos = $processo->fetchAll( $where );
		$qtdd = $processos->count();
		
		if ( $qtdd >= 1 ) {
			$this->view->message = "O número do processo já existe";
		}
		
		$return = "";
		if ( (!empty ( $cadastrar )) and ( $qtdd == 0 ) ) {
			//$processo = new Processo ( );
			
			// Formata as datas para cadastro
			if ( !empty($post->dtdistribuicao) ) {
				$dtdistribuicao = explode( "/", $post->dtdistribuicao );
				$dtdistribuicao = $dtdistribuicao[2] . '-' . $dtdistribuicao[1] . '-' . $dtdistribuicao[0] . ' ' . date("H:i:s");
			} else {
				$dtdistribuicao = null;
			}
			if ( !empty($post->dtencerramento) ) {
				$dtencerramento = explode( "/", $post->dtencerramento );
				$dtencerramento = $dtencerramento[2] . '-' . $dtencerramento[1] . '-' . $dtencerramento[0] . ' ' . date("H:i:s");
			} else {
				$dtencerramento = null;
			}
			
			$dados = array ('numero_processo' => ( string ) $post->nmprocesso, 
							'data_distribuicao' => $dtdistribuicao, 
							'data_encerramento' => $dtencerramento,
							'tb_cliente_id' => ( string ) $post->idcliente, 
							'tb_natureza_id' => ( string ) $post->idnatureza, 
							'tb_comarca_id' => ( string ) $post->idcomarca, 
							'tb_vara_id' => ( string ) $post->idvara );
			
			$return = $processo->insert ( $dados );
			
			if ($return) {
				$this->view->message = "Cadastro realizado com sucesso";	
			} else {
				$this->view->message = "Erro ao cadastrar o processo";
			}
		}
		
		$cliente = new Cliente();
		$order = array("nome ASC");
		$clientes = $cliente->fetchAll(null, $order);
		$this->view->clientes = $clientes;
		
		$natureza = new Natureza();		
		$naturezas = $natureza->fetchAll();
		$this->view->naturezas = $naturezas;
		
		$comarca = new Comarca();		
		$comarcas = $comarca->fetchAll();
		$this->view->comarcas = $comarcas;
		
		$vara = new Vara();		
		$varas = $vara->fetchAll();
		$this->view->varas = $varas;

		$this->view->title = "Cadastrar Processo";
		$this->view->action = "cadastrar";
		$this->view->textButton = "Cadastrar";
		$this->view->subtitulo = "Cadastro de novo processo";

		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );
	}
	
	public function editarAction() {		
		$post = Zend_Registry::get ( 'post' );		
		$cadastrar = ( string ) $post->cadastrar;
	
		if ( !empty ( $cadastrar )) {
			$processo = new Processo();
			
			// Formata as datas para cadastro
			if ( !empty($post->dtdistribuicao) ) {
				$dtdistribuicao = explode( "/", $post->dtdistribuicao );
				$dtdistribuicao = $dtdistribuicao[2] . '-' . $dtdistribuicao[1] . '-' . $dtdistribuicao[0] . ' ' . date("H:i:s");
			} else {
				$dtdistribuicao = null;
			}
			if ( !empty($post->dtencerramento) ) {
				$dtencerramento = explode( "/", $post->dtencerramento );
				$dtencerramento = $dtencerramento[2] . '-' . $dtencerramento[1] . '-' . $dtencerramento[0] . ' ' . date("H:i:s");
			} else {
				$dtencerramento = null;
			}
						
			$id = $post->id;
			$where = "id = " . $id;
			$dados = array ('tb_cliente_id' => $post->idcliente,
							'numero_processo' => $post->nmprocesso, 
							'data_distribuicao' => $dtdistribuicao, 
							'data_encerramento' => $dtencerramento, 
							'tb_cliente_id' => $post->idcliente, 
							'tb_natureza_id' => $post->idnatureza, 
							'tb_comarca_id' => $post->idcomarca, 
							'tb_vara_id' => $post->idvara );
			
			$return = $processo->update($dados, $where);
			if ($return) {
				$this->view->message = "Atualização realizada com sucesso";	
			} else {
				$this->view->message = "Erro ao atualizar a processo";
			}
		} else {
			$id = $this->getRequest()->getParam("id");
				
			if (empty($id)) {
				$this->_redirect('/');
				exit;
			}
	
			if(!empty($id)) {
				$processo = new Processo();
				$where = "id = " . $id;
				$processo = $processo->fetchAll($where);
			}
			
			foreach ($processo as $dados) {
				$this->view->id = $dados["id"];
				$this->view->numero_processo = $dados["numero_processo"];
				$this->view->data_distribuicao = $dados["data_distribuicao"];
				$this->view->data_encerramento = $dados["data_encerramento"];
				$this->view->cliente = $dados["tb_cliente_id"];
				$this->view->natureza = $dados["tb_natureza_id"];			
				$this->view->vara = $dados["tb_vara_id"];
				$this->view->comarca = $dados["tb_comarca_id"];
			}
			
			$cliente = new Cliente();		
			$clientes = $cliente->fetchAll();
			$this->view->clientes = $clientes;
			
			$natureza = new Natureza();		
			$naturezas = $natureza->fetchAll();
			$this->view->naturezas = $naturezas;
			
			$comarca = new Comarca();		
			$comarcas = $comarca->fetchAll();
			
			$this->view->title = "Visualizar Processo";
			$this->view->comarcas = $comarcas;
			
			$vara = new Vara();		
			$varas = $vara->fetchAll();
			$this->view->varas = $varas;
		}
		/*$this->view->action = "editar";
		$this->view->textButton = "Editar";
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "cadastrar" );*/
		
		
		/////////////////////////////////////////////
		
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
		}

		if(!empty($id)) {
			$processo = new Processo();
			$where = "id = " . $id;
			
			try {
				$return = $processo->delete($where);
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
		$this->view->title = "Processos";
		
		$processo = new Processo();
		$processos = $processo->fetchAll();
		$this->view->processos = $processos;
		$this->view->nmprocessos = $processos->count();
		
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "index" );		
	}
	
	public function parteAction() {
		$this->view->title = "Partes";
		
		$idprocesso = $this->getRequest()->getParam("idprocesso");
		
		if (empty ( $idprocesso )) {
			$this->_redirect ( '/' );
			exit ();
		}
	
		if(!empty($idprocesso)) {
			$parte = new Parte();
			$where = "tb_processo_id = " . $idprocesso;
			$partes = $parte->fetchAll($where);
		}
		
		$this->view->partes = $partes;
		$this->view->nmpartes = $partes->count();

		// Renderiza a pagina
		$this->_helper->layout->setLayout ( 'listaprocessos' );
		$this->render ( "parte" );	
	}

}
?>