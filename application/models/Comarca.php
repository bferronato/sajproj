<?php 

class Comarca extends Zend_Db_Table {
	
	protected $_name = 'tb_comarca';

	protected $_dependentTables = array('Processo');
}

?>