<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;

/**
 * @group OpenAPI
 *
 * APIs for OpenAPI.
 */
class OpenAPIController extends Controller
{
    /**
     * Show the OpenAPI documentation in JSON format.
     *
     * @jsonresponse 200 {}
     *
     * @unauthenticated
     **/
    public function index(): \Illuminate\Http\JsonResponse
    {
        // Get and return JSON from storage
        $file_path = storage_path('app/scribe/openapi.json');
        try {
            $json = json_decode(file_get_contents($file_path));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json($json);
    }
}
