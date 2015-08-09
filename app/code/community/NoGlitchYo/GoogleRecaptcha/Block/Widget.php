<?php
/**
 * @category NoGlitchYo
 * @package NoGlitchYo_GoogleRecaptcha  
 * @author Maxime ELOMARI <maxime.elomari@gmail.com>
 * @copyright Copyright (c) 2015, Maxime Elomari
 * @license http://opensource.org/licenses/MIT
 */

class NoGlitchYo_GoogleRecaptcha_Block_Form extends Mage_Core_Block_Template
{
    public function getKey()
    {
        return Mage::getStoreConfig();
    }

    public function getSecureToken()
    {
        return Mage::helper('grecaptcha/data')->getSecureToken();
    }
}
