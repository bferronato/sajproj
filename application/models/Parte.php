<?php 

class Parte extends Zend_Db_Table {
	
	protected $_name = 'tb_parte';
	
	protected $_referenceMap = array(
		'Processo'=>array(
			'columns'=>'tb_processo_id',
			'refTableClass'=>'Processo',
			'refColums'=>'numero_processo' ),

		'Parte'=>array(
			'columns'=>'tb_cliente_id',
			'refTableClass'=>'Cliente',
			'refColums'=>'id' ),
	);
}

?>