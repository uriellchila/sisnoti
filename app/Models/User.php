<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Documento;
use App\Models\TipoDocumento;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasSuperAdmin;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $fillable = [
        'name',
        'dni',
        'telefono',
        'email',
        'password',
    ];
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function isAdmin()
    {
        //if ($this->role->name == 'Super Admin' /*&& $this->is_active == 1*/) {
         ///   return true;
       // }
       // return false;
       //dd(Auth::role()->name);
       //return false;
       $data = DB::table('model_has_roles')
                                ->select('roles.name as role_name')
                                ->join('roles','roles.id','=','model_has_roles.role_id')
                                ->join('users','users.id','=','model_has_roles.model_id')
                                ->where('users.id',Auth::user()->id)
                                ->get();    
                                foreach ($data as $p) { 
                                    if ($p->role_name == 'Admin') {
                                        return true;
                                     }
                                    return false;
                                } 
    }
    public function isNotificador()
    {
        //if ($this->role->name == 'Super Admin' /*&& $this->is_active == 1*/) {
         ///   return true;
       // }
       // return false;
       //dd(Auth::role()->name);
       //return false;
       $data = DB::table('model_has_roles')
                                ->select('roles.name as role_name')
                                ->join('roles','roles.id','=','model_has_roles.role_id')
                                ->join('users','users.id','=','model_has_roles.model_id')
                                ->where('users.id',Auth::user()->id)
                                ->get();    
                                foreach ($data as $p) { 
                                    if ($p->role_name == 'Notificador' /*&& $this->is_active == 1*/) {
                                        return true;
                                     }
                                    return false;
                                } 
    }
    public function documento_notificadors(){
        return $this->hasMany(DocumentoNotificador::class);
    }
    public function documentos(){
        return $this->hasMany(Documento::class);
    }

}
