<?php

namespace App\Filament\Imports;

use App\Models\Documento;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DocumentoImporter extends Importer
{
    protected static ?string $model = Documento::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tipo_documento_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('numero_doc')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('anyo_doc')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('deuda_desde')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('deuda_hasta')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('deuda_ip')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('codigo')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('dni')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('razon_social')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('domicilio')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            /*ImportColumn::make('user_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('prico')
                ->boolean()
                ->rules(['boolean']),*/
        ];
    }

    public function resolveRecord(): ?Documento
    {
        // return Documento::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Documento();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your documento import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
