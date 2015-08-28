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
    public function validate($controller)
    {
        $captchaResponse= $controller->getRequest()->getPost('g-recaptcha-response');
        $secret = Mage::getStoreConfig(NoGlitchYo_GoogleRecaptcha_Helper_Data::XML_PATH_SECRET_KEY);

        $data = $this->_request($captchaResponse, $secret);

        return $data['success'];
    }

    protected function _request($captchaResponse, $secret)
    {
        try {
            $client = new Zend_Http_Client(Mage::getStoreConfig(NoGlitchYo_GoogleRecaptcha_Helper_Data::XML_PATH_VALIDATION_ENDPOINT_URI));
            $client->setParameterGet('response', $captchaResponse);
            $client->setParameterGet('secret', $secret);

            $response = $client->request();
        }
        catch (Zend_Http_Exception $e) {

        }
        catch (Exception $e) {

        }

        return $this->_parseResponse($response);
    }


    protected function _parseResponse($response)
    {
        $data = Zend_Json::decode($response->getBody());

        return $data;
    }
}
