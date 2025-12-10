<?php

namespace App\Providers\Filament;

use App\Filament\Auth\AdminLogin;
use App\Filament\Auth\GrupooliveiranetoLogin;
use App\Filament\Grupooliveiraneto\Pages\Board;
use App\Filament\Grupooliveiraneto\Pages\PainelDeTarefas;
use App\Filament\Movelveiculos\Widgets\EstatisticasGerais;
use Filament\Facades\Filament;
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
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GrupooliveiranetoPanelProvider extends PanelProvider
{
    public function canAccessPanel(Authenticatable $user): bool
    {
        return $user->empresas->contains('id', 1);
    }

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
            ->viteTheme('resources/css/filament/grupooliveiraneto/theme.css')
            ->assets([
                Css::make('grupooliveiraneto', __DIR__ . '/../../../resources/css/grupooliveiraneto.css'),
            ])
            ->navigationItems([
                NavigationItem::make('Grupo Oliveira Neto')
                    ->url('/grupooliveiraneto')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->visible(function (){
                        return auth()->user()->empresas->contains('id', 1)  && Filament::getCurrentPanel()->getId() != 'grupooliveiraneto';;
                    })
                    ->sort(10000000),

                NavigationItem::make('Movel Veículos')
                    ->url('/movelveiculos')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->visible(function (){
                        return auth()->user()->empresas->contains('id', 2);
                    })
                    ->sort(10000001),

                NavigationItem::make('BYD Conquista')
                    ->url('/bydconquista')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->visible(function (){
                        return auth()->user()->empresas->contains('id', 3);
                    })
                    ->sort(10000002),

                NavigationItem::make('Administrador')
                    ->url('/admin')
                    ->icon('fas-sitemap')
                    ->group('Mudar para')
                    ->visible(function (){
                        //return auth()->user()->hasRole('super_admin');
                    })
                    ->sort(10000003),
            ])

            ->id('grupooliveiraneto')
            ->path('grupooliveiraneto')
            ->colors([
                'primary' => [
                    50  => 'oklch(0.97 0.02 259)',  // quase branco
                    100 => 'oklch(0.92 0.04 259)',
                    200 => 'oklch(0.82 0.07 259)',
                    300 => 'oklch(0.70 0.10 259)',
                    400 => 'oklch(0.55 0.13 259)',
                    500 => 'oklch(0.28 0.15 259)',  // cor base (≈ #001e50)
                    600 => 'oklch(0.26 0.14 259)',
                    700 => 'oklch(0.24 0.12 259)',
                    800 => 'oklch(0.22 0.10 259)',
                    900 => 'oklch(0.16 0.08 259)',
                    950 => 'oklch(0.10 0.06 259)',  // quase preto
                ],

                'grupooliveiraneto' => Color::Gray,
                'movelveiculos' => Color::Blue,
                'byd' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Grupooliveiraneto/Resources'), for: 'App\Filament\Grupooliveiraneto\Resources')
            ->discoverPages(in: app_path('Filament/Grupooliveiraneto/Pages'), for: 'App\Filament\Grupooliveiraneto\Pages')
            ->pages([
                \App\Filament\Grupooliveiraneto\Pages\Dashboard::class,
                PainelDeTarefas::class
            ])
            ->discoverWidgets(in: app_path('Filament/Grupooliveiraneto/Widgets'), for: 'App\Filament\Grupooliveiraneto\Widgets')
            ->widgets([

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

            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
