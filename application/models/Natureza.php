<?php 

class Natureza extends Zend_Db_Table {
	
	protected $_name = 'tb_natureza';

	protected $_dependentTables = array('Processo');
}

?>