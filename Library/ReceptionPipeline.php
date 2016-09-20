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

namespace Ignite\Reception\Library;

use FloatingPoint\Stylist\Facades\ThemeFacade as Theme;
use Ignite\Core\Foundation\Asset\Manager\AssetManager;
use Ignite\Core\Foundation\Asset\Pipeline\AssetPipeline;

/**
 * Description of ReceptionPipeline
 *
 * @author samuel
 */
class ReceptionPipeline {
    /**
     * Featured asset pipeline for calendar
     * @param AssetPipeline $pipeline
     * @param AssetManager $manager
     */
    public static function calendar_assets(AssetPipeline $pipeline, AssetManager $manager) {
        $manager->addAsset('moments.js', Theme::url('/plugins/fullcalendar/lib/moment.min.js'));
        $manager->addAsset('fullcalendar.css', Theme::url('/plugins/fullcalendar/fullcalendar.min.css'));
        $manager->addAsset('fullcalendar.js', Theme::url('/plugins/fullcalendar/fullcalendar.min.js'));
        //add to pipeline
        $pipeline->requireJs('moments.js');
        $pipeline->requireJs('fullcalendar.js');
        $pipeline->requireCss('fullcalendar.css');
    }

}
