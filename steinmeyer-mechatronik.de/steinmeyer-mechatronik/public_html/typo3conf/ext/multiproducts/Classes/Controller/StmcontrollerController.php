<?php
namespace Emagio\Multiproducts\Controller;

/***
 *
 * This file is part of the "Multiproducts" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/

/**
 * StmcontrollerController
 */
class StmcontrollerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
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
        $stmcontrollers = $this->stmcontrollerRepository->findAll();
        $this->view->assign('stmcontrollers', $stmcontrollers);
    }

    /**
     * action show
     *
     * @param \Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller
     * @return void
     */
    public function showAction(\Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller)
    {
        $this->view->assign('stmcontroller', $stmcontroller);
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
     * @param \Emagio\Multiproducts\Domain\Model\Stmcontroller $newStmcontroller
     * @return void
     */
    public function createAction(\Emagio\Multiproducts\Domain\Model\Stmcontroller $newStmcontroller)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->stmcontrollerRepository->add($newStmcontroller);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller
     * @ignorevalidation $stmcontroller
     * @return void
     */
    public function editAction(\Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller)
    {
        $this->view->assign('stmcontroller', $stmcontroller);
    }

    /**
     * action update
     *
     * @param \Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller
     * @return void
     */
    public function updateAction(\Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->stmcontrollerRepository->update($stmcontroller);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller
     * @return void
     */
    public function deleteAction(\Emagio\Multiproducts\Domain\Model\Stmcontroller $stmcontroller)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->stmcontrollerRepository->remove($stmcontroller);
        $this->redirect('list');
    }
}
