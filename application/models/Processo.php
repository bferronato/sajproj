<?php 

class Processo extends Zend_Db_Table {
	
	protected $_name = 'tb_processo';
	
	protected $_referenceMap = array(
		'Cliente'=>array(
			'columns'=>'tb_cliente_id',
			'refTableClass'=>'Cliente',
			'refColums'=>'id' ),
	
		'Natureza'=>array(
			'columns'=>'tb_natureza_id',
			'refTableClass'=>'Natureza',
			'refColums'=>'id' ),
	
		'Vara'=>array(
			'columns'=>'tb_vara_id',
			'refTableClass'=>'Vara',
			'refColums'=>'id' ),
		
		'Comarca'=>array(
			'columns'=>'tb_comarca_id',
			'refTableClass'=>'Comarca',
			'refColums'=>'id' ),
	
	);
	
	protected $_dependentTables = array('Parte');
}

?>