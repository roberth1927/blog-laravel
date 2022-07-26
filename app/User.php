<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
/*
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope);
    }
*/
    use Notifiable, SoftDeletes;


    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';
    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';
    
    protected $table  = 'users';
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    public function setNameAttribute($valor){
        $this->attributes['name'] = strtolower($valor);
    }

   public function getNameAttribute($valor){
       return ucfirst($valor); 
    }

    public function setEmailAttribute($valor){
        $this->attributes['email'] = strtolower($valor);
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
 public function getNombreCompletoAttribute() 
{ 
  return $this->name . ' ' . $this->email; 
}   
    public function esVerificado(){
        return $this->verified == User::USUARIO_VERIFICADO;    
    }

    public function esAdministrador(){
       return $this->admin == User::USUARIO_ADMINISTRADOR; 
    } 

    public static function generarVerificationToken(){ 
        return str_random(40);
     }


}
