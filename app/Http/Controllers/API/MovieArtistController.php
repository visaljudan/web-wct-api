<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieArtist\MovieArtistResource;
use App\Http\Resources\MovieArtist\MovieArtistResourceCollection;
use App\Models\MovieArtist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieArtistController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/movie_artists",
     *     tags={"Movie_Artists"},
     *     summary="Get List movie_artists Data",
     *     description="enter your movie_artists here",
     *     operationId="Movie_Artists",
     *     @OA\Response(
     *         response="default",
     *         description="return array model movie_artists"
     *     )
     * )
     */
    public function index()
    {
        $movieArtists = MovieArtist::all();

        if ($movieArtists->count() > 0) {
            $res = new MovieArtistResourceCollection($movieArtists);
            return $this->sendSuccess(200, 'Artists in Movies Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/movie_artists",
     *     tags={"Movie_Artists"},
     *     summary="movie_artists",
     *     description="-",
     *     operationId="movie_artists",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form movie_artists",
     *          @OA\JsonContent(
     *            required={"movie_id", "artist_id", "role_id","movie_artist_name", },
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="artist_id", type="string"),
     * @OA\Property(property="role_id", type="string"),
     * @OA\Property(property="movie_artist_name", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'artist_id' => 'required|exists:artists,id',
            'role_id' => 'required|exists:roles,id',
            'movie_artist_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not alloweds');
        }

        $movieArtist = MovieArtist::create($request->all());

        $res = new MovieArtistResource($movieArtist);
        return $this->sendSuccess(201, 'Movie artist created successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/movie_artists/{id}",
     *     tags={"Movie_Artists"},
     *     summary="Detail",
     *     description="-",
     *     operationId="movie_artists/GetById",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
    public function show($id)
    {
        $movieArtist = MovieArtist::find($id);

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        $res = new MovieArtistResource($movieArtist);
        return $this->sendSuccess(200, 'Movie Artist found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/movie_artists/{id}",
     *     tags={"Movie_Artists"},
     *     summary="Update artist",
     *     description="-",
     *     operationId="movie_artists/update",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="form admin",
     *          @OA\JsonContent(
     *             required={"movie_id", "artist_id", "role_id", "movie_artist_name"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="artist_id", type="string"),
     *              @OA\Property(property="role_id", type="string"),
     *              @OA\Property(property="movie_artist_name", type="string"),
     *          ),
     *      ),
     *      @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $movieArtist = MovieArtist::find($id);

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'artist_id' => 'required|exists:artists,id',
            'role_id' => 'required|exists:roles,id',
            'movie_artist_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $movieArtist->update($request->all());

        $res = new MovieArtistResource($movieArtist);
        return $this->sendSuccess(200, 'Movie artist updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/movie_artists/{id}",
     *     tags={"Movie_Artists"},
     *     summary="Delete movie_artists",
     *     description="-",
     *     operationId="movie_artists/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
    public function destroy($id)
    {
        $movieArtist = MovieArtist::find($id);

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieArtist->delete();
        return $this->sendSuccess(200, 'Movie artist deleted successfully');
    }


    public function showByMovieId($movieId)
    {
        $movieArtist = MovieArtist::where('movie_id', $movieId)->get();

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        $res = new MovieArtistResourceCollection($movieArtist);
        return $this->sendSuccess(200, 'Movie Artist found', $res);
    }
}
