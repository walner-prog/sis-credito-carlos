<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cartera extends Model
{
    use HasFactory, SoftDeletes;

    // Especificamos los campos que pueden ser asignados masivamente (fillable)
    protected $fillable = [
        'nombre',
        'estado',
        'user_id'
    ];

    
 
    public function getEstadoAttribute($value)
    {
        // Devuelve true si está activa, false si está inactiva (para el ToggleColumn)
        return $value === 'activa';
    }
    
    public function setEstadoAttribute($value)
    {
        // Si el valor es true, guarda 'activa', si es false, guarda 'inactiva'
        $this->attributes['estado'] = $value ? 'activa' : 'inactiva';
    }

 
public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}



// En el modelo Cartera
public function clientes()
{
    return $this->hasMany(Cliente::class);  // Asegúrate de que Cliente sea el nombre correcto del modelo
}

// En el modelo Cartera
public function creditos()
{
    return $this->hasMany(Credito::class);  // Asegúrate de que el nombre de la clase sea correcto
}







    
}
