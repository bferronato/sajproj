<?php

class AuthController extends Zend_Controller_Action {

	function init() {
		$this->initView();
		//$this->view->baseUrl = $this->_request->getBaseUrl();
	}

    function indexAction() {
        $this->_redirect('/busca/buscar');
	}

	function loginAction() {
		
		$this->view->message = '';
		if ($this->_request->isPost()) {
			// pega as informacoes do usuario
			Zend_Loader::loadClass('Zend_Filter_StripTags');
			$f = new Zend_Filter_StripTags();
			$username = $f->filter($this->_request->getPost('login'));
			$password = $f->filter($this->_request->getPost('senha'));
			
			if (empty($username)) {
				$this->view->message = 'Por favor informe o usuario.';
			} else {
				// configura o adapter Zend_Auth para uma tabela do BD
				Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
				
				$db = Zend_Registry::get('db');
				
				$authAdapter = new Zend_Auth_Adapter_DbTable($db);
				$authAdapter->setTableName('tb_admin');
				$authAdapter->setIdentityColumn('login');
				$authAdapter->setCredentialColumn('senha');

				// Seta as credenciais para a autenticacao
				$authAdapter->setIdentity($username);
				$authAdapter->setCredential($password);

				// Faca a autenticacao
				$auth = Zend_Auth::getInstance();
				
				echo __LINE__;

				try {
					$result = $auth->authenticate($authAdapter);
				catch(Exception $e) {
					phpinfo();
					echo $e->getMessage();
					die
				}
				echo __LINE__;
				echo "334565443";
				die("okok");

				if ($result->isValid()) {
					// success: store database row to auth's storage
					// system. (Not the password though!)
					echo __LINE__;

					die;
					// Seta o timeout da sessao para 1800 segundos
					$authNamespace = new Zend_Session_Namespace('auth');
		            		// timeout is 20 minutes (1200 seconds)
        		    		$authNamespace->timeout = time() + 1800;
  
					$data = $authAdapter->getResultRowObject(null, 'senha');
					echo __LINE__;
					die("okok");
					$auth->getStorage()->write($data);
					$this->_redirect('/busca/buscar');
				} else {
					// failure: clear database row from session
					$this->view->message = 'Login ou senha incorretos.';
				}
			}
			$this->view->title = 'Login';
		}
	}

	function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('');
	}
}
