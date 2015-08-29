<?php
/**
 * NOTICE OF LICENSE
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category NoGlitchYo
 * @package NoGlitchYo_Google_Recaptcha
 * @author Maxime ELOMARI <maxime.elomari@gmail.com>
 * @copyright Copyright (c) 2015, Maxime Elomari
 * @license http://opensource.org/licenses/MIT
 */

class NoGlitchYo_GoogleRecaptcha_Helper_Data extends Mage_Core_Helper_Data
{
    const XML_PATH_SITE_KEY     = 'grecaptcha/keys/site_key';
    const XML_PATH_SECRET_KEY   = 'grecaptcha/keys/secret_key';

    const XML_PATH_VALIDATION_ENDPOINT_URI   = 'grecaptcha/general/endpoint_uri';
    const XML_PATH_VALIDATE_CHECKOUT_1P_IDX  = 'grecaptcha/recaptcha_on/checkout_onepage_index';

    /**
     * Token in JSON
     * @return string
     */
    public function getSecureToken()
    {
        /**
         * A unique string that identifies this request.
         * Every CAPTCHA request needs a distinct session_id.
         */
        $token = array(
            'session_id' => Mage::getSingleton('core/session')->getFormKey(), //maybe use the session_id?
            'ts_ms' => time()
        );

        return json_encode($token);
    }

    /**
     * Encrypted secure token with site secret
     * @return string
     */
    public function getEncryptedSecureToken()
    {
        return crypt($this->getSecureToken(), Mage::getStoreConfig(self::XML_PATH_SECRET_KEY));
    }
}
