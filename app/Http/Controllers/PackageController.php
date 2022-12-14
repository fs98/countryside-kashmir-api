<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;

class PackageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Package::class, 'package');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::with([
            'category',
            'destinations',
            'user',
            'author'
        ])->paginate(10);

        return PackageResource::collection($packages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePackageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageRequest $request)
    {
        $requestData = $this->uploadImage($request, 'packages');

        $package = auth()->user()->packages()->create($requestData);

        if ($package) {
            return $this->sendResponse($package, 'Package successfully stored!');
        }

        return $this->sendError($package, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        return new PackageResource($package->load([
            'category',
            'destinations',
            'user',
            'author'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePackageRequest  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        $requestData = $this->uploadImage($request, 'packages', $package->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        if ($package->update($requestData)) {
            return $this->sendResponse($package, 'Package successfully updated!');
        }

        return $this->sendError($package, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        if ($package->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($package->image);

            return $this->sendResponse($package, 'Package successfully deleted!');
        }

        return $this->sendError($package, 'There has been a mistake!', 503);
    }
}
