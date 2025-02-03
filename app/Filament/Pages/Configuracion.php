<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\Auth;

class Configuracion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = '.';
    protected static ?int $navigationSort = 0;
    protected static string $view = 'filament.pages.configuracion';
    public static function getNavigationBadge(): ?string
    {
        $td=TipoDocumento::select('nombre')->where('id', '=',Auth::user()->tipo_documento_id)->get();
        foreach($td as $t ){
            $tipodoc=$t->nombre;

        }
        return "PERFIL PARA: ". $tipodoc;

    }
    public static function getNavigationBadgeColor(): ?string
    {   
        return 'info2';
    }
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Perfil Habilitado';
    }
    
}
