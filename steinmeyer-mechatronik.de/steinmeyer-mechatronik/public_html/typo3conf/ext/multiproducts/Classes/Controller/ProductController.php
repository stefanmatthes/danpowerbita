<?php
namespace Emagio\Multiproducts\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/***
 *
 * This file is part of the "Multiproducts" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017
 *
 ***/

/**
 * ProductController
 */
class ProductController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * categoryRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = NULL;

    /**
     * stmcontrollerRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\StmcontrollerRepository
     * @inject
     */
    protected $stmcontrollerRepository = null;

    /**
     * antriebsartRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\AntriebsartRepository
     * @inject
     */
    protected $antriebsartRepository = NULL;

    /**
     * messsystemRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\MesssystemRepository
     * @inject
     */
    protected $messsystemRepository = NULL;

    /**
     * productRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\ProductRepository
     * @inject
     */
    protected $productRepository = NULL;

    /**
     * solutionRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\SolutionRepository
     * @inject
     */
    protected $solutionRepository = NULL;

    /**
     * action list
     *
     * @param \Emagio\Multiproducts\Domain\Model\Category $category
     * @return void
     */
    public function listAction(\Emagio\Multiproducts\Domain\Model\Category $category = null)
    {
        //Benachrichtigung Admin
        if ($category == null) {
            $pageid = intval($GLOBALS['TSFE']->id);
            // Finde die passende UID der Category dieser Seite
            $category = $this->categoryRepository->findByPageUid($pageid);
        }
        //  mail('s.matthes@emagio.de', 'STM listAction'.$pageid, 'listAction Seite:'.$pageid);
        $this->view->assign('category', $category);
        //Find products in this category
        $category_id = $category->getUid();
        //    echo $category_id;
        $products = $this->productRepository->findByCategory($category_id);
        // $antriebe = $this->antriebsartRepository->findSorted();
        $antriebe = $this->antriebsartRepository->findAntriebsartByCategory($category_id);
        $messsysteme = $this->messsystemRepository->findMesssystemByCategory($category_id);
        $title = '<title>' . $category->getName() . ' : Steinmeyer Mechatronik Dresden</title>';
        $metaDescription = '<meta name="description" content="' . $category->getContent() . '">';
        $this->response->addAdditionalHeaderData($title . PHP_EOL . $metaDescription);
        $this->view->assign('products', $products);
        $this->view->assign('antriebe', $antriebe);
        $this->view->assign('messsysteme', $messsysteme);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Product $product
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Product $product)
    {
        $product_basic = strstr($product->getName(), '-', true);
        $title = '<title>' . $product->getName() . ' - ' . $product->getCategory()->getName() . ' : Steinmeyer Mechatronik Dresden</title>';
        $metaDescription = '<meta name="description" content="' . $product->getName() . ' - ' . $product->getCategory()->getName() . '">';
        $this->response->addAdditionalHeaderData($title . PHP_EOL . $metaDescription);
        //    echo "searching for ".$product_basic;
        if ($product_basic != '') {
          //  $solutions_with_this_product = $this->solutionRepository->findProductsInside($product_basic);   Umgebaut auf UID
            $solutions_with_this_product = $this->solutionRepository->findProductsInside($product->getUid());
            //foreach( $solutions_with_this_product as $solution_with_this_product) {
            //   echo $product->getUid()." - ".$product->getName()." - ".$solution_with_this_product->getUid()." - ".$solution_with_this_product->getTitle()."<br>";
            // $solution_with_this_product->addProduct($product);
            //   $this->solutionRepository->update($solution_with_this_product);
            //  $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            //  $persistenceManager->persistAll();
            //  var_dump($solution_with_this_product);
            //  die();
            // }
            // print_r($solutions_with_this_product);
            //die();
            //   $solutions = $this->solutionRepository->findByProduct($product->getUid());
            $this->view->assign('solutions', $solutions_with_this_product);
        }
        //    $product_downloads_localized = $this ->productRepository->findDownloadsByUid($product->getUid(),'downloadfile');
        //   $this->view->assign('downloads_localized', $product_downloads_localized);
        $product_downloads_localized = $this->productRepository->findDownloadsByUid($product->getUid(), 'downloadfile');
        $this->view->assign('downloads_localized', $product_downloads_localized);

        if (isset($GLOBALS['BE_USER']) && (int) $GLOBALS['BE_USER']->user['uid'] == 2) {
            //     var_dump($product_downloads_localized);
            //       echo " ----------------------------------- <br> ";
            // $product_downloads_localized = $this ->productRepository->findDownloadsByUid($product->getUid(),'downloadfile');
            // $this->view->assign('downloads_localized', $product_downloads_localized);
            // var_dump($product_multilanguage);
            //$path="http://www.steinmeyer-mechatronik.de/fileadmin/extensions/multiproducts/Resources/Public/Downloads/product_downloads/";
            // var_dump($product_downloads_localized);
            krexx($solutions_with_this_product);
            echo 'admin logged in';
        }
        $this->view->assign('product', $product);
        // Welcher Controller?
        $controller_for_this_product = $this->stmcontrollerRepository->findControllerForThisProduct($product->getUid());
        // krexx($product);
        $this->view->assign('stmcontrollers', $controller_for_this_product);
        $this->view->assign('product', $product);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        // Nutzung als Script zum Verknüpfen von Solutions und Products
        $products = $this->productRepository->findSorted();
        foreach ($products as $product) {
            $product_basic = strstr($product->getName(), '-', true);
            echo '<hr>searching for ' . $product_basic;
            if ($product_basic != '') {
                $solutions_with_this_product = $this->solutionRepository->findProductsInside($product_basic);
                foreach ($solutions_with_this_product as $solution_with_this_product) {
                    echo '<br><b>' . $solution_with_this_product->getTitle() . '</b><br>' . $solution_with_this_product->getText();
                    $solution_with_this_product->addProduct($product);
                    $this->solutionRepository->update($solution_with_this_product);
                }
            }
        }
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
    }

    /**
     * action create
     *
     * @param \Emagio\Multiproducts\Domain\Model\Product $newProduct
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Product $newProduct)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->productRepository->add($newProduct);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Product $product
     * @ignorevalidation $product
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Product $product)
    {
        $this->view->assign('product', $product);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Product $product
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Product $product)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->productRepository->update($product);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Emagio\Multiproducts\Domain\Model\Product $product
     * @return void
     */
    public function deleteAction(\Emagio\Multiproducts\Domain\Model\Product $product)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->productRepository->remove($product);
        $this->redirect('list');
    }
}
