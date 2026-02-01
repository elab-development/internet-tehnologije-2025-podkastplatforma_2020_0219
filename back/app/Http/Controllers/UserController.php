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
    


     
 }