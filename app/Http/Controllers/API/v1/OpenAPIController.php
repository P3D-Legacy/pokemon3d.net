<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\Reader;
use cebe\openapi\Writer;
use Illuminate\Http\Request;

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
     * @response 200 {}
     * @unauthenticated
     **/
    public function index()
    {
        # Get YAML from storage
        $file_path = storage_path('app/scribe/openapi.yaml');
        try {
            $openapi = Reader::readFromYamlFile($file_path);
        } catch (IOException|TypeErrorException|UnresolvableReferenceException $e) {
            return response('', 500)->header('Content-Type', 'application/json');
        }
        $json = Writer::writeToJson($openapi);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
