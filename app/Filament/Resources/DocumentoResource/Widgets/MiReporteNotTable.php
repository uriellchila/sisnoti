<?php

namespace App\Filament\Resources\DocumentoResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Query\Builder;

class MiReporteNotTable extends BaseWidget
{  // protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Mis Reportes';
    
    protected static ?string $maxHeight = '10px';
    public function table(Table $table): Table
    {
        return $table
            ->query( User::query()->whereExists(function (Builder $query) {
                $query->select(DB::raw(1))
                        ->from('documentos')
                        ->whereColumn('documentos.user_id', 'users.id');
            })->where('id',Auth::user()->id)
            )
            ->columns([
                //TextColumn::make('id'),
                //TextColumn::make('name')->label(''),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Reporte Devueltos')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rdevueltospdf.reporte_devueltos',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(fn(User $record) => route('notificaciones.rdevueltospdf.reporte_devueltos',$record))
                    ->openUrlInNewTab(),
                ]) 
                ->label('Devoluciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Notificacion Resumido')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rrpdf.reporte_resumido',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(fn(User $record) => route('notificaciones.rrpdf.reporte_resumido',$record))
                    ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Notificacion Detallado')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rdpdf.reporte_detallado',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(fn(User $record) => route('notificaciones.rdpdf.reporte_detallado',$record))
                    ->openUrlInNewTab(),
                ]) 
                ->label('Notificaciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Reporte Resumido')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rasigresumidopdf.reporte_asig_resumido',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(fn(User $record) => route('notificaciones.rasigresumidopdf.reporte_asig_resumido',$record))
                    ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Reporte Detallado')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rasigdetalladopdf.reporte_asig_detallado',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(fn(User $record) => route('notificaciones.rasigdetalladopdf.reporte_asig_detallado',$record))
                    ->openUrlInNewTab(),
                ]) 
                ->label('Asignados')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),

                
            ]);
    }
    public static function canView(): bool
    {
        return Auth::user()->isNotificador();
    }
}