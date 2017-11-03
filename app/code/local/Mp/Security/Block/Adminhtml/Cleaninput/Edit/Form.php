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
 * @category     Magestore
 * @package     Magestore_SolutionPartner
 * @copyright     Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Cleaninput Edit Form Block
 *
 * @category    Magestore
 * @package     Mp_Security
 * @author      Magestore Developer
 */
class Mp_Security_Block_Adminhtml_Cleaninput_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form's information for block
     *
     * @return Mp_Security_Block_Adminhtml_Cleaninput_Edit_Form
     */
    protected function _prepareForm()
    {
        $cleaninput = Mage::registry('cleaninput_data')->getData();
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array(
                'id'    => $this->getRequest()->getParam('id'),
            )),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('cleaninput_form', array(
            'legend'=>Mage::helper('security')->__('Input Information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'         => Mage::helper('security')->__('Name'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'name',
        ));

        $form->setUseContainer(true);
        $form->setValues($cleaninput);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}