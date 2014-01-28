<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    tests
 * @package     selenium
 * @subpackage  tests
 * @author      Magento Core Team <core@magentocommerce.com>
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Add address tests.
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Helper class
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Lofty_Mage_Customer_Helper extends Lofty_Selenium_TestCase
{
    /**
     * Register Customer on Frontend.
     * PreConditions: 'Login or Create an Account' page is opened.
     *
     * @param array $registerData
     * @param bool $disableCaptcha
     *
     * @return void
     */
    public function registerCustomer(array $registerData, $disableCaptcha = true)
    {
        $currentPage = $this->getCurrentPage();
        $this->clickLink('create_account');
        // Disable CAPTCHA if present
        if ($disableCaptcha && $this->controlIsPresent('pageelement', 'captcha')) {
            $this->loginAdminUser();
            $this->navigate('system_configuration');
            $this->systemConfigurationHelper()->configure('disable_customer_captcha');
            $this->frontend($currentPage);
            $this->clickLink('create_account');
        }
        $this->fillForm($registerData);
        $waitConditions = array($this->_getMessageXpath('general_error'), $this->_getMessageXpath('general_validation'),
            $this->_getControlXpath('link', 'log_out'));
        $this->clickButton('submit', false);
        $this->waitForElement($waitConditions);
        $this->validatePage();
    }

    /**
     * Log in customer at frontend.
     *
     * @param array $loginData
     */
    public function frontLoginCustomer(array $loginData)
    {
        $this->frontend();
        $this->logoutCustomer();
        $this->clickControl('link', 'log_in');
        $this->fillFieldset($loginData, 'log_in_customer');
        $waitConditions = array($this->_getMessageXpath('general_error'), $this->_getMessageXpath('general_validation'),
            $this->_getControlXpath('link', 'log_out'));
        $this->clickButton('login', false);
        $this->waitForElement($waitConditions);
        $this->addParameter('id', $this->defineIdFromUrl());
        $this->assertTrue($this->controlIsPresent('link', 'log_out'), 'Customer is not logged in.');
        $this->setCurrentPage($this->_findCurrentPageFromUrl());
    }
}