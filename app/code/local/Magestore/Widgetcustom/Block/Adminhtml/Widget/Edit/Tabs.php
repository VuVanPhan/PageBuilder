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
 * Widget custom Edit Tabs Block
 *
 * @category    Magestore
 * @package     Magestore_Widgetcustom
 * @author      Magestore Developer
 */
class Magestore_Widgetcustom_Block_Adminhtml_Widget_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('widgetCustomTabs');
        $this->setDestElementId('edit_form');
        if ( $tab = $this->getRequest()->getParam( 'activeTab' ) )
        {
            $this->_activeTab = $tab;
        }
        else
        {
            $this->_activeTab = 'general_section';
        }
        $this->setTitle(Mage::helper('widgetcustom')->__('Widget Custom Information'));
    }
    /**
     * prepare before render block to html
     *
     * @return Magestore_Widgetcustom_Block_Adminhtml_Widget_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper('widgetcustom')->__('General Information'),
            'title'     => Mage::helper('widgetcustom')->__('General Information'),
            'content'   => $this->_getTabHtml('design'),
        ));
        return parent::_beforeToHtml();
    }
    private function _getTabHtml($tab)
    {
        return $this->getLayout()->createBlock( 'widgetcustom/adminhtml_widget_edit_tab_' . $tab )->toHtml();
    }
}