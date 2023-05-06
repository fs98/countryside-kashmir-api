<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Message::class, 'message');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function index(): JsonResource
    {
        $user = auth()->user();

        if ($user->hasRole('Client')) {
            $messages = $user->messages()->get();
        } else {
            $messages = Message::with('user')->get();
        }

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMessageRequest $request): JsonResponse
    {
        $message = auth()->user()->messages()->create($request->all());

        return $message ? $this->sendResponse($message, 'Message successfully stored!')
            : $this->sendError($message, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Message $message): JsonResource
    {
        return new MessageResource($message->load('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMessageRequest $request, Message $message): JsonResponse
    {
        if ($message->update($request->all())) {
            return $this->sendResponse($message, 'Message successfully updated!');
        }

        return $this->sendError($message, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Message $message): JsonResponse
    {
        if ($message->delete()) {
            return $this->sendResponse($message, 'Message successfully deleted!');
        }

        return $this->sendError($message, 'There has been a mistake!', 503);
    }
}
