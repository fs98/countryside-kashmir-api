<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }


    /**
     * Return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Upload new image.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request, string $destination, string $currentImage = null)
    {
        $requestData = $request->all();

        // If request has image then store it and include image in request
        if ($request->hasFile('image')) {

            // Store image
            $path = Storage::disk('public')->putFile($destination, $request->file('image'));

            // Override image value
            $requestData['image'] = $path;

            // Delete old image if we are updating
            if ($request->isMethod('PUT')) {

                Storage::disk('public')->delete($currentImage);
            }
        }

        return $requestData;
    }
}
