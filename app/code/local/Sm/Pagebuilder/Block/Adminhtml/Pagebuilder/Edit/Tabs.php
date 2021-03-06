<?php
/**
 * Created by PhpStorm.
 * User: Vu Van Phan
 * Date: 04-11-2015
 * Time: 15:33
 */
class Sm_Pagebuilder_Block_Adminhtml_Pagebuilder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('pagebuilder_tabs');
		$this->setDestElementId('edit_form');
		if ( $tab = $this->getRequest()->getParam( 'activeTab' ) )
		{
			$this->_activeTab = $tab;
		}
		else
		{
			$this->_activeTab = 'form_design';
		}
		$this->setTitle("<i class='fa fa-qrcode'></i>".Mage::helper('pagebuilder')->__('Page Builder'));
	}

	protected function _beforeToHtml()
	{
		$builder_type_label = 'Page';
		$this->addTab('form_general', array(
			'label'     => "<i class='fa fa-cogs'></i>".Mage::helper('pagebuilder')->__($builder_type_label.' General'),
			'title'     => Mage::helper('pagebuilder')->__('Block General'),
			'content'   => $this->_getTabHtml('general'),
		));

		$this->addTab('form_design', array(
			'label'     => "<i class='fa fa-object-group'></i>".Mage::helper('pagebuilder')->__('Design '.$builder_type_label),
			'title'     => Mage::helper('pagebuilder')->__('Design Page'),
			'content'   => $this->_getTabHtml('design'),
		));

		$this->addTab('form_cmspage', array(
			'label'     => "<i class='fa fa-file-text-o'></i>".Mage::helper('pagebuilder')->__('CMS '.$builder_type_label.' Information'),
			'title'     => Mage::helper('pagebuilder')->__('CMS Page Information'),
			'content'   => $this->_getTabHtml('cmspage'),
		));

		$this->addTab('form_settings', array(
			'label'     => "<i class='fa fa-cog'></i>".Mage::helper('pagebuilder')->__('Settings'),
			'title'     => Mage::helper('pagebuilder')->__('Settings'),
			'content'   => $this->_getTabHtml('settings'),
		));

		parent::_beforeToHtml();
	}

	private function _getTabHtml($tab)
	{
		return $this->getLayout()->createBlock( 'pagebuilder/adminhtml_pagebuilder_edit_tab_' . $tab )->toHtml();
	}
}