<?php

use App\Http\Controllers\API\ArtistController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\MovieArtistController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\MovieCountryController;
use App\Http\Controllers\API\MovieGenreController;
use App\Http\Controllers\API\MovieLanguageController;
use App\Http\Controllers\API\MovieSerieController;
use App\Http\Controllers\API\MovieSubscriptionController;
use App\Http\Controllers\API\MovieVideoController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RatedMovieController;
use App\Http\Controllers\API\RequestedMovieController;
use App\Http\Controllers\API\RequestedMovieResponseController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SavedMovieController;
use App\Http\Controllers\API\SubscriptionPlanController;
use App\Http\Controllers\API\TVShowController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/signout', [AuthController::class, 'signout']);

    //User
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    //Genres
    Route::post('/genres', [GenreController::class, 'store']);
    Route::put('/genres/{id}', [GenreController::class, 'update']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);

    //Role
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    //Artist
    Route::post('/artists', [ArtistController::class, 'store']);
    Route::put('/artists/{id}', [ArtistController::class, 'update']);
    Route::delete('/artists/{id}', [ArtistController::class, 'destroy']);

    //Country
    Route::post('/countries', [CountryController::class, 'store']);
    Route::put('/countries/{id}', [CountryController::class, 'update']);
    Route::delete('/countries/{id}', [CountryController::class, 'destroy']);

    //Language
    Route::post('/languages', [LanguageController::class, 'store']);
    Route::put('/languages/{id}', [LanguageController::class, 'update']);
    Route::delete('/languages/{id}', [LanguageController::class, 'destroy']);

    //Movie
    Route::post('/movies', [MovieController::class, 'store']);
    Route::put('/movies/{id}', [MovieController::class, 'update']);
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

    //Movie Genres
    Route::post('/movie_genres', [MovieGenreController::class, 'store']);
    Route::put('/movie_genres/{id}', [MovieGenreController::class, 'update']);
    Route::delete('/movie_genres/{id}', [MovieGenreController::class, 'destroy']);

    //Movie Artist
    Route::post('/movie_artists', [MovieArtistController::class, 'store']);
    Route::put('/movie_artists/{id}', [MovieArtistController::class, 'update']);
    Route::delete('/movie_artists/{id}', [MovieArtistController::class, 'destroy']);

    //Movie Countries
    Route::post('/movie_countries', [MovieCountryController::class, 'store']);
    Route::put('/movie_countries/{id}', [MovieCountryController::class, 'update']);
    Route::delete('/movie_countries/{id}', [MovieCountryController::class, 'destroy']);

    //Movie Languages
    Route::post('/movie_languages', [MovieLanguageController::class, 'store']);
    Route::put('/movie_languages/{id}', [MovieLanguageController::class, 'update']);
    Route::delete('/movie_languages/{id}', [MovieLanguageController::class, 'destroy']);

    //Movie Series 
    Route::post('movie_series', [MovieSerieController::class, 'store']);
    Route::put('movie_series/{id}', [MovieSerieController::class, 'update']);
    Route::delete('movie_series/{id}', [MovieSerieController::class, 'destroy']);

    //Movie Videos/////////////////
    Route::post('movie-videos', [MovieVideoController::class, 'store']);
    Route::put('movie-videos/{id}', [MovieVideoController::class, 'update']);
    Route::delete('movie-videos/{id}', [MovieVideoController::class, 'destroy']);

    //Saved Movies
    // Route::get('saved-movies', [SavedMovieController::class, 'index']);
    Route::post('saved-movies', [SavedMovieController::class, 'store']);
    Route::get('saved-movies/{id}', [SavedMovieController::class, 'show']);
    Route::put('saved-movies/{id}', [SavedMovieController::class, 'update']);
    Route::delete('saved-movies/{id}', [SavedMovieController::class, 'destroy']);

    //Rated Movies
    Route::get('rated-movies', [RatedMovieController::class, 'index']);
    Route::post('rated-movies', [RatedMovieController::class, 'store']);
    Route::get('rated-movies/{id}', [RatedMovieController::class, 'show']);
    Route::put('rated-movies/{id}', [RatedMovieController::class, 'update']);
    Route::delete('rated-movies/{id}', [RatedMovieController::class, 'destroy']);

    //Requested Movies
    Route::get('requested-movies', [RequestedMovieController::class, 'index']);
    Route::post('requested-movies', [RequestedMovieController::class, 'store']);
    Route::get('requested-movies/{id}', [RequestedMovieController::class, 'show']);
    Route::put('requested-movies/{id}', [RequestedMovieController::class, 'update']);
    Route::delete('requested-movies/{id}', [RequestedMovieController::class, 'destroy']);

    //Requested Movie Responses
    Route::get('requested-movie-responses', [RequestedMovieResponseController::class, 'index']);
    Route::post('requested-movie-responses', [RequestedMovieResponseController::class, 'store']);
    Route::get('requested-movie-responses/{id}', [RequestedMovieResponseController::class, 'show']);
    Route::put('requested-movie-responses/{id}', [RequestedMovieResponseController::class, 'update']);
    Route::delete('requested-movie-responses/{id}', [RequestedMovieResponseController::class, 'destroy']);

    //Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

    //Movie Subscriptions 
    Route::get('movie-subscriptions', [MovieSubscriptionController::class, 'index']);
    Route::post('movie-subscriptions', [MovieSubscriptionController::class, 'store']);
    Route::get('movie-subscriptions/{id}', [MovieSubscriptionController::class, 'show']);
    Route::put('movie-subscriptions/{id}', [MovieSubscriptionController::class, 'update']);
    Route::delete('movie-subscriptions/{id}', [MovieSubscriptionController::class, 'destroy']);

    //Subscription Plans
    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index']);
    Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store']);
    Route::get('/subscription-plans/{id}', [SubscriptionPlanController::class, 'show']);
    Route::put('/subscription-plans/{id}', [SubscriptionPlanController::class, 'update']);
    Route::delete('/subscription-plans/{id}', [SubscriptionPlanController::class, 'destroy']);

    //TV Show
    Route::post('/tv_shows', [TVShowController::class, 'store']);
    Route::put('/tv_shows/{id}', [TVShowController::class, 'update']);
    Route::delete('/tv_shows/{id}', [TVShowController::class, 'destroy']);
});

