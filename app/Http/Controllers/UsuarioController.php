<?php

namespace App\Http\Controllers;

 

class UsuarioController extends Controller
{
    /**
     * Mostrar la vista principal de usuarios.
     */
    public function index()
    {
        // Solo retorna la vista
        return view('usuarios.index');
    }
}
