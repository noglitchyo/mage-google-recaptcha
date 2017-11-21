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

class NoGlitchYo_GoogleRecaptcha_Model_Observer_Contacts
{
    const XML_PATH_VALIDATE_CONTACT_US = 'grecaptcha/recaptcha_on/contacts_index_index';

    /**
     * @return NoGlitchYo_GoogleRecaptcha_Model_Validator
     */
    protected function _getValidator()
    {
        return Mage::getSingleton('grecaptcha/validator');
    }

    /**
     * Check Google Recaptcha on User Login Page
     * Triggered by: controller_action_predispatch_contacts_index_post
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function validateContactForm(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig(self::XML_PATH_VALIDATE_CONTACT_US)) {
            $controller = $observer->getControllerAction();

            if (!$this->_getValidator()->validate()) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('grecaptcha')->__('Incorrect reCAPTCHA.'));
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $controller->getResponse()->setRedirect(Mage::getUrl('*/'));
            }
        }

        return $this;
    }
}
