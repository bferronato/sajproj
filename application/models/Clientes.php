<?php 

class Clientes extends Zend_Db_Table {
	
	protected $_name = 'tb_clientes';
	
	protected $_dependentTables = array('Processo', 'Parte');

}

?>