<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Calendar;
use App\Models\GoogleCalendarToken;
use Carbon\Carbon;

class GoogleCalendarController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new \Google\Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('google-calendar.callback'));
        $client->addScope(\Google\Service\Calendar::CALENDAR);
        $client->setAccessType('offline'); // muy importante para refresh token
        $client->setPrompt('consent');

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('google-calendar.callback'));
        $client->addScope(Calendar::CALENDAR);

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        // Guardar token en DB
        GoogleCalendarToken::updateOrCreate(
            
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_at' => Carbon::now()->addSeconds($token['expires_in'] ?? 3600),
            ]
        );

        return "Google Calendar conectado correctamente. Ahora podés probar la creación de eventos.";
    }
}
