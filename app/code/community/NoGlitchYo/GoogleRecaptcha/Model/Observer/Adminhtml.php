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

class NoGlitchYo_GoogleRecaptcha_Model_Observer_Adminhtml
{
    const XML_PATH_VALIDATE_ADMINHTML_LOGIN     = 'grecaptcha/recaptcha_on/adminhtml_index_login';
    const XML_PATH_VALIDATE_ADMINHTML_FORGOT    = 'grecaptcha/recaptcha_on/adminhtml_index_forgotpassword';

    /**
     * @return NoGlitchYo_GoogleRecaptcha_Model_Validator
     */
    protected function _getValidator()
    {
        return Mage::getSingleton('grecaptcha/validator');
    }

    /**
     * Triggered by: admin_user_authenticate_before
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function validateLogin(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig(self::XML_PATH_VALIDATE_ADMINHTML_LOGIN)) {
            if (!$this->_getValidator()->validate()) {
                Mage::throwException(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
            }
        }

        return $this;
    }

    /**
     * Triggered by: controller_action_predispatch_adminhtml_index_forgotpassword
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function validateForgotPassword(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig(self::XML_PATH_VALIDATE_ADMINHTML_FORGOT)) {
            $controller = $observer->getControllerAction();

            if (Mage::app()->getFrontController()->getRequest()->getMethod() == 'POST') {
                if (!$this->_getValidator()->validate()) {
                    $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('captcha')->__('Incorrect reCAPTCHA.'));
                    $controller->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
                }
            }
        }

        return $this;
    }
}
