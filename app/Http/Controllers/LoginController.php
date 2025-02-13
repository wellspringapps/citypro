<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $email)
    {
        if (! $request->hasValidSignature()) {
            abort(Response::HTTP_UNAUTHORIZED);
        }
 
        /**
         * @var User $user
         */
        $user = User::query()->where('email', $email)->firstOrFail();
 
        Auth::login($user);
 
        return new RedirectResponse(
            url: route('portal.index'),
        );
    }
}
