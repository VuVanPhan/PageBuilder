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
    protected $_model = null;

    protected function _construct()
    {
        $this->_model = Mage::registry('page');

        //Get current layout profile params
        $params = $this->_model->getParams();

        $backup_params = array();
        $data = Mage::getModel("pagebuilder/page")->getCollection();
        $collection = $data->getData();
        if (count($collection) > 0) {
            foreach($collection as $c) {
                $backup_params[$c["page_code"]] = $c["params"];
            }
        }

//        $widgets_info = Mage::getModel('widget/widget')->getWidgetsArray();
        $widgets_info = Mage::helper('pagebuilder/widget')->getWidgetsArray();
        $widgets_json = $widgets_info?Zend_Json::encode( $widgets_info ): "";
        $widgets_json = str_replace( array('\n','\r','\t') ,"", $widgets_json);
        $this->assign("params", Mage::helper('core')->jsonEncode($params));
        $this->assign("widgets_json", $widgets_json);
        $this->assign("backup_params", $backup_params);
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