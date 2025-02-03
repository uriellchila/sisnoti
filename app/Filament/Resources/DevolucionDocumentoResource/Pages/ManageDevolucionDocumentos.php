<?php

namespace App\Filament\Resources\DevolucionDocumentoResource\Pages;

use Actions\Action;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\DevolucionDocumentoResource;

class ManageDevolucionDocumentos extends ManageRecords
{
    protected static string $resource = DevolucionDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Registrar Devolucion'),
            /*Actions\Action::make('Reporte')
                ->icon('heroicon-o-printer')
                ->url(route('notificaciones.rdevueltospdf.reporte_devueltos',Auth::user()->id))
                ->openUrlInNewTab(),*/
        ];
    }
    
}
