<?php
/**
 * AHT_Aslideshow Extension
 *
 * @category    Local
 * @package     AHT_Aslideshow
 * @author      dungnv (dungnv@arrowhitech.com)
 * @copyright   Copyright(c) 2011 Arrowhitech Inc. (http://www.arrowhitech.com)
 *
 */

/**
 *
 * @category   Local
 * @package    AHT_Aslideshow
 * @author     dungnv <dungnv@arrowhitech.com>
 */
 
class AHT_Aslideshow_Adminhtml_SlideshowController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('aslideshow');
        $id = $this->getRequest()->getParam('id');
        $_slideshow = Mage::getModel('aslideshow/slideshow')->load($id);

        if ($_slideshow->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $_slideshow->setData($data);
            }
        }
        Mage::register('slideshow_data', $_slideshow);
        Mage::register('current_slideshow', $_slideshow);
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Slideshow Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow'));
        $this->renderLayout();
    }

    public function addAction() {
        $this->_title($this->__('New Slideshow'));

        $_model  = Mage::getModel('aslideshow/slideshow');
        //Mage::register('slideshow_data', $_model);
        //Mage::register('current_slideshow', $_model);

        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Slideshow Manager'), Mage::helper('adminhtml')->__('Slideshow Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Slideshow'), Mage::helper('adminhtml')->__('Add Slideshow'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

/*        $this->_addContent($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_add'))
                ->_addLeft($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_add_tabs'));*/
        $this->_addContent($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit'))
                    ->_addLeft($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit_tabs'));

        $this->renderLayout();
    }

    public function editAction() {
        $slideshowId     = $this->getRequest()->getParam('id');
        $_model  = Mage::getModel('aslideshow/slideshow')->load($slideshowId);

        if ($_model->getId()) {
            $this->_title($_model->getId() ? $_model->getName() : $this->__('New Slideshow'));

            //Mage::register('slideshow_data', $_model);
            //Mage::register('current_slideshow', $_model);

            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Slideshow Manager'), Mage::helper('adminhtml')->__('Slideshow Manager'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Slideshow'), Mage::helper('adminhtml')->__('Edit Slideshow'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit'))
                    ->_addLeft($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('aslideshow')->__('The slideshow does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {//echo '<pre>'; print_r($data); die();
            if(isset($data['products']['slideshow'])){
                $data['products']['slideshow'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['products']['slideshow']);
            }
            if(isset($data['staticblocks']['slideshow'])){
                $data['staticblocks']['slideshow'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['staticblocks']['slideshow']);
            }
            $_model = Mage::getModel('aslideshow/slideshow');
            if (isset($data['categories'])) {
                $data['categories'] = explode(',',$data['categories']);
                if (is_array($data['categories'])) {
                    $data['categories'] = array_unique($data['categories']);
                }
            }

            $_model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                $_model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('aslideshow')->__('Slideshow was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $_model->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('aslideshow')->__('Unable to find slideshow to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('aslideshow/slideshow');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slideshow was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $IDList = $this->getRequest()->getParam('slideshow');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select slideshow(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('aslideshow/slideshow')
                            ->setIsMassDelete(true)->load($itemId);
                    $_model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($IDList)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $IDList = $this->getRequest()->getParam('slideshow');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select slideshow(s)'));
        } else {
            try {
                if($this->getRequest()->getParam('status') == 2) {
                    $_status = 0;
                } else {
                    $_status = 1;
                }
                foreach ($IDList as $itemId) {
                    /*$_model = Mage::getSingleton('aslideshow/slideshow')
                            ->setIsMassStatus(true)
                            ->load($itemId)
                            ->setIsActive($_status)
                            ->save();*/
                    Mage::getResourceModel('aslideshow/slideshow')->updateIsActive($_status, $itemId);
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($IDList))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function imageAction() {
        $result = array();
        try {
            $uploader = new AHT_Aslideshow_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('aslideshow/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('aslideshow/config')->getMediaUrl($result['file']);
            $result['cookie'] = array(
                    'name'     => session_name(),
                    'value'    => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path'     => $this->_getSession()->getCookiePath(),
                    'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function categoriesJsonAction()
    {
        $slideshowId     = $this->getRequest()->getParam('id');
        $_model  = Mage::getModel('aslideshow/slideshow')->load($slideshowId);
        Mage::register('slideshow_data', $_model);
        Mage::register('current_slideshow', $_model);

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit_tab_category')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    public function productAction()
    {
        $this->_initAction();
        $this->loadLayout();
        $this->getLayout()->getBlock('aslideshow.slideshow.edit.tab.products')
            ->setProductsRelated($this->getRequest()->getPost('products_slideshow', null));
        $this->renderLayout();
    }

    public function productGridAction()
    {
        $this->_initAction();
        $this->loadLayout();
        $this->getLayout()->getBlock('aslideshow.slideshow.edit.tab.products')
            ->setProductsRelated($this->getRequest()->getPost('products_slideshow', null));
        $this->renderLayout();
        /*$id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('aslideshow/slideshow');

        if ($id) {
            $model->load($id);
        }
        $this->getResponse()->setBody($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit_tab_products')->toHtml());*/
    }

    public function staticblockAction()
    {
        $this->_initAction();
        $this->loadLayout();
        $this->getLayout()->getBlock('aslideshow.slideshow.edit.tab.staticblocks')
            ->setProductsRelated($this->getRequest()->getPost('staticblocks_slideshow', null));
        $this->renderLayout();
    }

    public function staticblockGridAction()
    {
        $this->_initAction();
        $this->loadLayout();
        $this->getLayout()->getBlock('aslideshow.slideshow.edit.tab.staticblocks')
            ->setProductsRelated($this->getRequest()->getPost('staticblocks_slideshow', null));
        $this->renderLayout();
        /*$id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('aslideshow/slideshow');

        if ($id) {
            $model->load($id);
        }
        $this->getResponse()->setBody($this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_edit_tab_products')->toHtml());*/
    }

   /* public function productsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('catalog.product.edit.tab.related')
            ->setProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }*/

        /**
     * Create serializer block for a grid
     *
     * @param string $inputName
     * @param Mage_Adminhtml_Block_Widget_Grid $gridBlock
     * @param array $productsArray
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Ajax_Serializer
     */
    /*protected function _createSerializerBlock($inputName, Mage_Adminhtml_Block_Widget_Grid $gridBlock, $productsArray)
    {
        return $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_ajax_serializer')
            ->setGridBlock($gridBlock)
            ->setProducts($productsArray)
            ->setInputElementName($inputName)
        ;
    }*/


    /**
     * Add an extra title to the end or one from the end, or remove all
     *
     * Usage examples:
     * $this->_title('foo')->_title('bar');
     * => bar / foo / <default title>
     *
     * $this->_title()->_title('foo')->_title('bar');
     * => bar / foo
     *
     * $this->_title('foo')->_title(false)->_title('bar');
     * bar / <default title>
     *
     * @see self::_renderTitles()
     * @param string|false|-1|null $text
     * @return Mage_Core_Controller_Varien_Action
     */
    protected function _title($text = null, $resetIfExists = true)
    {
        if (is_string($text)) {
            $this->_titles[] = $text;
        } elseif (-1 === $text) {
            if (empty($this->_titles)) {
                $this->_removeDefaultTitle = true;
            } else {
                array_pop($this->_titles);
            }
        } elseif (empty($this->_titles) || $resetIfExists) {
            if (false === $text) {
                $this->_removeDefaultTitle = false;
                $this->_titles = array();
            } elseif (null === $text) {
                $this->_removeDefaultTitle = true;
                $this->_titles = array();
            }
        }
        return $this;
    }
}
