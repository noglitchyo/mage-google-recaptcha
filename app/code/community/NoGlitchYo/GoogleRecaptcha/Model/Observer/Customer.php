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

class NoGlitchYo_GoogleRecaptcha_Model_Observer_Customer
{
    const XML_PATH_VALIDATE_CUSTOMER_CREATE     = 'grecaptcha/recaptcha_on/customer_account_create';
    const XML_PATH_VALIDATE_CUSTOMER_LOGIN      = 'grecaptcha/recaptcha_on/customer_account_login';
    const XML_PATH_VALIDATE_CUSTOMER_FORGOT     = 'grecaptcha/recaptcha_on/customer_account_forgotpassword';

    /**
     * @return NoGlitchYo_GoogleRecaptcha_Model_Validator
     */
    protected function _getValidator()
    {
        return Mage::getSingleton('grecaptcha/validator');
    }

    /**
     * Check Google Recaptcha on User Login Page
     * Triggered by: controller_action_predispatch_customer_account_loginPost
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function validateLogin(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig(self::XML_PATH_VALIDATE_CUSTOMER_LOGIN)) {
            $controller = $observer->getControllerAction();

            if (!$this->_getValidator()->validate()) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('grecaptcha')->__('Incorrect reCAPTCHA.'));
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $beforeUrl = Mage::getSingleton('customer/session')->getBeforeAuthUrl();
                $url = $beforeUrl ? $beforeUrl : Mage::helper('customer')->getLoginUrl();
                $controller->getResponse()->setRedirect($url);
            }
        }

        return $this;
    }

    /**
     * Check Google Recaptcha on User Create Account
     * Triggered by: controller_action_predispatch_customer_account_createpost
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function validateCreate(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig(self::XML_PATH_VALIDATE_CUSTOMER_CREATE)) {
            $controller = $observer->getControllerAction();

            if (!$this->_getValidator()->validate()) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('grecaptcha')->__('Incorrect reCAPTCHA.'));
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                Mage::getSingleton('customer/session')->setCustomerFormData($controller->getRequest()->getPost());
                $controller->getResponse()->setRedirect(Mage::getUrl('*/*/create'));
            }
        }

        return $this;
    }

    /**
     * Check Google Recaptcha on User Forgot Password
     * Triggered by: controller_action_predispatch_customer_account_forgotpasswordpost
     *
     * @param $observer
     * @return $this
     */
    public function validateForgotPassword($observer)
    {
        if (Mage::getStoreConfig(self::XML_PATH_VALIDATE_CUSTOMER_FORGOT)) {
            $controller = $observer->getControllerAction();

            if (!$this->_getValidator()->validate()) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect reCAPTCHA.'));
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $controller->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
            }
        }

        return $this;
    }
}
