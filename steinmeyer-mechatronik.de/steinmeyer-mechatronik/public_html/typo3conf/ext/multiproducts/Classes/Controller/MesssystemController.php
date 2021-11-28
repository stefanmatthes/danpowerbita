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
 * MesssystemController
 */
class MesssystemController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * messsystemRepository
     *
     * @var \Emagio\Multiproducts\Domain\Repository\MesssystemRepository
     * @inject
     */
    protected $messsystemRepository = NULL;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $messsystems = $this->messsystemRepository->findAll();
        $this->view->assign('messsystems', $messsystems);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Messsystem $messsystem
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Messsystem $messsystem)
    {
        $this->view->assign('messsystem', $messsystem);
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
     * @param \Emagio\Multiproducts\Domain\Model\Messsystem $newMesssystem
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Messsystem $newMesssystem)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->messsystemRepository->add($newMesssystem);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Messsystem $messsystem
     * @ignorevalidation $messsystem
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Messsystem $messsystem)
    {
        $this->view->assign('messsystem', $messsystem);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Messsystem $messsystem
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Messsystem $messsystem)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->messsystemRepository->update($messsystem);
        $this->redirect('list');
    }
}
