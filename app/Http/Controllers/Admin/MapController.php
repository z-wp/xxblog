<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Repositories\MapRepository;
use Illuminate\Http\Request;
use App\Http\Requests;

class MapController extends Controller
{
    protected $mapRepository;

    /**
     * MapController constructor.
     * @param MapRepository $mapRepository
     */
    public function __construct(MapRepository $mapRepository)
    {
        $this->mapRepository = $mapRepository;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|unique:maps',
            'value' => 'required',
        ]);
        if ($this->mapRepository->create($request))
            return back()->with('success', __('web.SAVE_SUCCESS'));
        else
            return back()->withErrors(__('web.SAVE_FAIl'));
    }

    public function get($key)
    {
        $map = $this->mapRepository->get($key);
        if (is_null($map))
            abort(404);
        return $map;
    }
}
