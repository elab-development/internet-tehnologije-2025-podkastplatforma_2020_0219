<?php
 
 namespace App\Http\Controllers;

 use App\Models\User;
 use Illuminate\Http\Request;
 use App\Http\Resources\UserResource;
 use App\Http\Resources\PodcastResource;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Log;
 use App\Models\Podcast;
 
 class UserController extends Controller
 {
     

    

 public function vratiAutore()
    {
        try {
           
            $autori = User::where('role', 'autor')->get();
            return UserResource::collection($autori);
    
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Došlo je do greške prilikom učitavanja korisnika.',
                'error' => $e->getMessage()
            ], 500); 
        }
    }
    

    
    public function mojiPodcasti(Request $request)
    {
        try{
            $perPage = $request->input('per_page', 10); 
            $user = Auth::user();
            $podkasti = $user->mojiPodkasti()->paginate($perPage);
            return PodcastResource::collection($podkasti);
        }catch (\Exception $e) {
           
            return response()->json([
                'message' => 'Došlo je do greške prilikom dohvatanja podkasta.',
                'error' => $e->getMessage(),
            ], 500);
        }
       
    }


    
    public function getFavorites(Request $request)
    {
        try {
            $user = Auth::user();
            $query = $user->listaOmiljenihPodkasta()->newQuery();
    
            if ($request->filled('id_autora')) {
                $query->whereHas('autori', function ($q) use ($request) {
                     $q->where('users.id', $request->id_autora);
                });
            }
    
            $perPage = $request->input('per_page', 10); 
            $omiljeniPodkasti = $query->paginate($perPage);
    
            return PodcastResource::collection($omiljeniPodkasti);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Došlo je do greške pri dohvatanju omiljenih podkasta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    


     public function addToFavorites($id)
    {
        try {
           
            $user = Auth::user();
            $podkast = Podcast::findOrFail($id);
         
            if (!$user->listaOmiljenihPodkasta->contains($podkast->id)) {
                $user->listaOmiljenihPodkasta()->attach($podkast->id);
            }

           
            return response()->json(['message' => 'Podkast je uspešno dodat u omiljene.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Došlo je do greške prilikom dodavanja podkasta u omiljene.','error'=> $e->getMessage()], 500);
        }
    }


    public function removeFavorite($id)
    {
        try {
            $user = Auth::user();
            $podkast = Podcast::findOrFail($id);
            $user->listaOmiljenihPodkasta()->detach($podkast->id);
            return response()->json(['message' => 'Podkast je uspešno uklonjen iz omiljenih.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Došlo je do greške prilikom uklanjanja podkasta iz omiljenih.','error'=> $e->getMessage()], 500);
        }
    }

     
 }