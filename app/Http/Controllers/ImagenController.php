<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $imagen = $request->file('file');

        $nombreimagen = Str::uuid().".".$imagen->extension();

        // Crea la imagen
        $imagenServidor = Image::make($imagen);

        // Corta la imaghen
        $imagenServidor->fit(1000, 1000);

        // Define la ruta a la que va la imagen
        $imagenPath = public_path('uploads').'/'.$nombreimagen;

        // Guarda la imagen en su ruta
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreimagen]);
    }
}
