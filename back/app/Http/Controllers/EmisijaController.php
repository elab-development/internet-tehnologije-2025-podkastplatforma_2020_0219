<?php

namespace App\Http\Controllers;

use App\Models\Emisija;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\EmisijaResource;


class EmisijaController extends Controller
{
   


    public function show($id){
        try{
            $emisija = Emisija::findOrFail($id);
            return new EmisijaResource($emisija);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Došlo je do greške prilikom učitavanja emisije.'], 500);
        }
    }



 public function vratiFile($id)
    {
        try {
           
            $emisija = Emisija::findOrFail($id);
            $relativePath = $emisija->file;
            $absolutePath = public_path($relativePath); 

    
            if (!File::exists($absolutePath)) {
                return response()->json(['error' => 'Fajl ne postoji'], 404);
            }
    
           
            return response()->stream(function () use ($absolutePath) {
                readfile($absolutePath);
            }, 200, [
                'Content-Type' => $emisija->tip,
                'Accept-Ranges' => 'bytes',
                'Content-Length' => filesize($absolutePath),
            ]);
        } catch (\Exception $e) {
            Log::error('Greška prilikom učitavanja file: ' . $e->getMessage());
            return response()->json(['error' => 'Došlo je do greške prilikom učitavanja file.'], 500);
        }
    }


}

