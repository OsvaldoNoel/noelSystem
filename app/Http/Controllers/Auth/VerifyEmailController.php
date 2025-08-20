<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash, $expires, $signature)
    {
        $user = User::findOrFail($id);

        // Validaciones (firma, expiración, hash)
        if (!hash_equals($signature, hash_hmac('sha256', $user->email, config('app.key')))) {
            abort(403, 'Firma inválida');
        }

        if (Carbon::createFromTimestamp($expires)->isPast()) {
            abort(403, 'El enlace ha expirado');
        }

        if (!hash_equals($hash, sha1($user->email))) {
            abort(403, 'Token inválido');
        }

        // Marcar como verificado
        $user->email_verified_at = now();
        $user->save();

        // Redirigir al home que manejará la UI
        return redirect()->route('app.home')->with([
            'verified' => true,
            'notification' => [
                'text' => 'Email verificado correctamente',
                'bg' => 'success'
            ]
        ]);
    }
}