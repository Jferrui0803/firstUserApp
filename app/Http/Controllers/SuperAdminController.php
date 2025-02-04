<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{

    public function home()
    {
        
        $users = User::paginate(5);

        // Pasar los usuarios a la vista
        return view('superAdmin.index', compact('users'));
    }
    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin,superAdmin',
        ]);

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        $user->sendEmailVerificationNotification();
        // Redirigir al superadmin con un mensaje de éxito
        return redirect()->route('superAdmin.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        // Mostrar los datos del usuario que se va a editar
        $user = User::findOrFail($id);
        return view('superAdmin.edit', compact('user'));
    }


    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:user,admin',
            'password' => 'nullable|min:8|confirmed', // Validación de la contraseña
        ]);
    
        // Buscar el usuario y actualizar los datos
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
    
        // Si el campo de contraseña no está vacío, actualizamos la contraseña
        if ($request->has('password') && $request->input('password') !== '') {
            $user->password = bcrypt($request->input('password'));
        }
    
        // Guardar los cambios
        $user->save();
    
        // Redirigir a la página anterior con un mensaje de éxito
        return back()->with('success', 'Usuario actualizado correctamente.');
    }
    


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }

    public function verify($id)
    {
        // Buscar al usuario por su ID
        $user = User::findOrFail($id);

        // Verificar el correo electrónico del usuario
        $user->email_verified_at = now(); // Establece la fecha y hora actuales como verificación
        $user->save();

        // Devolver una respuesta
        return response()->json(['message' => 'Usuario verificado correctamente.'], 200);
    }

    public function desVerify($id)
    {
        // Buscar al usuario por su ID
        $user = User::findOrFail($id);

        // Verificar el correo electrónico del usuario
        $user->email_verified_at = null; // Establece la fecha y hora actuales como verificación
        $user->save();

        // Devolver una respuesta
        return response()->json(['message' => 'Usuario desverificado correctamente.'], 200);
    }
}
