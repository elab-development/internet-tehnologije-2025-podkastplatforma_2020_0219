<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\PodcastResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class PodcastController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->input('per_page', 9);
    $idAutora = $request->input('id_autora');
    $search = $request->input('search');
    $kategorija = $request->input('kategorija');
    $minEpizoda = $request->input('min_episodes', 0); 

    try {
   
        $query = Podcast::withCount('emisije');

      
        if ($idAutora) {
            $query->whereHas('autori', function($q) use ($idAutora) {
                $q->where('users.id', $idAutora);
            });
        }

     
        if ($search) {
            $query->where('naslov', 'like', '%' . $search . '%');
        }

        if ($kategorija && $kategorija !== 'all') {
            $query->whereHas('kategorija', function($q) use ($kategorija) {
                $q->where('naziv', $kategorija);
            });
        }

       
        if ($minEpizoda > 0) {
            $query->having('emisije_count', '>=', $minEpizoda);
        }

        $podkasti = $query->orderBy('naslov', 'asc')->paginate($perPage);

        return PodcastResource::collection($podkasti);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Greška na serveru.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function show($id)
    {
        try{
            $podkast = Podcast::findOrFail($id);
            Log::info($podkast);
            return new PodcastResource($podkast);
        }
        catch (\Exception $e) {
          
            return response()->json([
                'message' => 'Došlo je do greške prilikom dohvatanja podkasta.',
                'error' => $e->getMessage(),
            ], 500);
        }
        
      
    }



       public function destroy($id)
    {
        try {
           
    
            $podcast = Podcast::findOrFail($id);
            $user = Auth::user();
    
            if ($podcast->logo_putanja) {
                $putanjaBanera = public_path($podcast->logo_putanja);
                $direktorijum = dirname($putanjaBanera);
                if (File::exists($direktorijum)) {
                    File::deleteDirectory($direktorijum);
                }
            }
    
          
            $podcast->delete();
    
            return response()->json(['message' => 'Podcast i svi povezani resursi su uspešno obrisani.'], 200);
        } catch (\Exception $e) {
            Log::error('Greška prilikom brisanja podcasta: ' . $e->getMessage());
            return response()->json(['message' => 'Došlo je do greške prilikom brisanja podcasta.', 'error' => $e->getMessage()], 500);
        }
    }



   
    
  
}

    






