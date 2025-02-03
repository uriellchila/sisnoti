<?php

namespace App\Filament\Resources\NotificacionDocumentoResource\Pages;

use Actions\Action;
use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\NotificacionDocumentoResource;
use App\Filament\Resources\DocumentoResource\Widgets\DocumentosNotificadosTable;

class ManageNotificacionDocumentos extends ManageRecords
{
    protected static string $resource = NotificacionDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Registro Notificacion'),
            /*Actions\Action::make('Reporte Resumido')
                ->icon('heroicon-o-printer')
                ->url(route('notificaciones.rrpdf.reporte_resumido',Auth::user()->id))
                ->openUrlInNewTab(),
            Actions\Action::make('Reporte Detallado')
                ->icon('heroicon-o-printer')
                ->url(route('notificaciones.rdpdf.reporte_detallado',Auth::user()->id))
                ->openUrlInNewTab(),*/
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            //DocumentosAsignarTable::class,
            //DocumentosNotificadosTable::class,
            //NotificadoresChart::class,
        ];
    }
}
