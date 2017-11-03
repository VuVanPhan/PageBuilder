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
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml security grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mp_Security_Block_Adminhtml_Cleaninput_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('cleaninputGrid');
        $this->setDefaultSort('cleaninput_id');
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Mp_Security_Block_Adminhtml_Cleaninput_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('security/cleaninput')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns collection object
     *
     * @return Mp_Security_Block_Adminhtml_Cleaninput_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('cleaninput_id', array(
            'header' => Mage::helper('security')->__('ID'),
            'align' => 'right',
            'width' => '80px',
            'index' => 'cleaninput_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('security')->__('Name'),
            'align' => 'left',
            'index' => 'name',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('security')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('security')->__('XML'));

        parent::_prepareColumns();
    }

    /**
     * Prepare grid massaction actions
     *
     * @return Mp_Security_Block_Adminhtml_Security_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField("cleaninput_id");
        $this->getMassactionBlock()->setFormFieldName("cleaninput");

        $this->getMassactionBlock()->addItem("deleta", array(
            "label"     => Mage::helper('security')->__("Delete"),
            "url"       => $this->getUrl("*/*/massDelete"),
            "confirm"   => Mage::helper("security")->__("Are you sure?")
        ));
        return $this;
    }

    /**
     * Return row url for js event handlers
     *
     * @param Mage_Catalog_Model_Product|Varien_Object
     * @return string
     */
    public function getRowUrl($item)
    {
        $res = $this->getUrl("*/*/edit", array("id" => $item->getId()));
        return ($res ? $res : '#');
    }
}