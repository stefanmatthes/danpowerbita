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
 * CategoryController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * categoryRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = NULL;

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
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $categories = $this->categoryRepository->findSorted();
        $this->view->assign('categories', $categories);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Category $category
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Category $category = null)
    {
        if ($category == null) {
            $pageid = intval($GLOBALS['TSFE']->id);
            // Finde die passende UID der Category dieser Seite
            $category = $this->categoryRepository->findByPageUid($pageid);
        }
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
     * action new
     *
     * @return void
     */
    public function newAction()
    {

    }

    /**
     * action create
     *
     * @param \Emagio\Multiproducts\Domain\Model\Category $newCategory
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Category $newCategory)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->categoryRepository->add($newCategory);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Category $category
     * @ignorevalidation $category
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Category $category)
    {
        $this->view->assign('category', $category);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Category $category
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Category $category)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->categoryRepository->update($category);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Emagio\Multiproducts\Domain\Model\Category $category
     * @return void
     */
    public function deleteAction(\Emagio\Multiproducts\Domain\Model\Category $category)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->categoryRepository->remove($category);
        $this->redirect('list');
    }
}
