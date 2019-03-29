<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Repositories\TagRepository;
use App\Tag;
use Illuminate\Http\Request;
use XblogConfig;

class TagController extends Controller
{
    public $tagRepository;

    /**
     * TagController constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:tags',
        ]);

        if ($this->tagRepository->create($request)) {
            $this->tagRepository->clearCache();
            return back()->with('success', 'Tag' . $request['name'] . __('web.CREATE_SUCCESS'));
        } else
            return back()->with('error', 'Tag' . $request['name'] . __('web.CREATE_FAIL'));
    }

    public function edit(Tag $tag)
    {
        return view('admin.tag.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->validate($request, [
            'name' => 'required|unique:tags',
        ]);

        if ($this->tagRepository->update($request, $tag)) {
            return redirect()->route('admin.tags')->with('success', __('web.TAG') . $request['name'] . __('web.EDIT_SUCCESS'));
        }

        return back()->withInput()->withErrors(__('web.CATEGORY') . $request['name'] . __('web.EDIT_FAIL'));
    }

    public function destroy(Tag $tag)
    {
        if ($tag->posts()->withoutGlobalScopes()->count() > 0) {
            return redirect()->route('admin.tags')->withErrors($tag->name . __('web.HAVE_POST_CANT_REMOVE'));
        }
        if ($tag->delete()) {
            $this->tagRepository->clearCache();
            return back()->with('success', $tag->name . __('web.REMOVE_SUCCESS'));
        }
        return back()->withErrors($tag->name . __('web.REMOVE_FAIL'));
    }
}
