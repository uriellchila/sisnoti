<?php

namespace App\Filament\Resources\DocumentoResource\Widgets;

use Filament\Tables;
use App\Models\Documento;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
//use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Widgets\TableWidget as BaseWidget;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class AsignadosTable extends BaseWidget
{   protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Asignados - Estado';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Documento::query()
                ->select('documentos.id',DB::raw("SPLIT_PART(u.name, ' ', 1) as nombre"),'fecha_para','numero_doc','codigo','razon_social',
                DB::raw('(select tn.nombre from notificacion_documentos as nd inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id  where user_id=documentos.user_id and nd.deleted_at is null and documentos.id = documento_id LIMIT 1) as notificado'),
                DB::raw('(select md.nombre from devolucion_documentos as dd inner join motivo_devolucions as md on md.id =dd.motivo_devolucion_id where user_id=documentos.user_id and dd.deleted_at is null and documentos.id = documento_id LIMIT 1) as devuelto')
                )
                ->join('users as u', 'documentos.user_id', 'u.id' )
                /*Documento::query()->select('u.name','fecha_para','numero_doc','codigo','razon_social',
                DB::raw('(select documento_id from notificacion_documentos where user_id=documentos.user_id and deleted_at is null and documentos.id = documento_id LIMIT 1) as notificado'),
                DB::raw('(select documento_id from devolucion_documentos where user_id=documentos.user_id and deleted_at is null and documentos.id = documento_id LIMIT 1) as devuelto')
                )
                ->join('users as u', 'documentos.user_id', 'u.id' )*/
                ->where('documentos.user_id','!=',null)
                ->where('documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
                ->orderBy('u.name','asc')
                ->orderBy('fecha_para','asc')
                ->orderBy('numero_doc','asc')
            )
            ->columns([
                TextColumn::make('nombre')->sortable()->toggleable()->label('Notificador'),
                TextColumn::make('fecha_para')->sortable()->toggleable(),
                TextColumn::make('numero_doc')->sortable()->toggleable(),
                TextColumn::make('codigo')->sortable()->toggleable(),
                TextColumn::make('razon_social')->sortable()->toggleable(),
                TextColumn::make('notificado')->sortable()->toggleable(),
                TextColumn::make('devuelto')->sortable()->toggleable(),
                
            ])
            ->paginated([2, 5, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(2)
            ->filters([
                SelectFilter::make('user')->relationship('user', 'name',fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereExists(function (Builder $query) {
                    $query->select(DB::raw(1))
                          ->from('documentos')
                          ->whereColumn('documentos.user_id', 'users.id');
                }))
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable()
                    ->withFilename(date('Y-m-d') . ' - Asignados')
                    ->withColumns([
                        Column::make('codigo')->formatStateUsing(fn (string $state): string => __("{$state}")),//->width(10)
                    ])    
                ])
            ]);
    }
    public static function canView(): bool
    {
        return Auth::user()->isAdmin();
    }
}
