<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\Reader;
use cebe\openapi\Writer;

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
     * @jsonresponse {}
     *
     * @unauthenticated
     **/
    public function index()
    {
        // Get and return JSON from storage
        $file_path = storage_path('app/scribe/openapi.json');
        try {
            $json = file_get_contents($file_path);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
