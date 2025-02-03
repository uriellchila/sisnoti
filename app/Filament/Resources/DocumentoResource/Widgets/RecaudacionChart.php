<?php

namespace App\Filament\Resources\DocumentoResource\Widgets;

use App\Models\Pago;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;

class RecaudacionChart extends ChartWidget
{
    protected static ?string $heading = 'Impuesto Predial 2024';
    //public ?string $filter = 'today';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $notificaciones=NotificacionDocumento::select(DB::raw("sum(d.deuda_ip) as notificados"))
        ->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
        ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('tipo_notificacion_id', '!=', null)        
        //->where('deleted_at', '=', null)        
        //->groupBy('fecha_notificacion')
        //->orderBy('fecha_notificacion','asc')
        ->get();

        $recaudacion=Pago::select(DB::raw("sum(monto_ip) as monto_ip"))
        //->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
        //->where('tipo_notificacion_id', '!=', null)        
        ->where('periodo', '=', 2024)        
        ->where('clasificador', '=', '1.1.2.1.1.1.01')        
        //->groupBy('fecha_notificacion')
        //->orderBy('fecha_notificacion','asc')
        ->get();

        $recaudacion_notificaciones=NotificacionDocumento::select(DB::raw("sum(p.monto_ip) as monto_recuperado"))
        ->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
        ->join('pagos as p', 'p.recepcion', '=', 'd.numero_doc')
        ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('tipo_notificacion_id', '!=', null)        
        //->where('deleted_at', '=', null)        
        //->groupBy('fecha_notificacion')
        //->orderBy('fecha_notificacion','asc')
        ->get();
        
      
        //$data=[0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89];
        //dd($data);
        return [
            'datasets' => [
                
                [
                    'label' => 'Recaudacion Total al 04/04/2024',
                    'data' => array_column($recaudacion->toArray(), 'monto_ip'),
                    'backgroundColor' => '#BB8FCE',
                    'borderColor' => '#8E44AD',
                    
                ],
                [
                    'label' => 'Monto Notificado',
                    'data' => array_column($notificaciones->toArray(), 'notificados'),
                    'backgroundColor' => '#AED6F1',
                    'borderColor' => '#2874A6',
                    
                ],
                
                [
                    'label' => 'Monto Recuperado x Notificaciones',
                    'data' => array_column($recaudacion_notificaciones->toArray(), 'monto_recuperado'),
                    'backgroundColor' => '#A2D9CE',
                    'borderColor' => '#148F77',
                    
                ],
                
            ],
            'labels' => [''],//array_column($notificaciones->toArray(), 'fecha'),
            'labels' => [''],//array_column($notificaciones->toArray(), 'fecha'),
            'labels' => ['']//array_column($notificaciones->toArray(), 'fecha'),
            
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
        return Auth::user()->isAdmin();
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
