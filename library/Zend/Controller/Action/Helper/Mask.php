<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * GenerateRandom Action Helper
 *
 * @desc Cria uma string rand™mica
 * @uses Zend_Controller_Action_Helper
 */
class Zend_Controller_Action_Helper_Mask extends Zend_Controller_Action_Helper_Abstract
{    
    const FONE = '(##)####-####';
    const CPF = '###.###.###-##';
    const CEP = '#####-###';
    const CNPJ = '##.###.###/####-##';
 
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct()
    {
    }
    
	public function mask( $mask,$str )
	{
		
		$mask = $this->getMask( $mask );
		
		if( !$str ) $mask = $str;
		
		$str = str_replace(" ", "", $str );
		for( $i = 0; $i < strlen( $str ); $i++ ) {
			$mask[strpos( $mask, "#" )] = $str[$i];
		}
		return $mask;
	}
 
	private function getMask( $mask )
	{	
		switch( $mask ) {
			case 'FONE':
				$mask = Zend_Controller_Action_Helper_Mask::FONE;
			break;
			case 'CPF':
				$mask = Zend_Controller_Action_Helper_Mask::CPF;
			break;
			case 'CEP':
				$mask = Zend_Controller_Action_Helper_Mask::CEP;
			break;
			case 'CNPJ':
				$mask = Zend_Controller_Action_Helper_Mask::CNPJ;
			break;
		}
		
		return $mask;
	}
 
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct( $mask = '', $str = '' )
    {
        return $this->mask( $mask, $str );
    }
}