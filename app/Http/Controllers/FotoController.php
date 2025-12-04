<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FotoCOntroller extends Controller
{

    public function upload (Request $request)
    {
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('fotos', 'public');

            return response()->json(['path' => $path], 200);
        }
    }
}