<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Language\LanguageResourceCollection;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class LanguageController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/languages",
     *     tags={"Languages"},
     *     summary="Get List Data",
     *     description="enter your  here",
     *     operationId="languages",
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    public function index()
    {
        $languages = Language::all();

        if ($languages->count() > 0) {
            $res = new LanguageResourceCollection($languages);
            return $this->sendSuccess(200, 'Languages Foun', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }
    /**
     * @OA\Post(
     *     path="/api/languages",
     *     tags={"Languages"},
     *     summary="languages",
     *     description="languages",
     *     operationId="Languages",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form languages",
     *          @OA\JsonContent(
     *            required={"language_code", "language_name"},
     *              @OA\Property(property="language_code", type="string"),
     *              @OA\Property(property="language_name", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *        
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|unique:languages|max:2',
            'language_name' => 'required|string|unique:languages',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation faild', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $language = Language::create($request->all());

        $res = new LanguageResource($language);
        return $this->sendSuccess(201, 'Langueage created successfully', $res);
    }
    /**
     * @OA\Get(
     *     path="/api/languages/{id}",
     *     tags={"Languages"},
     *     summary="Detail",
     *     description="-",
     *     operationId="languages/GetById",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="return model admin"
     *     )
     * )
     */
    public function show(string $languageCode)
    {
        $language = Language::where('language_code', $languageCode)->first();

        if (!$language) {
            return $this->sendError(404, 'Language not found');
        }

        $res = new LanguageResource($language);
        return $this->sendSuccess(200, 'Language Found', $res);
    }
    /**
     * @OA\Put(
     *     path="/api/languages/{id}",
     *     tags={"Languages"},
     *     summary="Update languages",
     *     description="-",
     *     operationId="languages/update",
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
     *             required={"language_code", "language_name"},
     *              @OA\Property(property="language_code", type="string"),
     *              @OA\Property(property="language_name", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    public function update(Request $request, string $languageCode)
    {
        $language = Language::where('language_code', $languageCode)->first();

        if (!$language) {
            return $this->sendError(404, 'Language not found');
        }

        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|max:2|unique:languages,language_code,' . $languageCode,
            'language_name' => 'required|string|unique:languages,language_name,' . $languageCode,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation fails', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $language->update($request->all());

        $res = new LanguageResource($language);
        return $this->sendSuccess(200, 'Language updated successfully', $res);
    }
    /**
     * @OA\Delete(
     *     path="/api/languages/{id}",
     *     tags={"Languages"},
     *     summary="Delete languages",
     *     description="-",
     *     operationId="languages/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    public function destroy(string $languageCode)
    {
        $language = Language::where('language_code', $languageCode)->first();

        if (!$language) {
            return $this->sendError(404, 'Language not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $language->delete();
        return $this->sendSuccess(200, 'Language deleted successfully');
    }
}
