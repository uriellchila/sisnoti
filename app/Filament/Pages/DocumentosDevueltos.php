<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\DevolucionDocumento;
use Illuminate\Support\Facades\Auth;

class DocumentosDevueltos extends Page
{
    
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationLabel = 'Doc. Devueltos';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.documentos-devueltos';
    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }
    protected function getHeaderWidgets(): array
    {
        return [
            /*ReporteNotTable::class,
            AsignadosTable::class,*/
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return DevolucionDocumento::where('deleted_at',null)->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)->count();

    }
    public static function getNavigationBadgeColor(): ?string
    {   
        return 'info';
    }
}
