<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlogController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Blog::class, 'blog');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(): JsonResource
    {
        $blogs = Blog::with([
            'user',
            'author'
        ])->paginate(10);

        return BlogResource::collection($blogs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBlogRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {

        $requestData = $this->uploadImage($request, 'blogs');

        $blog = auth()->user()->blogs()->create($requestData);

        if ($blog) {
            return $this->sendResponse($blog->load('user'), 'Blog successfully stored!');
        }

        return $this->sendError($blog, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Blog $blog): JsonResource
    {
        return new BlogResource($blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogRequest  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'blogs', $blog->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        if ($blog->update($requestData)) {
            return $this->sendResponse($blog->load('user'), 'Blog successfully updated!');
        }

        return $this->sendError($blog, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Blog $blog): JsonResponse
    {
        if ($blog->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($blog->image);

            return $this->sendResponse($blog, 'Blog successfully deleted!');
        }

        return $this->sendError($blog, 'There has been a mistake!', 503);
    }
}
