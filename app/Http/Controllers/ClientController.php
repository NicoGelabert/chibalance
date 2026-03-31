<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function create()
    {
        $products = Product::where('published', true)->get();
        return view('components.intake-form', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'age' => 'required|integer',
            'phone_number' => 'required|digits_between:9,15',
            'emergency_phone_number' => 'required|digits_between:9,15',
            'town' => 'required|string',
            'occupancy' => 'required|string',
            'email' => 'required|email',
            
            'treatment' => 'required|string',
        
            'sore' => 'required|string',
            'medication' => 'required|string',
            'allergies' => 'required|string',
            'medicalBackground' => 'required|string',
            'sports' => 'required|string',
        
            'currentDiet' => 'required|string',
            'sleepPatterns' => 'required|string',
            'waterIntake' => 'required|string',
        
            'pregnancy' => 'nullable|string',
            'menopause' => 'nullable|string',
        
            'signed' => 'required|boolean',
            'g-recaptcha-response' => 'required'
        ]);
        
        // Validar reCAPTCHA con Google
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key'); // Guarda la clave en el archivo .env
        
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            return back()->withErrors(['captcha' => 'reCAPTCHA validation failed.'])->withInput();
        }

        try {
            // Aquí se realizan las validaciones y el almacenamiento de datos
            Client::create($request->all());
    
            return response()->json(['success' => true]);  // Indica éxito
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function index()
    {
        $clients = Client::orderBy('full_name', 'asc')->get();

        return view('client.index', [
            'clients' => $clients
        ]);
    }

    public function view(Client $client){
        return view('client.view', [
            'client' => $client,
        ]);
    }
}
