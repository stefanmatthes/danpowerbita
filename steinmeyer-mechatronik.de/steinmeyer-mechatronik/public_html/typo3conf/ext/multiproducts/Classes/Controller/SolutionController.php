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
 * SolutionController
 */
class SolutionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * solutionRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\SolutionRepository
     * @inject
     */
    protected $solutionRepository = NULL;

    /**
     * productRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\ProductRepository
     * @inject
     */
    protected $productRepository = NULL;

    /**
     * stmcontrollerRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\StmcontrollerRepository
     * @inject
     */
    protected $stmcontrollerRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        // cheeck ob eine solution übergeben wurde, dann weiterleitung an SHOW
        if (isset($GLOBALS['BE_USER']) && (int) $GLOBALS['BE_USER']->user['uid'] == 2) {
            echo '--- admin logged in';
                krexx($_REQUEST['tx_multiproducts_solutions']['solution']);
        }
     //   echo ".".$_REQUEST['tx_multiproducts_solutions']['solution'];
        if ($_REQUEST['tx_multiproducts_solutions']['solution']) {
            $solution= $_REQUEST['tx_multiproducts_solutions']['solution'];
            echo "<!-- solution UID ".$solution." -->";
            $this->redirect('show', 'Solution', NULL, ['solution' => $solution]);
        } else {

        }
        $solutions = $this->solutionRepository->findAll();
        foreach ($solutions as $solution) {
            $solution_title = strstr($solution->getTitle(), '-', false);
            $number = substr($solution->getTitle(), 0, 4);
            if (is_numeric($number)) {
                $solution_number = substr($solution->getTitle(), 0, 13);
                $solution_title = substr($solution->getTitle(), 14);
                echo $solution->getTitle() . ':' . $solution_number . ' # ' . $solution_title . '<br>';
                $solution->setTitle($solution_title);
                $solution->setNumber($solution_number);
                //   $solutions_with_this_product = $this->solutionRepository->findProductsInside($product_basic);
                //  foreach( $solutions_with_this_product as $solution_with_this_product) {
                //      echo "<br><b>".$solution_with_this_product->getTitle()."</b><br>".$solution_with_this_product->getText();
                //     $solution_with_this_product->addProduct($product);
                $this->solutionRepository->update($solution);
            }
        }
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->view->assign('solutions', $solutions);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solution $solution
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Solution $solution)
    {
        if (isset($GLOBALS['BE_USER']) && (int) $GLOBALS['BE_USER']->user['uid'] == 2) {
            echo '--- admin logged in';
        //    krexx($controller_for_this_solution);
        }
        $solution_downloads_localized = $this->solutionRepository->findDownloadsByUid($solution->getUid(), 'file');
        //x var_dump( $solution_downloads_localized);
        // Welcher Controller?
        // echo "sol".$solution->getUid();
        $controller_for_this_solution = $this->stmcontrollerRepository->findControllerForThisSolution($solution->getUid());

        $this->view->assign('downloads_localized', $solution_downloads_localized);
        $this->view->assign('solution', $solution);
        $this->view->assign('stmcontrollers', $controller_for_this_solution);
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
     * @param \Emagio\Multiproducts\Domain\Model\Solution $newSolution
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Solution $newSolution)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->solutionRepository->add($newSolution);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solution $solution
     * @ignorevalidation $solution
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Solution $solution)
    {
        $this->view->assign('solution', $solution);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solution $solution
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Solution $solution)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->solutionRepository->update($solution);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solution $solution
     * @return void
     */
    public function deleteAction(\Emagio\Multiproducts\Domain\Model\Solution $solution)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->solutionRepository->remove($solution);
        $this->redirect('list');
    }
}
