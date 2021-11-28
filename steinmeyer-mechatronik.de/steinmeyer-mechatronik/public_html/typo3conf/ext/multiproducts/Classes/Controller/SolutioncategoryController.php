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
 * SolutioncategoryController
 */
class SolutioncategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * solutioncategoryRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\SolutioncategoryRepository
     * @inject
     */
    protected $solutioncategoryRepository = NULL;

    /**
     * solutioncategory
     *
     * @var \Emagio\Multiproducts\Domain\Repository\SolutionRepository
     * @inject
     */
    protected $solutionRepository = NULL;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        //   $modus =$this->request->getContentObjectData();
        $solutioncategories = $this->solutioncategoryRepository->findAll();
        // $modus = $this->cObj->data["select_key"];
        //  print_r($modus);
        $this->view->assign('solutioncategories', $solutioncategories);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory
     * @param \Emagio\Multiproducts\Domain\Model\Solution $solution
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory = null,\Emagio\Multiproducts\Domain\Model\Solution $solution = null)
    {
        // cheeck ob eine solution übergeben wurde, dann weiterleitung an SHOW
        if (isset($GLOBALS['BE_USER']) && (int) $GLOBALS['BE_USER']->user['uid'] == 2) {
            echo '--- admin logged in';
krexx($solution);
        }
            if ($solution) {
                //     echo "<!-- solution UID ".$solution." -->";
                $this->forward('show', 'Solution', NULL, ['solution' => $solution]);
            //    $this->redirect('show', 'Solution', NULL, ['solution' => $solution]);
                //  $uri = $this->uriBuilder->uriFor('show', ['solution' => $solution, 'controller' => "solution"]);
              //  krexx($uri);
            } else {

            }



        if ($solutioncategory == null) {
            $pageid = intval($GLOBALS['TSFE']->id);
            // Finde die passende UID der Category dieser Seite
            $solutioncategory = $this->solutioncategoryRepository->findByPageUid($pageid);
        //    krexx($solutioncategory);
        }
        $solutions = $this->solutionRepository->findByCategory($solutioncategory);
        $this->view->assign('solutions', $solutions);
        $this->view->assign('solutioncategory', $solutioncategory);
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
     * @param \Emagio\Multiproducts\Domain\Model\Solutioncategory $newSolutioncategory
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Solutioncategory $newSolutioncategory)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->solutioncategoryRepository->add($newSolutioncategory);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory
     * @ignorevalidation $solutioncategory
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory)
    {
        $this->view->assign('solutioncategory', $solutioncategory);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->solutioncategoryRepository->update($solutioncategory);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory
     * @return void
     */
    public function deleteAction(\Emagio\Multiproducts\Domain\Model\Solutioncategory $solutioncategory)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->solutioncategoryRepository->remove($solutioncategory);
        $this->redirect('list');
    }
}
