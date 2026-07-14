<?php

namespace Database\Seeders;

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Hardstyle',
            'Rawstyle',
            'Hardcore',
            'Frenchcore',
            'Uptempo',
            'Techno',
            'House',
            'EDM',
            'Trance',
            'Drum & Bass',
            'Dubstep',
            'Psytrance',
        ];

        foreach ($genres as $name) {
            Genre::firstOrCreate([
                'name' => $name,
            ], [
                'slug' => Str::slug($name),
            ]);
        }

        $allGenres = Genre::all();

        // Assign to Users
        User::all()->each(function ($user) use ($allGenres) {
            $user->genres()->sync($allGenres->random(rand(2, 4))->pluck('id'));
        });

        // Assign to Events
        Event::all()->each(function ($event) use ($allGenres) {
            $event->genres()->sync($allGenres->random(rand(1, 3))->pluck('id'));
        });

        // Assign to Artists
        Artist::all()->each(function ($artist) use ($allGenres) {
            $artist->genres()->sync($allGenres->random(rand(1, 2))->pluck('id'));
        });

        // Assign to Acts
        Act::all()->each(function ($act) use ($allGenres) {
            $act->genres()->sync($allGenres->random(rand(1, 2))->pluck('id'));
        });
    }
}
