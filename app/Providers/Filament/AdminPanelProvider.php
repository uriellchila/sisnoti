<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Pages\Dashboard;
use Filament\Widgets;
use Filament\PanelProvider;
use Pages\AsignarDocumentos;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use Filament\FontProviders\GoogleFontProvider;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\DocumentoResource\Widgets\AsignadosTable;
use App\Filament\Resources\DocumentoResource\Widgets\ReporteNotTable;
use App\Filament\Resources\DocumentoResource\Widgets\RecaudacionChart;
use App\Filament\Resources\DocumentoResource\Widgets\AvanceDiarioChart;
use App\Filament\Resources\DocumentoResource\Widgets\MiReporteNotTable;
use App\Filament\Resources\DocumentoResource\Widgets\TipoNotificacionChart;
use App\Filament\Resources\DocumentoResource\Widgets\AvanceNotificacionesChart;
use App\Filament\Resources\DocumentoResource\Widgets\NotiTipoNotificacionChart;
use App\Filament\Resources\DocumentoNotificadorResource\Widgets\TipoNotificacion;
use App\Filament\Resources\DocumentoResource\Widgets\RankinNotificadoresOverview;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Resources\DocumentoNotificadorResource\Widgets\NotificacionesNoti;
use App\Filament\Resources\DocumentoNotificadorResource\Widgets\NotificadoresChart;
use App\Filament\Resources\DocumentoNotificadorResource\Widgets\NotificacionesTabla;
use App\Filament\Resources\DocumentoResource\Widgets\AvanceDiarioNotificadoresChart;
use App\Filament\Resources\DocumentoResource\Widgets\EstadisticaNotificadorOverview;
use App\Filament\Resources\DocumentoResource\Widgets\NotificacionDocumentosOverview;
use App\Filament\Resources\DocumentoResource\Widgets\NotificacionesNotificadorChart;
use App\Filament\Resources\DevolucionDocumentoResource\Widgets\DevolucionDocumentosTable;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            //->navigation(false)
            //->topNavigation()
            ->colors([
                'primary' => Color::Purple,
                //'primary' => Color::Emerald,
                
                'info2' => Color::Emerald,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                //'primary' => Color::Amber,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            //->font('Roboto')
            //->font('Hind', provider: GoogleFontProvider::class)
            ->font('Nunito Sans', provider: GoogleFontProvider::class)
            //->font('Google Sans', provider: GoogleFontProvider::class)
            ->brandName('Sis Notificaciones')
            ->favicon(asset('favicon.png'))
            ->brandLogoHeight('1.8rem')
            ->navigationGroups([
                NavigationGroup::make('Mantenimiento')
                     ->label('mantenimiento'),
                /*NavigationGroup::make('asignardocumento')
                     ->label('mantenimientossss'),*/
                NavigationGroup::make()
                     ->label('Roles y Permisos')
                     ->collapsed('false'),
                
                
            ])
            /*->navigationItems([
                NavigationItem::make()
                    ->label('Asignar')
                    ->visible(fn(): bool => Auth::user()->can('view-analytics'))
                    ->icon('heroicon-o-presentation-chart-line'),
            ])*/
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->topbar(true)
            //->profile(EditProfile::class)
            ->profile(isSimple: false)
            ->profile(EditProfile::class)
            ->login()
            //->brandLogo(asset('logo.png'))
            //->brandLogo(fn () => view('filament.pages.logo'))
            //->registration()
            //->passwordReset()
            //->emailVerification()
            //->profile()
            ->authGuard('web')
            ->breadcrumbs(true)
            //->spa()
            ->unsavedChangesAlerts()
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                //Pages\AsignarDocumentos::class,
            ])
            //->path('app')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                MiReporteNotTable::class,
                //EstadisticaNotificadorOverview::class,
                NotificacionDocumentosOverview::class,
                
               
                //NotificadoresChart::class,
                RankinNotificadoresOverview::class,
                AvanceDiarioChart::class,
                //AvanceDiarioNotificadoresChart::class,
		        TipoNotificacionChart::class,
                NotificacionesNotificadorChart::class,
                //DevolucionDocumentosTable::class,
		        AvanceNotificacionesChart::class,
                //NotificacionesTabla::class,
                NotiTipoNotificacionChart::class,
                //RecaudacionChart::class,
                //Widgets\FilamentInfoWidget::class,
                //NotificacionesNoti::class,
                
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
