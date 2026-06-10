<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\PipelineFunnelWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Vite;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->globalSearch(false)
            ->brandName('OASIS')
            ->brandLogo(fn () => asset('logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon('data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" rx="6" fill="#d97706"/><text x="16" y="22" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="white" text-anchor="middle">O</text></svg>'))
            ->login(\App\Filament\Auth\Pages\Login::class)
            ->authGuard('web')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                'Master Data',
                'Penjualan & Marketing',
                'Keuangan',
                'Laporan',
                'Settings',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverviewWidget::class,
                PipelineFunnelWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn () => "<style>[x-cloak] { display: none !important; }
.fi-wi-chart .fi-section-content { padding: 0.5rem !important; }
.fi-ta-header-toolbar > div:last-child { display: inline-flex; align-items: center; gap: 0.5rem; }
.fi-ta-filters-dropdown, .fi-ta-filters-modal, .fi-ta-filters-trigger-action-ctn { order: 2 !important; }
.fi-ta-col-manager-modal, .fi-ta-col-manager-dropdown { order: 1 !important; }
.fi-simple-header { text-align: center; }
</style>
<script>
(function(){function c(){document.querySelectorAll('.fi-ac-icon-btn-action .fi-badge').forEach(function(b){b.textContent.trim()==='0'&&(b.style.display='none')})}c();(new MutationObserver(c)).observe(document.body,{childList:!0,subtree:!0});document.addEventListener('livewire:navigated',c)})();
</script>",
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn () => app(Vite::class)('resources/js/alpine-mask.js'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            function () {
                if (auth()->user()?->hasRole('super-admin')) {
                    return '';
                }

                return app('livewire')->mount('bug-report-widget');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn () => app('livewire')->mount('mang-haris-widget'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_END,
            function () {
                if (!auth()->user()?->hasRole('super-admin')) {
                    return '';
                }

                return app('livewire')->mount('bug-report-bell');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_END,
            function () {
                $url = config('oasisync.script_url');
                $user = auth()->user();

                if (!$user) {
                    return '';
                }

                if ($user->hasRole('super-admin')) {
                    $cabangs = \App\Models\Cabang::whereNotNull('google_sheet_id')
                        ->orderBy('urutan')
                        ->get();

                    if ($cabangs->isEmpty()) {
                        return '';
                    }

                    $items = '';
                    foreach ($cabangs as $c) {
                        $items .= '<a href="' . e($url) . '?cabang_id=' . $c->id . '" target="_blank" rel="noopener noreferrer" style="display:flex;align-items:center;gap:8px;padding:8px 14px;text-decoration:none;color:#374151;font-size:14px;transition:background 0.15s;" onmouseover="this.style.background=\'#f3f4f6\'" onmouseout="this.style.background=\'transparent\'">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9ca3af"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"></path></svg>
                            ' . e($c->nama) . '
                        </a>';
                    }

                    return '<div style="position:relative;display:inline-flex;align-items:center;">
                        <button onclick="var m=this.nextElementSibling;m.style.display=m.style.display===\'block\'?\'none\':\'block\'" style="width:36px;height:36px;border:none;background:transparent;cursor:pointer;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#6b7280;transition:all 0.15s;" onmouseover="this.style.background=\'#f3f4f6\';this.style.color=\'#4b5563\'" onmouseout="this.style.background=\'transparent\';this.style.color=\'#6b7280\'" title="Database Cabang">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path></svg>
                        </button>
                        <div style="display:none;position:absolute;right:0;top:100%;margin-top:4px;width:192px;background:white;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.15);z-index:50;overflow:hidden;padding:4px 0;">' . $items . '</div>
                    </div>';
                }

                $cabang = $user->cabang;
                if (!$cabang || !$cabang->google_sheet_id) {
                    return '';
                }

                $href = e($url) . '?cabang_id=' . $cabang->id;
                return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" style="width:36px;height:36px;border:none;background:transparent;cursor:pointer;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#6b7280;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background=\'#f3f4f6\';this.style.color=\'#4b5563\'" onmouseout="this.style.background=\'transparent\';this.style.color=\'#6b7280\'" title="' . e($cabang->nama) . '">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path></svg>
                </a>';
            },
        );
    }
}
