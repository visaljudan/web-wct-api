<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SubscriptionPlanSeeder::class,
            TVShowSeeder::class,
            GenreSeeder::class,
            MovieSeeder::class,
            ArtistSeeder::class,
            RoleSeeder::class,
            YearSeeder::class,
            CountrySeeder::class,
            LanguageSeeder::class,
            MovieArtistSeeder::class,
            MovieCountrySeeder::class,
            MovieLanguageSeeder::class,
            MovieGenreSeeder::class,
            MoviePhotoSeeder::class,
            MovieSeriesSeeder::class,
            RatedMovieSeeder::class,
            SavedMovieSeeder::class,
            // SubscriptionUserSeeder::class,
        ]);
    }
}
