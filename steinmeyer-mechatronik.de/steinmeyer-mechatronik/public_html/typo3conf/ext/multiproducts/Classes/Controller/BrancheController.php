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
 * BrancheController
 */
class BrancheController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * brancheRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\BrancheRepository
     * @inject
     */
    protected $brancheRepository = NULL;


    /**
     * PageRepository
     *
     * @var \TYPO3\CMS\Frontend\Page\PageRepository
     * @inject
     */
    protected $pageRepository = NULL;


    /**
     * solutionRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\SolutionRepository
     * @inject
     */
    protected $solutionRepository = NULL;

    /**
     * @param \Emagio\Multiproducts\Domain\Repository\SolutionRepository
     */
    public function injectSolutionRepository(\Emagio\Multiproducts\Domain\Repository\SolutionRepository $solutionRepository) {
        $this->solutionRepository = $solutionRepository;
    }




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
        $branches = $this->brancheRepository->findAll();
        $this->view->assign('branches', $branches);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Branche $branche
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Branche $branche = null)
    {
        // $this->view->assign('branche', $branche);
        if ($branche == null) {
            $pageid = intval($GLOBALS['TSFE']->id);
            //   echo "pageuid:". $pageid;
            // Finde die passende UID der Category dieser Seite
            $branche = $this->brancheRepository->findByPageUid($pageid);
        }
        //    echo "searching";
        $depth = 999999;
        $parent= 489;

        $queryGenerator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Database\\QueryGenerator' );

        $childPids_Ebene1 = $queryGenerator->getTreeList($parent, 1, 0, 1); //Will be a string like 1,2,3

        $childPids_Ebene1_Array = $thePostIdArray = explode(',', $childPids_Ebene1);

        $array_branchen_products = [];
        $counter = 1;
        $current_language = $GLOBALS['TSFE']->lang;
        foreach ($childPids_Ebene1_Array as $ebene1) {
            $array_branchen_products_single = [];
            if ($current_language == "de") $array_branchen_products_single["page"] = $this->pageRepository->getPage( $childPids_Ebene1_Array[$counter]);
            if ($current_language == "en") $array_branchen_products_single["page"] = $this->pageRepository->getPageOverlay( $childPids_Ebene1_Array[$counter],1 );
         //   $array_branchen_products_single["page_overlay"] = $this->pageRepository->getPageOverlay( $childPids_Ebene1_Array[$counter],1 );

            if (isset($GLOBALS['BE_USER']) && (int) $GLOBALS['BE_USER']->user['uid'] == 12) {
                //             echo '--- admin logged in';
                //            krexx($array_branchen_products_single["page"]);
            }
            $childPids= $queryGenerator->getTreeList($childPids_Ebene1_Array[$counter], $depth, 0, 1); //Will be a string like 1,2,3
            $array_branchen_products_single["products"]  = $this->solutionRepository->findProductsAndSolutionsByBranchenUid($branche->getUid(),$childPids);
            $array_branchen_products[]=$array_branchen_products_single;
            $counter ++;
        }
        $this->view->assign('array_branchen_products', $array_branchen_products);

        $this->view->assign('branche', $branche);
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
     * @param \Emagio\Multiproducts\Domain\Model\Branche $newBranche
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Branche $newBranche)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->brancheRepository->add($newBranche);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Branche $branche
     * @ignorevalidation $branche
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Branche $branche)
    {
        $this->view->assign('branche', $branche);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Branche $branche
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Branche $branche)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->brancheRepository->update($branche);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Emagio\Multiproducts\Domain\Model\Branche $branche
     * @return void
     */
    public function deleteAction(\Emagio\Multiproducts\Domain\Model\Branche $branche)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->brancheRepository->remove($branche);
        $this->redirect('list');
    }
}
