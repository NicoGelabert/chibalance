<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactConfirmation;
use App\Mail\AdminNotificationMail;

class ContactController extends Controller
{
    public function create()
    {
        $products = Product::where('published',true)->get();
        return view('contact.contact', ['products' => $products]);
    }

    public function store(Request $request)
    {
        // Validación de datos recibidos en el formulario
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'treatment' => 'nullable|string',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        // Validar reCAPTCHA con Google
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key'); // Clave secreta de reCAPTCHA

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $responseData = $response->json();

        // Si la validación de reCAPTCHA falla, retornamos un mensaje de error
        if (!$responseData['success']) {
            return back()->withErrors(['captcha' => 'reCAPTCHA validation failed.'])->withInput();
        }

        try {
            // Almacenamos los datos del contacto en la base de datos, usando el método 'create' con el objeto $request
            $contact = Contact::create($request->only(['name', 'email', 'phone', 'treatment', 'message']));

            // Enviar correo de confirmación al contacto
            Mail::to($contact->email)->send(new ContactConfirmation($contact));

            // Enviar correo de notificación al administrador
            Mail::to(config('mail.from.address'))->send(new AdminNotificationMail($contact));

            // Responder con éxito
            return response()->json(['success' => true, 'message' => 'Mensaje enviado exitosamente!'], 200);
        } catch (\Exception $e) {
            // Si ocurre un error, capturamos la excepción y respondemos con un mensaje de error
            return response()->json(['success' => false, 'message' => 'Falló el envío. Por favor intente más tarde.'], 500);
        }
    }
}
