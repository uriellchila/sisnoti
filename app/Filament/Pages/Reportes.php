<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\DocumentoResource\Widgets\AsignadosTable;
use App\Filament\Resources\DocumentoResource\Widgets\ReporteNotTable;

class Reportes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.reportes';
    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }
    protected function getHeaderWidgets(): array
    {
        return [
            ReporteNotTable::class,
            AsignadosTable::class,
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return 'Nuevo.!';
    }
    public static function getNavigationBadgeColor(): ?string
    {   
        return 'primary';
    }
}
