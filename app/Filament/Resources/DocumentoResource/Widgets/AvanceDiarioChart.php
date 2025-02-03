<?php

namespace App\Filament\Resources\DocumentoResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;
use Illuminate\Database\Query\Builder;

class AvanceDiarioChart extends ChartWidget
{    //protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Cantidad por fecha de Notificacion';
    public ?string $filter = 'today';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $notificaciones=NotificacionDocumento::select('fecha_notificacion as fecha', DB::raw("count(*) as notificados"))
        ->where('user_id', Auth::user()->id)
        ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)        
        ->groupBy('fecha_notificacion')
        ->orderBy('fecha_notificacion','asc')
        ->get();
        
      
        //$data=[0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89];
        //dd($data);
        return [
            'datasets' => [
                
                [
                    'label' => 'Fecha Notificacion',
                    'data' => array_column($notificaciones->toArray(), 'notificados'),
                    'backgroundColor' => '#AED6F1',
                    'borderColor' => '#2874A6',
                    
                ],
                /*[
                    'label' => 'Notificados',
                    'data' => array_column($notificaciones->toArray(), 'notificados'),
                    'backgroundColor' => '#A2D9CE',
                    'borderColor' => '#148F77',
                    
                ],*/
                
            ],
            'labels' => array_column($notificaciones->toArray(), 'fecha'),
            
        ];
        
    }

  /*  protected function getFilters(): ?array
    {
        $users=User::query()->pluck('id','name');
        
        return [
            $users
        ];
    }*/
    public static function canView(): bool
    {
        return Auth::user()->isNotificador();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
