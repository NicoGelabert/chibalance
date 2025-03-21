<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Http\Resources\AboutListResource;
use App\Http\Resources\AboutResource;
use App\Models\Api\About;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');
        
        $query = About::query()
            ->where('headline', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
        
        return AboutListResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(AboutRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        
        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }
        
        $about = About::create($data);
        
        return new AboutResource($about);
    }
    
    /**
     * Display the specified resource.
     * @param \App\Models\About $about
     * @return \Illuminate\Http\Response
     */
    public function show(About $about)
    {
        return new AboutResource($about);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\About      $about
     * @return \Illuminate\Http\Response
     */
    public function update(AboutRequest $request, About $about)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;
        
        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
            
            // If there is an old image, delete it
            if ($about->image) {
                Storage::deleteDirectory('/public/' . dirname($about->image));
            }
        }
        
        $about->update($data);
        
        return new AboutResource($about);
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\About $about
     * @return \Illuminate\Http\Response
     */
    public function destroy(About $about)
    {
        $about->delete();
        
        return response()->noContent();
    }
    
    
    private function saveImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName
        ())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName
            ()}\"");
        }
        
        return $path . '/' . $image->getClientOriginalName();
    }
}

