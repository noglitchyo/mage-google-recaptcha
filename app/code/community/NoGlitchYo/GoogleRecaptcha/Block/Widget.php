<?php
/**
 * @category NoGlitchYo
 * @package NoGlitchYo_GoogleRecaptcha  
 * @author Maxime ELOMARI <maxime.elomari@gmail.com>
 * @copyright Copyright (c) 2015, Maxime Elomari
 * @license http://opensource.org/licenses/MIT
 */

class NoGlitchYo_GoogleRecaptcha_Block_Widget extends Mage_Core_Block_Template
{
    public function getKey()
    {
        return Mage::getStoreConfig(NoGlitchYo_GoogleRecaptcha_Helper_Data::XML_PATH_SITE_KEY);
    }

    public function getSecureToken()
    {
        return Mage::helper('grecaptcha')->getSecureToken();
    }
}
