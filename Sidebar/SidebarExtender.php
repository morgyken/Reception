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

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;

/**
 * Description of SidebarExtender
 *
 * @author Samuel Dervis <samueldervis@gmail.com>
 */
class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender {

    public function extendWith(\Maatwebsite\Sidebar\Menu $menu) {
        $menu->group('Dashboard', function (Group $group) {
            $group->item('Reception', function (Item $item) {
                $item->weight(1);
                $item->icon('fa fa-ambulance');

                $item->item('New Patient', function (Item $item) {
                    $item->icon('fa fa-medkit');
                    $item->route('reception.add_patient');
                });
                $item->item('Manage Patients', function (Item $item) {
                    $item->icon('fa fa-h-square');
                    $item->route('reception.manage_patients');
                });
                $item->item('Appointment Scheduler', function (Item $item) {
                    $item->icon('fa fa-calendar-o');
                    $item->route('reception.appointments');
                });
                $item->item('Checkin Patient', function (Item $item) {
                    $item->icon('fa fa-sign-in');
                    $item->route('reception.checkin');
                });
                $item->item('Checked In Patients', function (Item $item) {
                    $item->icon('fa fa-calendar-check-o');
                    $item->route('reception.patients_queue');
                });
                $item->item('Calendar', function (Item $item) {
                    $item->icon('fa fa-calendar');
                    $item->route('reception.calendar');
                });
                $item->item('Patient Docs', function (Item $item) {
                    $item->icon('fa fa-file-text');
                    $item->route('reception.patient_documents');
                });
            });
        });
        return $menu;
    }

}
