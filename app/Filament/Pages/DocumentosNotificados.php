<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;
use App\Filament\Resources\DocumentoResource\Widgets\DocumentosNotificadosTable;

class DocumentosNotificados extends Page
{
    
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Doc. Notificados';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.documentos-notificados';
    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }
    protected function getHeaderWidgets(): array
    {
        return [
            DocumentosNotificadosTable::class,
        ];
    }
    public static function getNavigationBadge(): ?string
    {
       return NotificacionDocumento::where('deleted_at',null)->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {   
        return 'success';
    }
    
}
