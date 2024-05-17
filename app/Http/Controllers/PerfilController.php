<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        // No puede ver nada si no esta autenticado
        $this->middleware('auth');   
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        // Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username,'.auth()->user()->id, 'min:3', 'max:20', 'not_in:twitter,editar-perfil'],
        ]);

        if($request->imagen) {
            $imagen = $request->file('imagen');

            $nombreimagen = Str::uuid().".".$imagen->extension();
    
            // Crea la imagen
            $imagenServidor = Image::make($imagen);
    
            // Corta la imaghen
            $imagenServidor->fit(1000, 1000);
    
            // Define la ruta a la que va la imagen
            $imagenPath = public_path('perfiles').'/'.$nombreimagen;
    
            // Guarda la imagen en su ruta
            $imagenServidor->save($imagenPath);
        }
        
        // Guardar cambios

        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreimagen ?? auth()->user()->imagen ?? null;
        $usuario->save();

        // Redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }
}
