<?php
/**
 * Created by PhpStorm.
 * User: lufficc
 * Date: 2016/8/19
 * Time: 17:41
 */

namespace App\Http\Repositories;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Storage;


/**
 * Class TagRepository
 * @package App\Http\Repository
 */
class ImageRepository extends FileRepository
{
    static $tag = 'image';

    public function getAll($size = 12)
    {
        $maps = $this->remember('image.page.' . $size . request()->get('page', 1), function () use ($size) {
            return File::where('type', 'image')->orderBy('created_at', 'desc')->paginate($size);
        });
        return $maps;
    }

    public function uploadImage(UploadedFile $file, $key)
    {
        return $this->uploadFile($file, $key);
    }

    public function uploadImageForBlog(Request $request, $html)
    {
        $file = $request->file('image');
        $name = $file->getClientOriginalName() or 'image';
        $data = [];
        $url = $this->uploadFile($file);
        if ($url) {
            if ($html) {
                return true;
            } else {
                $data['url'] = $url;
                $data['filename'] = $name;
            }
        } else {
            if ($html)
                return false;
            $data['error'] = 'upload failed';
        }
        return $data;
    }

    public function count()
    {
        $count = $this->remember($this->tag() . '.count', function () {
            return File::where('type', $this->type())->count();
        });
        return $count;
    }

    public function tag()
    {
        return ImageRepository::$tag;
    }

    public function type()
    {
        return ImageRepository::$tag;
    }
}