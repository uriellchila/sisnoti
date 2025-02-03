<?php

namespace App\Models;

use App\Models\SubTipoNotificacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoNotificador extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable=[
        'contribuyente_id',
        'tipo_documento_id',
        'codigo',
        'dni',
        'razon_social',
        'domicilio',
        'anyo',
        'numero_doc',
        'cantidad_visitas',
        'numero_acuse',
        'tipo_notificacion_id',
        'sub_tipo_notificacion_id',
        'fecha_notificacion',
        'observaciones',
        'user_id',
    ];
    public function contribuyente(){
        return $this->belongsTo(Contribuyente::class);
    }
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class);
    }
    public function tipo_notificacion(){
        return $this->belongsTo(TipoNotificacion::class);
    }
    public function sub_tipo_notificacion(){
        return $this->belongsTo(SubTipoNotificacion::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
