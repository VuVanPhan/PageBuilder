<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
/**
 * Widgetcustom Edit Form Content Tab Block
 *
 * @category    Magestore
 * @package     Magestore_Widgetcustom
 * @author      Magestore Developer
 */
class Magestore_Widgetcustom_Block_Adminhtml_Widget_Edit_Tab_Design extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Widgetcustom_Block_Adminhtml_Widget_Edit_Tab_Design
     */
    protected function _prepareForm()
    {
        /** @var $model Magestore_Widgetcustom_Model_Widget */
        $model = Mage::registry('cms_page');
        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('widgetcustom_');
        
        $fieldset = $form->addFieldset('link_form', array(
            'legend'=>Mage::helper('widgetcustom')->__('Widget Information')
        ));
        $fieldset->addField('name', 'text', array(
            'label'         => Mage::helper('widgetcustom')->__('Name'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'name',
        ));
        $contentField = $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'style'     => 'height:36em;width:1173px',
            'required'  => true,
            'disabled'  => $isElementDisabled,
        ));
        // Setting custom renderer for content field to remove label column
        $renderer = $this->getLayout()->createBlock('widgetcustom/adminhtml_custom_form_renderer_fieldset_element')
            ->setTemplate('widgetcustom/widget/page/edit/form/renderer/content.phtml');
        $contentField->setRenderer($renderer);
        $form->setUseContainer(true);
        $form->setValues($model);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('widgetcustom/widget/' . $action);
    }
}