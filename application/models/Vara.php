<?php 

class Vara extends Zend_Db_Table {
	
	protected $_name = 'tb_vara';

	protected $_dependentTables = array('Processo');
}

?>