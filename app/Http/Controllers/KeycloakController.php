<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Bmatovu\Keycloak\Contracts\WebKeycloakGuard;
use Bmatovu\Keycloak\Facades\KeycloakWeb;

class KeycloakController extends Controller
    {
    public function login(WebKeycloakGuard $keycloak)
    {
    // Redirect to Keycloak for authentication
    return KeycloakWeb::authenticate();
    }

    public function callback(Request $request, WebKeycloakGuard $keycloak)
    {
    // Handle the callback from Keycloak after successful authentication
    $keycloak->authenticate($request);

    // Redirect to the desired page after authentication
    return redirect()->intended('/');
    }

    public function logout(WebKeycloakGuard $keycloak)
    {
    // Logout from Keycloak
    $keycloak->logout();

    // Redirect to the desired page after logout
    return redirect()->route('about');
    }
    }
