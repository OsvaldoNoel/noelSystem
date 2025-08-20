<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailVerificationToken;
use Illuminate\Support\Facades\Mail;
use App\Mail\BrevoVerificationEmail;
use Illuminate\Support\Str;

class EmailVerificationService
{
    public function sendVerificationEmail(User $user)
    {
        $token = Str::random(60);

        // Guardar token en DB
        EmailVerificationToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $token,
                'expires_at' => now()->addHours(24)
            ]
        );

        // Generar URL correcta
        $verificationUrl = url('/email/verify/' . $token);

        Mail::to($user->email)->send(
            new BrevoVerificationEmail($user, $verificationUrl)
        );
    }

    public function verifyEmail($token)
    {
        $tokenModel = EmailVerificationToken::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = $tokenModel->user;
        $user->email_verified_at = now();
        $user->save();

        $tokenModel->delete();

        return $user;
    }

    protected function generateVerificationUrl($token)
    {
        // Usar la ruta por nombre que definiste
        return route('verification.verify', ['token' => $token], false);

        // El tercer parámetro 'false' evita que se genere la URL completa
        // para que Brevo use tu dominio configurado en .env
    }
}
