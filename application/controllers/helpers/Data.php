<?php

class Zend_Controller_Action_Helper_Data extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * @param string format 25/12-2005 
	 * @return string format 2005-12-25
	 */
	function isoFormat( $data )
	{
		return date('Y-m-d', strtotime(str_replace('/', '-', $data)));
	}

	/**
	 * Verifica se a data eh valida no formato ANO-MES-DIA e caso seja valida
	 * retorna a data no formato DD/MM/YYYY caso contrario retorna vazio.
	 *
	 * @param string format 2005-12-25
	 * @return string format 25/12-2005
	 */
	function ptFormat( $date ) {
		$aDate_parts = preg_split("/[\s-]+/", $date);
		if( !empty( $date ) && checkdate( $aDate_parts[1], $aDate_parts[2], $aDate_parts[0] ) ) {
			$date = date( 'd/m/Y', strtotime( $date ) );
		} else {
			$date = '';
		}
		return $date;
	}
}