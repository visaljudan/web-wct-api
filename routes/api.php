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
use App\Http\Controllers\API\MoviePhotoController;
use App\Http\Controllers\API\MovieSerieController;
use App\Http\Controllers\API\MovieSubscriptionController;
use App\Http\Controllers\API\MovieVideoController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RatedMovieController;
use App\Http\Controllers\API\RequestedMovieController;
use App\Http\Controllers\API\RequestedMovieResponseController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SavedMovieController;
use App\Http\Controllers\API\SubscriptionPlanController;
use App\Http\Controllers\API\TVShowController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\YearController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/signout', [AuthController::class, 'signout']);

    //User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}/password', [UserController::class, 'updatePassword']);
    Route::put('/users/{id}/new_password', [UserController::class, 'newPassword']);

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
    Route::put('/countries/{countyCode}', [CountryController::class, 'update']);
    Route::delete('/countries/{countyCode}', [CountryController::class, 'destroy']);

    //Language
    Route::post('/languages', [LanguageController::class, 'store']);
    Route::put('/languages/{id}', [LanguageController::class, 'update']);
    Route::delete('/languages/{id}', [LanguageController::class, 'destroy']);

    //TV Show
    Route::post('/tv_shows', [TVShowController::class, 'store']);
    Route::put('/tv_shows/{id}', [TVShowController::class, 'update']);
    Route::delete('/tv_shows/{id}', [TVShowController::class, 'destroy']);

    //Movie Artist
    Route::post('/movie_artists', [MovieArtistController::class, 'store']);
    Route::put('/movie_artists/{id}', [MovieArtistController::class, 'update']);
    Route::delete('/movie_artists/{id}', [MovieArtistController::class, 'destroy']);

    //Movie Genres
    Route::post('/movie_genres', [MovieGenreController::class, 'store']);
    Route::put('/movie_genres/{id}', [MovieGenreController::class, 'update']);
    Route::delete('/movie_genres/{id}', [MovieGenreController::class, 'destroy']);

    //Subscription Plans
    Route::get('subscription_plans', [SubscriptionPlanController::class, 'index']);
    Route::post('subscription_plans', [SubscriptionPlanController::class, 'store']);
    Route::get('/subscription_plans/{id}', [SubscriptionPlanController::class, 'show']);
    Route::put('/subscription_plans/{id}', [SubscriptionPlanController::class, 'update']);
    Route::delete('/subscription_plans/{id}', [SubscriptionPlanController::class, 'destroy']);

    //Movie Countries
    Route::post('/movie_countries', [MovieCountryController::class, 'store']);
    Route::put('/movie_countries/{id}', [MovieCountryController::class, 'update']);
    Route::delete('/movie_countries/{id}', [MovieCountryController::class, 'destroy']);

    //Movie Languages
    Route::post('/movie_languages', [MovieLanguageController::class, 'store']);
    Route::put('/movie_languages/{id}', [MovieLanguageController::class, 'update']);
    Route::delete('/movie_languages/{id}', [MovieLanguageController::class, 'destroy']);

    //Movie Photo
    Route::post('/movie_photos', [MoviePhotoController::class, 'store']);
    Route::put('/movie_photos/{id}', [MoviePhotoController::class, 'update']);
    Route::delete('/movie_photos/{id}', [MoviePhotoController::class, 'destroy']);

    //Saved Movies
    Route::get('/saved_movies', [SavedMovieController::class, 'index']);
    Route::post('/saved_movies', [SavedMovieController::class, 'store']);
    Route::get('/saved_movies/{movieId}', [SavedMovieController::class, 'show']);
    Route::put('/saved_movies/{movieId}', [SavedMovieController::class, 'update']);
    Route::delete('/saved_movies/{movieId}', [SavedMovieController::class, 'destroy']);

    //Rated Movies
    Route::get('/rated_movies', [RatedMovieController::class, 'index']);
    Route::post('/rated_movies', [RatedMovieController::class, 'storeUpdate']);
    Route::get('/rated_movies/{id}', [RatedMovieController::class, 'show']);
    Route::delete('/rated_movies/{id}', [RatedMovieController::class, 'destroy']);

    //Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/success', [PaymentController::class, 'show']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

    //Requested Movies
    Route::get('/requested_movies', [RequestedMovieController::class, 'index']);
    Route::post('/requested_movies', [RequestedMovieController::class, 'store']);
    Route::get('/requested_movies/{id}', [RequestedMovieController::class, 'show']);
    Route::put('/requested_movies/{id}', [RequestedMovieController::class, 'update']);
    Route::delete('/requested_movies/{id}', [RequestedMovieController::class, 'destroy']);

    //Requested Movie Responses
    Route::get('/requested_movie_responses', [RequestedMovieResponseController::class, 'index']);
    Route::post('/requested_movie_responses', [RequestedMovieResponseController::class, 'store']);
    Route::get('/requested_movie_responses/{id}', [RequestedMovieResponseController::class, 'show']);
    Route::put('/requested_movie_responses/{id}', [RequestedMovieResponseController::class, 'update']);
    Route::delete('/requested_movie_responses/{id}', [RequestedMovieResponseController::class, 'destroy']);

    //Movie
    Route::post('/movies', [MovieController::class, 'store']);
    Route::put('/movies/{id}', [MovieController::class, 'update']);
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

    //Movie Videos
    Route::post('movie_videos', [MovieVideoController::class, 'store']);
    Route::put('movie_videos/{id}', [MovieVideoController::class, 'update']);
    Route::delete('movie_videos/{id}', [MovieVideoController::class, 'destroy']);

    Route::post('/post', [PostController::class, 'store']);


    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////


});
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

