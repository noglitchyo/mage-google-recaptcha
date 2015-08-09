<?php
/**
 * @package NoGlitchYo_Google_Recaptcha
 * @author Maxime ELOMARI <maxime.elomari@gmail.com>
 * @copyright Copyright (c) 2015, Maxime Elomari
 * @license http://opensource.org/licenses/MIT
 */

class NoGlitchYo_GoogleRecaptcha_Helper_Data extends Mage_Core_Helper_Data
{
    const XML_PATH_SITE_KEY     = 'grecaptcha/general/site_key';
    const XML_PATH_SECRET_KEY   = 'grecaptcha/general/secret_key';

    public function getSecureToken()
    {
        $token = array(
            'session_id' => Mage::getSingleton('core/session')->getFormKey(),
            'ts_ms' => time()
        );

        return json_encode($token);
    }
}
