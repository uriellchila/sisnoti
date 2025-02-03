<?php

namespace App\Filament\Resources\DocumentoResource\Widgets;

use App\Models\User;
use App\Models\Documento;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;
use Illuminate\Database\Query\Builder;

class AvanceNotificacionesChart extends ChartWidget
{   //protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Asignados - Notificados';
    public ?string $filter = 'today';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $notificaciones=User::select(DB::raw("SPLIT_PART(name, ' ', 1) as nombre"), 
        DB::raw('(select count(*) from documentos where user_id=users.id and deleted_at is null and tipo_documento_id='.Auth::user()->tipo_documento_id.') as asignados'),
        DB::raw('(select count(*) from notificacion_documentos where user_id=users.id and deleted_at is null and tipo_documento_id='.Auth::user()->tipo_documento_id.') as notificados')
        )
        ->whereExists(function (Builder $query) {
            $query->select(DB::raw(1))
                  ->from('documentos')
                  ->where('documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
                  ->whereColumn('documentos.user_id', 'users.id');
        })
        
        ->groupBy('users.id', 'name')
        ->orderBy('notificados','desc')
        ->get();
        
      
        //$data=[0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89];
        //dd($data);
        return [
            'datasets' => [
                
                [
                    'label' => 'Asignados',
                    'data' => array_column($notificaciones->toArray(), 'asignados'),
                    'backgroundColor' => '#AED6F1',
                    'borderColor' => '#2874A6',
                    
                ],
                [
                    'label' => 'Notificados',
                    'data' => array_column($notificaciones->toArray(), 'notificados'),
                    'backgroundColor' => '#A2D9CE',
                    'borderColor' => '#148F77',
                    
                ],
                
            ],
            'labels' => array_column($notificaciones->toArray(), 'nombre'),
            
        ];
        
    }

    protected function getType(): string
    {
        return 'bar';
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
}
