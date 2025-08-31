<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    // Especificamos los campos que pueden ser asignados masivamente (fillable)
    protected $fillable = [
        'nombres',
        'apellidos',
        'identificacion',
        'telefono',
        'direccion',
        'km_referencia',
        'estado',
         'cartera_id',
    ];

   
    // Relación con créditos (un cliente puede tener muchos créditos)
    
    public function creditos()
    {
        return $this->hasMany(Credito::class);
    }

    

    public function abonos()
    {
        return $this->hasMany(Abono::class);
    }

   // En el modelo Cliente
  public function cartera()
  {
    return $this->belongsTo(Cartera::class);  // Asegúrate de que 'cartera_id' sea el nombre correcto de la clave foránea
   }

    

   // Función para contar los créditos del cliente
   public function getCreditosCountAttribute()
   {
       return $this->creditos->count(); // Cuenta los créditos relacionados con el cliente
   }

   // Función para contar cuántos créditos están activos
   public function getCreditosActivosCountAttribute()
   {
       return $this->creditos()->where('estado', 'activo')->count(); // Cuenta los créditos activos
   }

   // Función para mostrar la cantidad de créditos activos sobre el total
   public function getHistorialCreditosConActivosAttribute()
   {
       $totalCreditos = $this->creditos->count();
       $activos = $this->creditos()->where('estado', 'activo')->count();

       return "{$activos} de {$totalCreditos} créditos activos"; // Muestra el número de activos sobre el total
   }

   public function getFullNameAttribute()
   {
       return $this->nombres . ' ' . $this->apellidos;
   }
   

   
   
}
