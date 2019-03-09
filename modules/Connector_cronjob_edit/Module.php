<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_cronjob_edit;

use psc7helper\App\Connector\CronjobHelper;
use psc7helper\App\Date\Date;
use psc7helper\App\Form\FormValidator;
use psc7helper\App\Http\Request;
use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * helper.
     *
     * @var ojbect
     */
    private $helper;

    /**
     * requests.
     *
     * @var array
     */
    private $requests;

    /**
     * formValidato.
     *
     * @var object
     */
    private $formValidator;

    /**
     * id.
     *
     * @var int
     */
    private $id;

    /**
     * setProperties.
     *
     * @return $this
     */
    private function setProperties()
    {
        $this->helper = new CronjobHelper();
        $this->requests = Request::getArguments();
        $requests = $this->requests;
        $this->formValidator = new FormValidator($requests);
        if (isset($requests['param']) && ! empty($requests['param'])) {
            $this->id = (int) $requests['param'];
        }

        return $this;
    }

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('cronjob_edit_cardtitle'), false);
        $this->setProperties();
        $helper = $this->helper;
        $requests = $this->requests;
        $form = $this->formValidator;
        $this->setPlaceholder('message', ' ', false);
        if ($form->isValid() && array_key_exists('formname', $requests) && 'cronjobedit' == $requests['formname'] && array_key_exists('cronjobid', $requests)) {
            if ($helper->updateCronjob($requests['cronjobid'], $requests['next'], $requests['interval'], $requests['disable_on_error'], $requests['inform_mail'])) {
                $this->setPlaceholder('message', '<div class="alert alert-success">' . __('cronjob_edit_message_update_succsess') . '</div>', true);
            } else {
                $this->setPlaceholder('message', '<div class="alert alert-danger">' . __('cronjob_edit_message_update_error') . '</div>', true);
            }
        }
        if ($form->isValid() && array_key_exists('formname', $requests) && 'cronjobdeactivation' == $requests['formname'] && array_key_exists('cronjobid', $requests)) {
            if ($helper->deactivateCronjob($requests['cronjobid'])) {
                $this->setPlaceholder('message', '<div class="alert alert-success">' . __('cronjob_edit_message_deactivation_succsess') . '</div>', true);
            } else {
                $this->setPlaceholder('message', '<div class="alert alert-danger">' . __('cronjob_edit_message_deactivation_error') . '</div>', true);
            }
        }
        if ($form->isValid() && array_key_exists('formname', $requests) && 'cronjobactivation' == $requests['formname'] && array_key_exists('cronjobid', $requests)) {
            if ($helper->activateCronjob($requests['cronjobid'])) {
                $this->setPlaceholder('message', '<div class="alert alert-success">' . __('cronjob_edit_message_activation_succsess') . '</div>', true);
            } else {
                $this->setPlaceholder('message', '<div class="alert alert-danger">' . __('cronjob_edit_message_activation_error') . '</div>', true);
            }
        }
        $cronjob = $helper->getCronjobById($this->id);
        $name = str_replace('PlentyConnector ', '', $cronjob['name']);
        $status = 0;
        switch ($helper->getStatus($name, $cronjob['active'], Date::datetimeToTimestamp($cronjob['end']))) {
            case 0:
                $status = '<span class="badge badge-danger">' . __('cronjob_edit_status_inactive') . '</span>';
                break;
            case 1:
                $status = '<span class="badge badge-success">' . __('cronjob_edit_status_active') . '</span>';
                break;
            case 2:
                $status = '<span class="badge badge-danger">' . __('cronjob_edit_status_notrunning') . '</span>';
                break;
        }
        $this->setPlaceholder('abort_link', __('cronjob_edit_abort_href'), false);
        $this->setPlaceholder('abort_text', __('cronjob_edit_abort_text'), false);
        $this->setPlaceholder('id', $cronjob['id'], false);
        $this->setPlaceholder('name', $cronjob['name'], false);
        $this->setPlaceholder('status', $status, true);
        if ($cronjob['active']) {
            $this->setPlaceholder('next_label', __('cronjob_edit_next_label'), false);
            $this->setPlaceholder('next', $cronjob['next'], false);
            $this->setPlaceholder('next_help', __('cronjob_edit_next_help'), false);
            $this->setPlaceholder('interval_label', __('cronjob_edit_interval_label'), false);
            $this->setPlaceholder('interval', $cronjob['interval'], false);
            $this->setPlaceholder('interval_help', __('cronjob_edit_interval_help'), false);
            $this->setPlaceholder('disable_on_error_label', __('cronjob_edit_disable_on_error_label'), false);
            $this->setPlaceholder('disable_on_error', $cronjob['disable_on_error'], false);
            $this->setPlaceholder('disable_on_error_help', __('cronjob_edit_disable_on_error_help'), false);
            $this->setPlaceholder('inform_mail_label', __('cronjob_edit_inform_mail_label'), false);
            $this->setPlaceholder('inform_mail', ($cronjob['inform_mail']) ? $cronjob['inform_mail'] : ' ', false);
            $this->setPlaceholder('inform_mail_help', __('cronjob_edit_inform_mail_help'), false);
            $this->setPlaceholder('submit_text', __('cronjob_edit_btn_submit_text'), false);
            $this->setPlaceholder('deactivate_btn_text', __('cronjob_edit_btn_deactivate_text'), false);
            $this->setTemplate('view');
        } else {
            $this->setPlaceholder('inactive_text', __('cronjob_edit_inactive_text'), false);
            $this->setPlaceholder('inactive_btn_text', __('cronjob_edit_btn_inactive_text'), false);
            $this->setTemplate('inactive');
        }
        $module = $this->renderModule();

        return $module;
    }
}
