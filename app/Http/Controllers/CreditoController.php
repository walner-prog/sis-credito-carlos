<?php

namespace App\Http\Controllers;

 

class CreditoController extends Controller
{
    /**
     * Muestra el listado de créditos.
     */
    public function index()
    {
         

        return view('creditos.index');
    }
}
