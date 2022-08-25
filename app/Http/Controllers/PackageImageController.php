<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageImageRequest;
use App\Http\Requests\UpdatePackageImageRequest;
use App\Models\PackageImage;

class PackageImageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(PackageImage::class, 'image');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePackageImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function show(PackageImage $packageImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePackageImageRequest  $request
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageImageRequest $request, PackageImage $packageImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(PackageImage $packageImage)
    {
        //
    }
}
