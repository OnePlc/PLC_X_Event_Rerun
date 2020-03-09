<?php
/**
 * RerunController.php - Main Controller
 *
 * Main Controller for Event Rerun Plugin
 *
 * @category Controller
 * @package Event\Rerun
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Event\Rerun\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Event\Model\EventTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class RerunController extends CoreEntityController {
    /**
     * Event Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;

    /**
     * EventController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param EventTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,EventTable $oTableGateway,$oServiceManager) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'eventrerun-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    public function attachRerunForm($oItem = false) {
        $oForm = CoreEntityController::$aCoreTables['core-form']->select(['form_key'=>'eventrerun-single']);

        $aFields = [];
        $aUserFields = CoreEntityController::$oSession->oUser->getMyFormFields();
        if(array_key_exists('eventrerun-single',$aUserFields)) {
            $aFieldsTmp = $aUserFields['eventrerun-single'];
            if(count($aFieldsTmp) > 0) {
                # add all contact-base fields
                foreach($aFieldsTmp as $oField) {
                    if($oField->tab == 'rerun-base') {
                        $aFields[] = $oField;
                    }
                }
            }
        }

        $aFieldsByTab = ['rerun-base'=>$aFields];

        $aReruns = [];
        $oPrimaryTicket = false;
        if($oItem) {
            # load contact addresses
            $oRerunsDB = $this->oTableGateway->fetchAll(false, ['root_event_idfs' => $oItem->getID()]);
            # get primary address
            if (count($oRerunsDB) > 0) {
                foreach ($oRerunsDB as $oEv) {
                    $aReruns[] = $oEv;
                }
            }
        }

        # Pass Data to View - which will pass it to our partial
        return [
            # must be named aPartialExtraData
            'aPartialExtraData' => [
                # must be name of your partial
                'event_rerun'=> [
                    'oReruns'=>$aReruns,
                    'oForm'=>$oForm,
                    'aFormFields'=>$aFieldsByTab,
                ]
            ]
        ];
    }

    public function attachRerunToEvent($oItem,$aRawData)
    {
        $oRootEvent = $this->oTableGateway->getSingle($aRawData['ref_idfs']);
        $oItem->label = 'Event Rerun';
        $oItem->date_start = date('Y-m-d H:i',strtotime($aRawData[$this->sSingleForm.'_date_start'].' '.$aRawData[$this->sSingleForm.'_date_start-time']));
        $oItem->date_end = date('Y-m-d H:i',strtotime($aRawData[$this->sSingleForm.'_date_end'].' '.$aRawData[$this->sSingleForm.'_date_end-time']));
        $oItem->root_event_idfs = $aRawData['ref_idfs'];
        $oItem->calendar_idfs = $oRootEvent->calendar_idfs;

        return $oItem;
    }

    public function addAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * contact-add-before (before show add form)
         * contact-add-before-save (before save)
         * contact-add-after-save (after save)
         */
        $iEventID = $this->params()->fromRoute('id', 0);

        return $this->generateAddView('eventrerun','eventrerun-single','event','view',$iEventID,['iEventID'=>$iEventID]);
    }
}
