<?php

/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */

namespace Ignite\Reception\Sidebar;

use Ignite\Core\Contracts\Authentication;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\SidebarExtender as Panda;

/**
 * Description of SidebarExtender
 *
 * @author Samuel Dervis <samueldervis@gmail.com>
 */
class SidebarExtender implements Panda {

    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * SidebarExtender constructor.
     * @param Authentication $auth
     */
    public function __construct(Authentication $auth) {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu) {
        $menu->group('Dashboard', function (Group $group) {
            $group->item('Reception', function (Item $item) {
                $item->weight(1);
                $item->authorize($this->auth->hasAccess('reception.*'));
                $item->icon('fa fa-ambulance');

                $item->item('New Patient', function (Item $item) {
                    $item->icon('fa fa-medkit');
                    $item->route('reception.add_patient');
                    $item->authorize($this->auth->hasAccess('reception.patients.add'));
                });
                $item->item('Manage Patients', function (Item $item) {
                    $item->icon('fa fa-h-square');
                    $item->route('reception.show_patients');
                    $item->authorize($this->auth->hasAccess('reception.patients.manage'));
                });
                $item->item('Appointment Scheduler', function (Item $item) {
                    $item->icon('fa fa-calendar-o');
                    $item->route('reception.appointments');
                    $item->authorize($this->auth->hasAccess('reception.patients.manage'));
                });
                $item->item('Checkin Patient', function (Item $item) {
                    $item->icon('fa fa-sign-in');
                    $item->route('reception.checkin');
                    $item->authorize($this->auth->hasAccess('reception.patients.checkin'));
                });

                $item->item('External Order Queue', function (Item $item) {
                    $item->icon('fa fa-puzzle-piece');
                    $item->route('reception.external_order_queue');
                    //$item->authorize($this->auth->hasAccess('evaluation.external'));
                });

                $item->item('Checked In Patients', function (Item $item) {
                    $item->icon('fa fa-calendar-check-o');
                    $item->route('reception.patients_queue');
                    $item->authorize($this->auth->hasAccess('reception.patients.checkins'));
                });
                $item->item('Calendar', function (Item $item) {
                    $item->icon('fa fa-calendar');
                    $item->route('reception.calendar');
                    $item->authorize($this->auth->hasAccess('reception.appointments.view'));
                });
                $item->item('Patient Docs', function (Item $item) {
                    $item->icon('fa fa-file-text');
                    $item->route('reception.patient_documents');
                    $item->authorize($this->auth->hasAccess('reception.documents.view'));
                });
            });
            $group->item('Setup', function (Item $item) {
                $item->item('Appointment Categories', function (Item $item) {
                    $item->icon('fa fa-sign-in');
                    $item->route('reception.setup.app_category');
                    $item->authorize($this->auth->hasAccess('reception.settings.view_categories'));
                    $item->weight(3);
                });
            });
        });
        return $menu;
    }

}
