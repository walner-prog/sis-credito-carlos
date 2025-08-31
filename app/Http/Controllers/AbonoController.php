<?php

namespace App\Http\Controllers;

 
 

class AbonoController extends Controller
{
    /**
     * Mostrar la lista de abonos.
     */
    public function index()
    {
         

        return view('abonos.index');
    }


    public function report()
    {
        return view('abonos.report');
    }

     
}
