<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Repositories\PageRepository;
use App\Page;
use Illuminate\Http\Request;

use App\Http\Requests;

class PageController extends Controller
{

    protected $pageRepository;

    /**
     * PageController constructor.
     * @param $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.page.create');
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
            'name' => 'required|unique:pages',
            'display_name' => 'required',
            'content' => 'required',
        ]);

        if ($this->pageRepository->create($request)) {
            return redirect()->route('admin.index')->with('success', __('web.PAGE') . $request['name'] . __('web.CREATE_SUCCESS'));
        }
        return back()->withInput()->with('error', '页面' . $request['name'] . __('web.CREATE_FAIL'));
    }

    public function pageShowing($page)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications;
            foreach ($unreadNotifications as $notifications) {
                $comment = $notifications->data;
                if ($comment['commentable_type'] == 'App\Page' && $comment['commentable_id'] == $page->id) {
                    $notifications->markAsRead();
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Page $page)
    {
        return view('admin.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Page $page
     * @return mixed
     * @internal param $name
     * @internal param string $page
     * @internal param int $id
     */
    public function update(Request $request, Page $page)
    {
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
            'content' => 'required',
        ]);
        if ($this->pageRepository->update($request, $page)) {
            return redirect()->route('admin.index')->with('success', __('web.PAGE') . $request['name'] . __('web.EDIT_SUCCESS'));
        }
        return back()->withInput()->withErrors(__('web.PAGE') . $request['name'] . __('web.EDIT_FAIL'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->pageRepository->clearCache();
    }
}
