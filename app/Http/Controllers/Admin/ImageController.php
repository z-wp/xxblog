<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ImageRepository;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $imageRepository;

    /**
     * ImageController constructor.
     * @param $imageRepository
     */
    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function images()
    {
        $images = $this->imageRepository->getAll(32);
        $images->withPath(route('admin.images'));
        $image_count = $this->imageRepository->count();
        return view('admin.images', compact('images', 'image_count'));
    }

    public function images_list()
    {
        $images = $this->imageRepository->getAll(32);
        $images->withPath(route('admin.images'));
        $image_count = $this->imageRepository->count();
        return view('admin.partials.image_list', compact('images', 'image_count'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:5000'
        ]);
        $type = $request->input('type', null);
        if ($request->expectsJson() || ($type != null && $type == 'xrt')) {
            $result = $this->imageRepository->uploadImageForBlog($request, false);
            return response()->json($result, array_key_exists('error', $result) ? 500 : 200);
        } else {
            if ($this->imageRepository->uploadImageForBlog($request, true))
                return back()->with('success', '上传成功');
            return back()->withErrors('上传失败');
        }
    }
}