Route::middleware('api')->group(function () {

    //User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    //Genres
    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);

    //Auth
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);

    //Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);

    //Artists
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::get('/artists/{id}', [ArtistController::class, 'show']);

    //Country
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/countries/{id}', [CountryController::class, 'show']);

    //Language
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::get('/languages/{id}', [LanguageController::class, 'show']);

    //Movies
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/movies/{id}', [MovieController::class, 'show']);

    //Movie Genres
    Route::get('/movie_genres', [MovieGenreController::class, 'index']);
    Route::get('/movie_genres/{id}', [MovieGenreController::class, 'show']);

    //Movie Countries
    Route::get('/movie_countries', [MovieCountryController::class, 'index']);
    Route::get('/movie_countries/{id}', [MovieCountryController::class, 'show']);

    //Movie Languages
    Route::get('/movie_languages', [MovieLanguageController::class, 'index']);
    Route::get('/movie_languages/{id}', [MovieLanguageController::class, 'show']);

    //Movie Artists
    Route::get('/movie_artists', [MovieArtistController::class, 'index']);
    Route::get('/movie_artists/{id}', [MovieArtistController::class, 'show']);

    //TV-Show Type      
    Route::get('/tv_shows', [TVShowController::class, 'index']);
    Route::get('/tv_shows/{id}', [TVShowController::class, 'show']);

    //Movie Series
    Route::get('movie_series', [MovieSerieController::class, 'index']);
    Route::get('movie_series/{id}', [MovieSerieController::class, 'show']);

    //Movie Videos
    Route::get('movie-videos', [MovieVideoController::class, 'index']);
    Route::get('movie-videos/{id}', [MovieVideoController::class, 'show']);
});
