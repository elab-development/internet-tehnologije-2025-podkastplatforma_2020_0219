<?php

use App\Models\User;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('UserController autorizovani zahtevi', function () {

    beforeEach(function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    });

    
    it('može da povuče listu svih korisnika', function () {
        User::factory()->count(2)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    });

  
    it('uspešno briše korisnika i vraća poruku', function () {
        $userZaBrisanje = User::factory()->create();
        $response = $this->deleteJson("/api/users/{$userZaBrisanje->id}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Korisnik uspešno obrisan.']);

        $this->assertDatabaseMissing('users', ['id' => $userZaBrisanje->id]);
    });

   
    it('briše podkaste korisnika ako je on jedini autor', function () {
        $autor = User::factory()->create();
        
        
        $podcast = Podcast::factory()->create();
        $autor->mojiPodkasti()->attach($podcast->id);

        $this->deleteJson("/api/users/{$autor->id}")->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $autor->id]);
        
        $this->assertDatabaseMissing('podkasti', ['id' => $podcast->id]);
    });

    it('vraća 500 ako korisnik ne postoji (findOrFail exception)', function () {
        $response = $this->deleteJson("/api/users/999");

        $response->assertStatus(500)
                 ->assertJsonPath('error', 'Došlo je do greške prilikom brisanja korisnika.');
    });

});


it('zabranjuje pristup listi korisnika gostima', function () {
    $this->getJson('/api/users')->assertStatus(401);
});