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
 * Cleaninput Adminhtml controller
 *
 * @category   Mage
 * @package    Mp_Cecurity
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mp_Security_Adminhtml_CleaninputController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('security');
    }

    private function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('security/managesecurity')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Security Manager'), Mage::helper('adminhtml')->__('Security Manager'));
        $this->getLayout()->getBlock("head")->setTitle($this->__("Cleaninput Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $cleaninputId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('security/cleaninput')->load($cleaninputId);
        if ($model->getId() || $cleaninputId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('cleaninput_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('security/cleaninput');
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Cleaninput Manager'),
                Mage::helper('adminhtml')->__('Cleaninput Manager')
            );

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Cleaninput News'),
                Mage::helper('adminhtml')->__('Cleaninput News')
            );
            
            if ($model->getId())
                $this->_title($model->getName());
            else
                $this->_title(Mage::helper('adminhtml')->__('Add New Cleaninput'));
            
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('security/adminhtml_cleaninput_edit'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('security')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }
    
    public function saveAction() {
        $id = $this->getRequest()->getParam('id', null);
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('security/cleaninput');
            $model->setData($data)
                ->setId($id);
            
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('security')->__('Item was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('security')->__('Unable to find item to save')
        );
        $this->_redirect('*/*/');
    }
    
    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id', null) > 0) {
            try {
                $model = Mage::getModel('security/cleaninput');
                $model->setId($this->getRequest()->getParam('id', null))
                    ->delete();
                
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );

                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    
    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $cleaninputIdIds = $this->getRequest()->getParam('cleaninput');
        if (!is_array($cleaninputIdIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($cleaninputIdIds as $cleaninputId) {
                    $model = Mage::getModel('security/cleaninput')->load($cleaninputId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                        count($cleaninputIdIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'merchant.csv';
        $content    = $this->getLayout()
            ->createBlock('security/adminhtml_cleaninput_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'merchant.xml';
        $content    = $this->getLayout()
            ->createBlock('security/adminhtml_cleaninput_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
}