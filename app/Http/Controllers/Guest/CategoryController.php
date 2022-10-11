<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::select([
            'id', 'name', 'slug'
        ])
            ->with([
                'packages:id,image,name,slug,days,nights,persons,price,image_alt,category_id',
                'packages.destinations:name'
            ])
            ->get();

        $categories->each(function ($category) {
            $category->makeHidden('id'); // Hide category_id
            $category->packages->each(function ($package) {
                $package->makeHidden('id'); // Hide package_id
                $package->destinations->makeHidden('image_url'); // Hide destinations image_url
            });
        });

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $category->load([
            'packages:id,image,name,slug,days,nights,persons,price,image_alt,category_id',
            'packages.destinations:name'
        ]);

        // Hide Fields
        $category->makeHidden('id');
        $category->packages->makeHidden('id');

        $category->packages->each(function ($package) {
            $package->destinations->makeHidden('image_url');
        });

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
