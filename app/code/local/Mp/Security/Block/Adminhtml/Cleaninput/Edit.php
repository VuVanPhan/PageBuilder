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
 * Cleaninput Edit Block
 *
 * @category    Magestore
 * @package     Mp_Security
 * @author      Magestore Developer
 */
class Mp_Security_Block_Adminhtml_Cleaninput_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'security';
        $this->_controller = 'adminhtml_cleaninput';

        $this->_updateButton('save', 'label', Mage::helper('adminhtml')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('adminhtml')->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('security_cleaninput') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'security_cleaninput');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'security_cleaninput');
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('cleaninput_data')
            && Mage::registry('cleaninput_data')->getId()
        ) {
            $name = Mage::registry('cleaninput_data')->getName();
            $title = Mage::helper('security')->truncate($name, 150);
            return Mage::helper('security')->__("Edit '%s'", $this->htmlEscape($title));
        }
        return Mage::helper('security')->__('Add Solution Partner');
    }
}