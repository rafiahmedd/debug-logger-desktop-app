<?php

namespace App\Providers;


use Native\Laravel\Facades\Window;
use Native\Laravel\Menu\Menu;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Dialog;

class NativeAppServiceProvider
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Menu::new()
            ->appMenu()
            ->editMenu()
            ->viewMenu()
            ->windowMenu()
            ->register();

        Window::open()
            ->title('WP Logger')
            ->width(800)
            ->height(800);
    }
}