Route::middleware('api')->group(function () {


    //Genres
    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);

    //Auth
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/google', [AuthController::class, 'google']);

    //Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);

    //Artists
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::get('/artists/{id}', [ArtistController::class, 'show']);

    //Country
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/countries/{countyCode}', [CountryController::class, 'show']);

    //Language
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::get('/languages/{id}', [LanguageController::class, 'show']);

    //TV-Shows 
    Route::get('/tv_shows', [TVShowController::class, 'index']);
    Route::get('/tv_shows/{id}', [TVShowController::class, 'show']);

    //Years
    Route::get('years', [YearController::class, 'index']);


    //Movie Artists
    Route::get('/movie_artists', [MovieArtistController::class, 'index']);
    Route::get('/movie_artists/{id}', [MovieArtistController::class, 'show']);
    Route::get('/movie_artists/{movieId}/movie', [MovieArtistController::class, 'showByMovieId']);

    //Movie Genres
    Route::get('/movie_genres', [MovieGenreController::class, 'index']);
    Route::get('/movie_genres/{id}', [MovieGenreController::class, 'show']);
    Route::get('/movie_genres/{genreId}/movies', [MovieGenreController::class, 'genreIdMovie']);
    Route::get('/movie_genres/{movieId}/genres', [MovieGenreController::class, 'movieIdGerne']);

    //Movie Countries
    Route::get('/movie_countries', [MovieCountryController::class, 'index']);
    Route::get('/movie_countries/{id}', [MovieCountryController::class, 'show']);
    Route::get('/movie_countries/{countryCode}/movies', [MovieCountryController::class, 'countryCodeMovie']);
    Route::get('/movie_countries/{movieId}/countries', [MovieCountryController::class, 'movieIdCountry']);

    //Movie Languages
    Route::get('/movie_languages', [MovieLanguageController::class, 'index']);
    Route::get('/movie_languages/{id}', [MovieLanguageController::class, 'show']);
    Route::get('/movie_languages/{languageCode}/movies', [MovieLanguageController::class, 'languageCodeMovie']);
    Route::get('/movie_languages/{movieId}/languages', [MovieLanguageController::class, 'movieIdLanguage']);

    //Movie Photos
    Route::get('movie_photos', [MoviePhotoController::class, 'index']);
    Route::get('movie_photos/{id}', [MoviePhotoController::class, 'show']);
    Route::get('/movie_photos/{movieId}/photos', [MoviePhotoController::class, 'movieIdPhoto']);

    //Movies
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/latest', [MovieController::class, 'latest']);
    Route::get('/popular', [MovieController::class, 'popular']);
    Route::get('/top_rated', [MovieController::class, 'topRated']);
    Route::get('/movies/{id}', [MovieController::class, 'show']);
    Route::get('/movies/tv_shows/{tvShowId}', [MovieController::class, 'tvShow']);
    Route::get('/movies/years/{year}', [MovieController::class, 'year']);
    Route::get('/movies/search/title', [MovieController::class, 'search']);
    Route::get('/movies/filter/movie', [MovieController::class, 'filter']);


    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////
    ///////////////////////////Not Done Yet/////////////////////////////////////

    //Movie Videos
    Route::get('movie_videos', [MovieVideoController::class, 'index']);
    Route::get('movie_videos/{id}', [MovieVideoController::class, 'show']);
    Route::get('/movie_videos/{movieId}/trailer', [MovieVideoController::class, 'movieIdTrailer']);
});
