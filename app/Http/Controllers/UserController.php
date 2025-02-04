<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
    public function index()
    {
        // Obtén al usuario autenticado
        $user = Auth::user();
        return view('user.index', compact('user'));
    }

    public function update(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(), // Excluye el email actual
            'password' => 'nullable|string|min:8|confirmed', // Opcional, pero si se llena debe ser válido
        ]);

        // Actualiza los datos del usuario
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Información actualizada correctamente.');
    }
}
