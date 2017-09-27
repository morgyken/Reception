<?php

/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: Collabmed Health Platform
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */

namespace Ignite\Reception\Repositories;

use Ignite\Inpatient\Entities\Visit;

/**
 * Interface ReceptionRepository
 * @package Ignite\Reception\Repositories
 */
interface ReceptionRepository
{
    /**
     * @return mixed
     */
    public function reschedule_appointment();

    /**
     * @return mixed
     */
    public function add_appointment();

    /**
     * @return Visit
     */
    public function checkin_patient();
}
