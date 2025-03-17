<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\UserStatusComposer;
use App\Repository\Category\CategoryRepository;
use App\Repository\Category\CategoryInterface;
use App\Repository\NewsMenu\NewsMenuRepository;
use App\Repository\NewsMenu\NewsMenuInterface;
use App\Repository\Role\RoleRepository;
use App\Repository\Role\RoleInterface;
use App\Repository\NewsDetail\NewsDetailRepository;
use App\Repository\NewsDetail\NewsDetailInterface;
use App\Repository\Menu\MenuRepository;
use App\Repository\Menu\MenuInterface;
use App\Repository\Permission\PermissionRepository;
use App\Repository\Permission\PermissionInterface;
use App\Repository\VideoNews\VideoNewsRepository;
use App\Repository\VideoNews\VideoNewsInterface;
use App\Repository\ShortVideoNews\ShortVideoNewsRepository;
use App\Repository\ShortVideoNews\ShortVideoNewsInterface;
use App\Repository\Page\PageRepository;
use App\Repository\Page\PageInterface;
use App\Repository\Seo\SeoRepository;
use App\Repository\Seo\SeoInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\UserInterface;
use App\Repository\Region\RegionRepository;
use App\Repository\Region\RegionInterface;
use Config;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryInterface::class,CategoryRepository::class);
        $this->app->bind(RoleInterface::class,RoleRepository::class);
        $this->app->bind(NewsMenuInterface::class,NewsMenuRepository::class);
        $this->app->bind(NewsDetailInterface::class,NewsDetailRepository::class);
        $this->app->bind(PermissionInterface::class,PermissionRepository::class);
        $this->app->bind(MenuInterface::class,MenuRepository::class);
        $this->app->bind(VideoNewsInterface::class,VideoNewsRepository::class);
        $this->app->bind(ShortVideoNewsInterface::class,ShortVideoNewsRepository::class);
        $this->app->bind(PageInterface::class,PageRepository::class);
        $this->app->bind(SeoInterface::class,SeoRepository::class);
        $this->app->bind(UserInterface::class,UserRepository::class);
        $this->app->bind(RegionInterface::class,RegionRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $data = [
            'driver'        => getDataFromSetting('mail_driver'),
            'host'          => getDataFromSetting('smtp_host'),
            'port'          => getDataFromSetting('smtp_port'),
            'encryption'    => getDataFromSetting('email_encryption'),
            'username'      => getDataFromSetting('smtp_user'),
            'password'      => getDataFromSetting('smtp_pass'),
            'from'          => [
                                    'address' => getDataFromSetting('mail_from'),
                                    'name' => getDataFromSetting('mail_from_name'),
                                ],
        ];

        Config::set('mail', $data);
        View::composer('*', UserStatusComposer::class);
    }
}
