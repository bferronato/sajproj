<?php 

class Cliente extends Zend_Db_Table {
	
	protected $_name = 'tb_cliente';
	
	protected $_dependentTables = array('Processo', 'Parte');

}

?>