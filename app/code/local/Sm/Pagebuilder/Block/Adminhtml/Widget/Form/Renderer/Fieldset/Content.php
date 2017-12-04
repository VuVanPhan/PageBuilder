<?php
/**
 * Created by PhpStorm.
 * User: Vu Van Phan
 * Date: 06-11-2015
 * Time: 10:57
 */
class Sm_Pagebuilder_Block_Adminhtml_Widget_Form_Renderer_Fieldset_Content extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;

    protected function _construct()
    {
        $widgets_info = Mage::getModel('widget/widget')->getWidgetsArray();
        $widgets_json = $widgets_info?Zend_Json::encode( $widgets_info ): "";
        $widgets_json = str_replace( array('\n','\r','\t') ,"", $widgets_json);
        $this->assign("widgets_json", $widgets_json);
        $this->setTemplate('sm/pagebuilder/widget/form/renderer/fieldset/content.phtml');
    }

    public function getElement()
    {
        return $this->_element;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }
    
    public function getHtmlId() {
        return $this->getElement()->getId();
    }

    public function getAddWidgetUrl() {
        return $this->getUrl('adminhtml/pagebuilder_pagebuilder/loadIndex').'widget_target_id/';
    }
}