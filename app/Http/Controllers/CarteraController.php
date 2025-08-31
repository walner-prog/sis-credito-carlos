<?php

namespace App\Http\Controllers;

 
use App\Models\Cartera;

class CarteraController extends Controller
{
    /**
     * Muestra la lista de carteras.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        return view('carteras.index');
    }
}
