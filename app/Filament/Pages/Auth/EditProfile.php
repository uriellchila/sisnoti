<?php
 
namespace App\Filament\Pages\Auth;
 
use App\Models\User;
use Filament\Forms\Form;
use App\Models\TipoDocumento;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
 
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('dni')->required()->maxLength(255),
                $this->getNameFormComponent(),
                //$this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                /*Select::make('tipo_documento_id')->label('Tipo de Documento para el Perfil')
                ->options(
                    TipoDocumento::query()
                        ->pluck('nombre', 'id')
                )->selectablePlaceholder(false),*/
                Select::make('tipo_documento_id')
                        ->relationship('tipo_documento', 'nombre')
                        ->selectablePlaceholder(false),
                
            ]);
    }
}