<?php

namespace App\Filament\Resources\DocumentoResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;

class AvanceDiarioNotificadoresChart extends ChartWidget
{
    protected static ?string $heading = 'Progreso Notificaciones';
    //public ?string $filter = 'user_id';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $notificaciones=NotificacionDocumento::select('fecha_notificacion as fecha', DB::raw("count(*) as notificados"))
        ->where('user_id', $activeFilter)        
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

  protected function getFilters(): ?array
    {
        $users=User::query()->pluck('id','name');
        
        return [
            //$users
            0	=> "Seleccion un Notificador",
            2	=> "LISBETH YOMIRA CALSINA COILA",
            3	=> "JHONNY SALAMANCA FLORES",
            4	=> "MARITZA MIRAMIRA TIPULA",
            5	=> "YETSY GUADALUPE CALSIN CALSIN",
            6	=> "PAOLA CECILIA VERANO CUSIHUALLPA",
            7	=> "DONY LISANDRO CONDORI MENDOZA",
            8	=> "DINA",
            9	=> "JOSE LUIS FLORES MAMANI",
            10	=> "ANTONY",
            11	=> "WILLY",
            12	=> "DANIEL",
            20	=> "WENDELL NABIL BURGOS RAMOS",
            21	=> "FERNANDO JOSE CHAIÑA PINO",
            22	=> "BEATRIZ CRUZ RODRIGUEZ",
            23	=> "GILBERTH JR. DENNILSON MIRANDA FLORES",
        ];
    }
    public static function canView(): bool
    {
        return Auth::user()->isAdmin();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
