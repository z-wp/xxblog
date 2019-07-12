<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use XblogConfig;

class CategoryController extends Controller
{
    protected $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'description' => 'max:1024',
            'cover_img' => 'max:255',
        ]);
        if ($this->categoryRepository->create($request))
            return back()->with('success', __('web.CATEGORY') . $request['name'] . __('web.CREATE_SUCCESS'));
        else
            return back()->with('error', __('web.CATEGORY') . $request['name'] . __('web.CREATE_FAIL'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Category $category
     * @return mixed
     * @internal param int $id
     */
    public function update(Request $request, Category $category)
    {

        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'max:1024',
            'cover_img' => 'max:255',
        ]);

        if ($this->categoryRepository->update($request, $category)) {
            return redirect()->route('admin.categories')->with('success', __('web.CATEGORY') . $request['name'] . __('web.EDIT_SUCCESS'));
        }

        return back()->withInput()->withErrors(__('web.CATEGORY') . $request['name'] . __('web.EDIT_FAIL'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return mixed
     * @internal param int $id
     */
    public function destroy(Category $category)
    {
        if ($category->posts()->withoutGlobalScopes()->count() > 0) {
            return redirect()->route('admin.categories')->withErrors($category->name . __('web.HAVE_POST_CANT_REMOVE'));
        }
        $this->categoryRepository->clearCache();
        if ($category->delete())
            return back()->with('success', $category->name . __('web.REMOVE_SUCCESS'));
        return back()->withErrors($category->name . __('web.REMOVE_FAIL'));
    }
}
