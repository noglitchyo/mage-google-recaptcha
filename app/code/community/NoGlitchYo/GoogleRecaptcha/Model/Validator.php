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

class NoGlitchYo_GoogleRecaptcha_Model_Validator
{
    const XML_PATH_VALIDATION_ENDPOINT_URI = 'grecaptcha/general/endpoint_uri';

    const GRECAPTCHA_RESPONSE_FORM_FIELD_NAME = 'g-recaptcha-response';

    /**
     * Validate the captcha by executing the request and return whether or not the captcha is valid (errors are logged)
     * @return bool
     */
    public function validate()
    {
        $captchaResponse = Mage::app()->getFrontController()->getAction()->getRequest()->getPost(self::GRECAPTCHA_RESPONSE_FORM_FIELD_NAME);
        $secret = Mage::getStoreConfig(NoGlitchYo_GoogleRecaptcha_Helper_Data::XML_PATH_SECRET_KEY);

        if (empty($secret)) {
            throw new Exception('Secret for Google reCAPTCHA must be defined first.');
        }

        try {
            $data = $this->_request($captchaResponse, $secret);
            if (empty($data)) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        if (isset($data['error-codes'])) {
            foreach ($data['error-codes'] as $error) {
                Mage::helper('grecaptcha')->log(sprintf('Error code: %s', $error));
            }
        }

        return $data['success'];
    }

    /**
     * Execute request to Google and validate that captcha response is correct
     * @param $captchaResponse
     * @param $secret
     * @return mixed
     */
    protected function _request($captchaResponse, $secret)
    {
        try {
            $client = new Zend_Http_Client(Mage::getStoreConfig(self::XML_PATH_VALIDATION_ENDPOINT_URI));
            $client->setParameterGet('response', $captchaResponse);
            $client->setParameterGet('secret', $secret);
            $client->setParameterGet('remoteip', Mage::helper('core/http')->getRemoteAddr(true));

            $response = $client->request();
        }
        catch (Zend_Http_Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('customer/session')->addError(Mage::helper('grecaptcha')->__('Unable to validate the reCAPTCHA with Google. Please, retry later.'));
            return null;
        }
        catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('customer/session')->addError(Mage::helper('grecaptcha')->__('Unable to validate the reCAPTCHA.'));
            return null;
        }

        return $this->_parseResponse($response);
    }

    /**
     * Decode the response
     * @param $response
     * @return array
     */
    protected function _parseResponse($response)
    {
        $data = Zend_Json::decode($response->getBody());

        return $data;
    }
}
