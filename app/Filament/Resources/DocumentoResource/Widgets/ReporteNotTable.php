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
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Query\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class ReporteNotTable extends BaseWidget
{   protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Notificadores - Reportes';
    public function table(Table $table): Table
    {
        return $table
            ->query( User::query()->whereExists(function (Builder $query) {
                $query->select(DB::raw(1))
                        ->from('documentos')
                        ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
                        ->whereColumn('documentos.user_id', 'users.id');
            })
            )
            ->columns([
                //TextColumn::make('id')->sortable(),
                TextColumn::make('name')->toggleable()->searchable()->label(''),
            ])
            ->paginated([2,5,10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(2)
            ->headerActions([
                Tables\Actions\Action::make('Reporte General Asignados')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data) => redirect()->route('notificaciones.rgeneralpdf.reporte_general',[Auth::user()->id, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(route('notificaciones.rgeneralpdf.reporte_general',Auth::user()->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('Reporte General Notificados')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->color('info')
                    ->action(fn(array $data) => redirect()->route('notificaciones.rgeneralpdfnot.reporte_general_not',[Auth::user()->id, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(route('notificaciones.rgeneralpdf.reporte_general',Auth::user()->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('Reporte Pagos')
                    ->icon('heroicon-o-printer')
                    ->url(route('notificaciones.rpagospdf.reporte_pagos',Auth::user()->id))
                    ->color('success')
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Reporte Devueltos')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rdevueltospdf.reporte_devueltos',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    ->openUrlInNewTab(),
                ])
                //->form() 
                ->label('Devoluciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('gray')
                ->button(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Notificados Resumido')
                    ->icon('heroicon-o-printer')
                    ->form([
                        DatePicker::make('fecha_desde')->required()->default(date("Y-m-d")),
                        DatePicker::make('fecha_hasta')->required()->default(date("Y-m-d")),
                    ])
                    ->action(fn(array $data,User $record) => redirect()->route('notificaciones.rrpdf.reporte_resumido',[$record, $data['fecha_desde'],  $data['fecha_hasta']]))
                    //->url(fn(User $record) => route('notificaciones.rrpdf.reporte_resumido',$record))
                    ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Notificados Detallado')
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
                ->color('gray')
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
                ->color('gray')
                ->button(),

                
            ]);
    }
    public static function canView(): bool
    {
        return Auth::user()->isAdmin();
    }
}
