<?php

namespace App\Providers\Filament;

use App\Filament\Auth\AdminLogin;
use App\Filament\Auth\BydLogin;
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

class BydconquistaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->databaseNotifications()
            ->login(BydLogin::class)
            ->databaseNotifications()
            ->unsavedChangesAlerts()
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17rem')
            ->brandLogoHeight('2rem')
            ->brandLogo(asset('images/byd/logo-vitoria-da-conquista.png'))
            ->sidebarCollapsibleOnDesktop(true)
            ->favicon(asset('images/byd/favicon.ico'))
            ->assets([
                Css::make('bydconquista', __DIR__ . '/../../../resources/css/bydconquista.css'),
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

            ->id('bydconquista')
            ->path('bydconquista')
            ->colors([
                'primary' => Color::Gray,
            ])
            ->discoverResources(in: app_path('Filament/Bydconquista/Resources'), for: 'App\Filament\Bydconquista\Resources')
            ->discoverPages(in: app_path('Filament/Bydconquista/Pages'), for: 'App\Filament\Bydconquista\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Bydconquista/Widgets'), for: 'App\Filament\Bydconquista\Widgets')
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
