<?php
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
