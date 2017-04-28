<?php

namespace Ignite\Reception\Providers;

use Ignite\Reception\Library\ReceptionFunctions;
use Ignite\Reception\Repositories\ReceptionRepository;
use Illuminate\Support\ServiceProvider;

class ReceptionServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot() {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerBindings();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig() {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('reception.php'),
        ]);
        $this->mergeConfigFrom(
                __DIR__ . '/../Config/config.php', 'reception'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews() {
        $viewPath = base_path('resources/views/modules/reception');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
                            return $path . '/modules/reception';
                        }, \Config::get('view.paths')), [$sourcePath]), 'reception');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations() {
        $langPath = base_path('resources/lang/modules/reception');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'reception');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'reception');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array();
    }

    private function registerBindings()    {
        $this->app->bind(ReceptionRepository::class,ReceptionFunctions::class);
    }

}
