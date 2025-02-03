<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\DocumentoResource\Widgets\DocumentosAsignarTable;
use App\Filament\Resources\DocumentoResource\Widgets\DocumentosAsignadosTable;

class DocumentosPendientes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Doc. Pendientes';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.documentos-pendientes';
    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }
    protected function getHeaderWidgets(): array
    {
        return [
            /*ReporteNotTable::class,
            AsignadosTable::class,*/
            DocumentosAsignarTable::class,
            DocumentosAsignadosTable::class,
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return '';
    }
    public static function getNavigationBadgeColor(): ?string
    {   
        return 'info';
    }
}
