<?php
namespace Nayjest\Menu;

use App;
use HTML;
use Blade;
use Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('nayjest/menu');

        $link = new Link;
        App::instance('menu_link', $link);
        HTML::macro('menuLink', [$link, 'make']);

        # extending Blade is optional feature
        # usage in templates:  @menu('menu::footer')
        if (Config::get('menu::extend_blade', true)) {
            $function_name = Config::get('menu::blade_function', 'menu');
            $this->extendBlade($function_name);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    protected function extendBlade($functin_name)
    {
        Blade::extend(function($view, $compiler) use ($functin_name)
        {
            $pattern = $compiler->createMatcher($functin_name);
            return preg_replace(
                $pattern,
                '$1<?php echo Nayjest\Menu\Facades\Menu::make($2); ?>',
                $view
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}