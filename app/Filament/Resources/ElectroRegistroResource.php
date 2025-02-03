<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ElectroRegistro;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ElectroRegistroResource\Pages;
use App\Filament\Resources\ElectroRegistroResource\RelationManagers;

class ElectroRegistroResource extends Resource
{
    protected static ?string $model = ElectroRegistro::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Base Direcciones';
    //protected static ?string $navigationLabel = 'Tipo Documento';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->query(ElectroRegistro::query()->select('id','dni', 'razon_social','direccion','codigo_suministro', 'serie_medidor',DB::raw("NULLIF(regexp_replace(direccion, '\D','','g'), '')::numeric as numero"))->orderBy('razon_social', 'asc'))
            ->columns([
                TextColumn::make('dni')->sortable()->toggleable()->searchable(isIndividual: true, isGlobal: false)->size(TextColumn\TextColumnSize::Small)->width('13%'),
                TextColumn::make('razon_social')->sortable()->toggleable()->searchable(isIndividual: true, isGlobal: false)->size(TextColumn\TextColumnSize::ExtraSmall)->width('22%'),
                TextColumn::make('direccion')->sortable()->toggleable()->searchable(isIndividual: true, isGlobal: false)->size(TextColumn\TextColumnSize::ExtraSmall)->width('25%'),
                TextColumn::make('numero')->sortable()->toggleable()->searchable(isIndividual: true, isGlobal: false, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('direccion', 'like', "%{$search}%");
                        //->orWhere('last_name', 'like', "%{$search}%");
                })->size(TextColumn\TextColumnSize::ExtraSmall)->width('10%'),
                TextColumn::make('codigo_suministro')->sortable()->toggleable()->searchable(isIndividual: true, isGlobal: false)->size(TextColumn\TextColumnSize::ExtraSmall)->width('15%'),
                TextColumn::make('serie_medidor')->sortable()->toggleable()->searchable(isIndividual: true, isGlobal: false)->size(TextColumn\TextColumnSize::ExtraSmall)->width('15%'),
            ])
            ->searchOnBlur()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
               // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageElectroRegistros::route('/'),
        ];
    }
}
