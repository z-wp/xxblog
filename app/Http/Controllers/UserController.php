<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ImageRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests;
use App\User;
use Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $imageRepository;

    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(UserRepository $userRepository, ImageRepository $imageRepository)
    {
        $this->userRepository = $userRepository;
        $this->imageRepository = $imageRepository;
        $this->middleware('auth', ['except' => 'show']);
    }

    public function show($name)
    {
        $user = $this->userRepository->get($name);
        return view('user.show', compact('user'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('user.settings', compact('user'));
    }

    public function pictures()
    {
        $user = auth()->user();
        return view('user.pictures', compact('user'));
    }

    public function socials()
    {
        $user = auth()->user();
        return view('user.socials', compact('user'));
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications;
        $readNotificationsCount = $user->readNotifications->count();
        return view('user.notifications', compact('notifications', 'user', 'readNotificationsCount'));
    }

    public function deleteReadNotifications(Request $request)
    {
        $type = $request->get('type');
        $user = auth()->user();
        $builder = $user->readNotifications();
        if ($type)
            $builder = $builder->where('type', $type);
        $count = $builder->delete();
        return back()->withSuccess("Deleted $count read notifications.");
    }

    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        if ($notification->delete())
            return back()->withSuccess('Deleted succeed.');
        return back()->withErrors('Deleted failed.');
    }

    public function readNotification(Request $request, $id)
    {
        if ($id == "all") {
            $type = $request->get('type');
            $builder = auth()->user()->unreadNotifications();
            if ($type)
                $builder = $builder->where('type', $type);
            $count = $builder->get()->markAsRead();
            return back()->with('success', __('web.EDIT_SUCCESS').$count);
        } else {
            $notification = auth()->user()->unreadNotifications()->findOrFail($id);
            $notification->markAsRead();
            return back()->with('success', __('web.EDIT_SUCCESS'));
        }
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $this->checkPolicy('manager', $user);
        $this->validate($request, [
            'name' => [
                'required',
                'max:16',
                'min:3',
                Rule::unique('users')->ignore($user->id),
                'regex:/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',
            ],
            'website' => 'nullable|url',
            'description' => 'max:255',
        ]);

        if ($this->userRepository->update($request, $user)) {
            return back()->with('success', __('web.EDIT_SUCCESS'));
        }
        return back()->withErrors(__('web.EDIT_FAIL'));
    }


    public function updateProfile(Request $request)
    {
        return $this->updateUserImage($request, 'profile_image', 'profile', 1024);
    }

    public function updateAvatar(Request $request)
    {
        return $this->updateUserImage($request, 'avatar', 'avatar');
    }

    private function updateUserImage(Request $request, $field, $path, $max = 512)
    {
        $user = auth()->user();
        $url = $request->get('url');
        if ($url) {
            $user->$field = $url;
        } else {
            $milliseconds = getMilliseconds();
            $file = $request->file('image');
            if ($file) {
                $key = 'user/' . $user->name . "/$path/$milliseconds." . $file->guessClientExtension();
                if ($url = $this->uploadImage($user, $request, $key, $max)) {
                    $user->$field = $url;
                }
            } else {
                return back()->withErrors(__('web.PLEASE_KEY_IN_URL_OR_IMG'));
            }
        }

        if ($user->save()) {
            $this->userRepository->clearCache();
            return back()->with('success', __('web.EDIT_SUCCESS'));
        }
        return back()->withErrors(__('web.EDIT_FAIL'));
    }

    private function uploadImage(User $user, Request $request, $key, $max = 512, $fileName = 'image')
    {
        $this->checkPolicy('manager', $user);
        $this->validate($request, [
            $fileName => 'required|image|mimes:jpeg,jpg,png|max:' . $max,
        ]);
        $image = $request->file($fileName);
        return $this->imageRepository->uploadImage($image, $key);
    }

}
