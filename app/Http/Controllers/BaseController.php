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
    public function uploadImage($request, $destination)
    {
        $requestData = $request->all();

        // Store image
        $path = Storage::disk('public')->putFile($destination, $request->file('image'));

        // Override image value
        $requestData['image'] = $path;

        return $requestData;
    }

    /**
     * Update image.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateImage($request, $currentImage, $destination)
    {
        // Upload new image
        if ($request->hasFile('image')) {
            $requestData = $this->uploadImage($request,  $destination);
        }

        // Delete old image
        Storage::disk('public')->delete($currentImage);

        return $requestData;
    }
}
