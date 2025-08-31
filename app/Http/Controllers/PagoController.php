<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PagoRecibido;
use Barryvdh\DomPDF\Facade\Pdf;



class PagoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'paypal_order_id' => 'required|string|unique:pagos,paypal_order_id',
            'payer_email'     => 'required|email',
            'amount'          => 'required|numeric',
            'description'     => 'nullable|string|max:255',
        ]);

        $pago = Pago::create($data);

        // Usar el Mailable para enviar correo 
        Mail::to(env('MAIL_PAYMENT_NOTIFY'))->send(new PagoRecibido($pago));

        return response()->json(['status' => 'success', 'pago' => $pago]);
    }

    public function index()
    {
        $pagos = Pago::latest()->get();
        return view('admin.pagos', compact('pagos'));
    }

    public function gracias(Pago $pago)
    {
        return view('admin.gracias', compact('pago'));
    }

    public function facturaPdf(Pago $pago)
    {
        $pdf = PDF::loadView('pagos.factura', compact('pago'));
        return $pdf->download('Factura-Compra-' . $pago->id . '.pdf');
    }
}
