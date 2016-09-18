<?php

class Zend_Controller_Action_Helper_String extends Zend_Controller_Action_Helper_Abstract
{
	function removeAcentos( $string, $entityDecode )
	{
		$alpha = array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
		$beta = array("a","a","a","a","a","e","e","e","e","i","i","i","i","o","o","o","o","o","u","u","u","u","c","A","A","A","A","A","E","E","E","E","I","I","I","I","O","O","O","O","O","U","U","U","U","C");
		if( $entityDecode ) $string = $this->entityDecode( $string );
		return str_replace( $alpha, $beta, $string );
	}
	
	function entityDecode( $string )
	{
		return html_entity_decode( $string );
	}
}