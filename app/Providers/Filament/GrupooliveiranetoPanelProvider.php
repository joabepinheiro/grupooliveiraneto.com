<?php

namespace App\Providers\Filament;

use App\Filament\Auth\AdminLogin;
use App\Filament\Auth\GrupooliveiranetoLogin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GrupooliveiranetoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->databaseNotifications()
            ->login(GrupooliveiranetoLogin::class)
            ->databaseNotifications()
            ->unsavedChangesAlerts()
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17rem')
            ->brandLogoHeight('2rem')
            ->brandLogo(asset('images/grupooliveiraneto/logo-grupo-oliveira-neto.png'))
            ->sidebarCollapsibleOnDesktop(true)
            ->favicon(asset('images/grupooliveiraneto/favicon.png'))
            ->assets([
                Css::make('grupooliveiraneto', __DIR__ . '/../../../resources/css/grupooliveiraneto.css'),
            ])

            ->navigationItems([
                NavigationItem::make('Grupo Oliveira Neto')
                    ->url('/grupooliveiraneto')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->sort(10000000),

                NavigationItem::make('BYD Conquista')
                    ->url('/bydconquista')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->sort(10000000),

                NavigationItem::make('Movel Veículos')
                    ->url('/movelveiculos')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->sort(10000000),
            ])

            ->id('grupooliveiraneto')
            ->path('grupooliveiraneto')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Grupooliveiraneto/Resources'), for: 'App\Filament\Grupooliveiraneto\Resources')
            ->discoverPages(in: app_path('Filament/Grupooliveiraneto/Pages'), for: 'App\Filament\Grupooliveiraneto\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Grupooliveiraneto/Widgets'), for: 'App\Filament\Grupooliveiraneto\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationIcon('fas-user-shield')
                    ->navigationGroup('Configurações'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
