<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Booking::class, 'booking');
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
            $bookings = $user->bookings()->paginate(10);
        } else {
            $bookings = Booking::with('user')->paginate(10);
        }

        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = auth()->user()->bookings()->create($request->all());

        return $booking ? $this->sendResponse($booking, 'Booking successfully stored!')
            : $this->sendError($booking, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking $booking
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Booking $booking): JsonResource
    {
        return new BookingResource($booking->load('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookingRequest  $request
     * @param  \App\Models\Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        if ($booking->update($request->all())) {
            return $this->sendResponse($booking, 'Booking successfully updated!');
        }

        return $this->sendError($booking, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $booking): JsonResponse
    {
        if ($booking->delete()) {
            return $this->sendResponse($booking, 'Booking successfully deleted!');
        }

        return $this->sendError($booking, 'There has been a mistake!', 503);
    }
}
