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
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Tag
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Cleaninput Frontend controller
 *
 * @category   Mage
 * @package    Mp_Cecurity
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mp_Security_CleaninputController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Cleaninput Page"));
        $this->renderLayout();
    }

    public function checkFormAction() {
        $dataPost = $this->getRequest()->getPost();
        $session = Mage::getSingleton('core/session');
        $sessionCustomer = Mage::getSingleton('customer/session');
        if ($dataPost) {
//            $data["name"] = stripslashes(Mage::helper("security")->stripTags($dataPost['name']));
            $data["name"] = $dataPost['name'];
            $data['creation_time'] = now();
            $data['update_time'] = now();
            $model = Mage::getModel("security/cleaninput");
            $model->setData($data);

            try {
                /**
                 * Get the resource model
                 */
                $resource = Mage::getSingleton('core/resource');

                /**
                 * Retrieve the write connection
                 */
                $writeConnection = $resource->getConnection('core_write');

//                /**
//                 * Retrieve the read connection
//                 */
//                $readConnection = $resource->getConnection('core_read');

//                $query = 'SELECT * FROM ' . $resource->getTableName('catalog/product');
//
//                /**
//                 * Execute the query and store the results in $results
//                 */
//                $results = $readConnection->fetchAll($query);

                /**
                 * Retrieve our table name
                 */
                $table = $resource->getTableName('security/cleaninput');

                /**
                 * UPDATE DATA
                 * */
//                $query = "UPDATE {$table} SET {item} = '{value}' WHERE entity_id = 'value'";

                /**
                 * INSERT DATA
                 * */
                $query = "INSERT INTO {$table} (`name`) VALUES ('{$data["name"]}')";
//                $writeConnection->query($query);
//                var_dump($model->getData());
//                die('ddds');
                $model->save();

                $session->addSuccess($this->__("Items save success"));
                return $this->_redirect("*/*/");
            } catch (Exception $e) {
                $sessionCustomer->addError($e->getMessage());
                return $this->_redirect("*/*/");
            }
        }
        $session->addError(Mage::helper('security')->__('Item does not exist'));
        return $this->_redirect("*/*/");
    }
}