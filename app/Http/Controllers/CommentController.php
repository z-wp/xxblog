<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Repositories\CommentRepository;
use function GuzzleHttp\Promise\all;
use Validator;
use Gate;
use Illuminate\Http\Request;
use XblogConfig;

function siteVerify(Request $request)
{
    if (!config('recaptcha.api_site_key')) {
        return true;
    }

    $url = 'https://recaptcha.net/recaptcha/api/siteverify';
    $data = [
        'secret' => config('recaptcha.api_secret_key'),
        'response' => $request->get('recaptcha_v3_token'),
        'remoteip' => $request->getClientIp()
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $result = json_decode($result);
    if (!$result->success) {
        return false;
    }
    if ($result->score >= 0.5) {
        return true;
    }
    return false;
}


class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->middleware('auth', ['only' => ['destroy', 'update', 'edit']]);
    }

    public function update(Request $request, $comment_id)
    {
        $comment = $this->findComment($comment_id);
        $this->checkPolicy('manager', $comment);

        if ($this->commentRepository->update($request->get('content'), $comment)) {
            $redirect = request('redirect');
            if ($redirect)
                return redirect($redirect)->with('success', __('web.EDIT_SUCCESS'));
            return back()->with('success', __('web.EDIT_SUCCESS'));
        }
        return back()->withErrors(__('web.EDIT_FAIL'));
    }

    public function edit(Comment $comment)
    {
        return view('comment.edit', compact('comment'));
    }

    public function show(Request $request, $commentable_id)
    {
        $commentable_type = $request->get('commentable_type');
        $comments = $this->commentRepository->getByCommentable($commentable_type, $commentable_id, isAdminById(auth()->id()));
        $redirect = $request->get('redirect');
        return view('comment.show', compact('comments', 'commentable', 'redirect'));
    }

    public function store(Request $request)
    {
        if (!$request->get('content')) {
            return response()->json(
                ['status' => 500, 'msg' => 'Comment content must not be empty !']
            );
        }
        if (!auth()->check()) {
            if (!($request->get('username') && $request->get('email'))) {
                return response()->json(
                    ['status' => 500, 'msg' => 'Username and email must not be empty !']
                );
            }
            $pattern = "/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/";
            if (!preg_match($pattern, request('email'))) {
                return response()->json(
                    ['status' => 500, 'msg' => 'An Invalidate Email !']
                );
            }
        }
        if (!siteVerify($request)) {
            return response()->json(
                ['status' => 500, 'msg' => 'reCAPTCHA incorrect! Maybe you are a robot.']
            );
        }

        if ($comment = $this->commentRepository->create($request)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 200,
                    'msg' => 'success',
                    'rendered_html' => view('comment.comment', compact('comment'))->render(),
                    'comment' => [
                        'id' => $comment->id,
                        'reply_id' => $comment->reply_id,
                    ]]);
            }
            return back()->with('success', 'Success');
        }
        if ($request->expectsJson()) {
            return response()->json(['status' => 500, 'msg' => 'failed']);
        }
        return back()->withErrors('Failed');
    }

    public function destroy($comment_id)
    {
        $force = (request('force') == 'true');
        $comment = $this->findComment($comment_id);

        $this->checkPolicy('manager', $comment);

        if ($this->commentRepository->delete($comment, $force)) {
            if (request()->expectsJson()) {
                return response()->json(['status' => 200, 'msg' => 'success']);
            }
            return back()->with('success', __('web.REMOVE_SUCCESS'));
        }
        if (request()->expectsJson()) {
            return response()->json(['status' => 500, 'msg' => __('web.REMOVE_FAIL')]);
        }
        return back()->withErrors(__('web.REMOVE_FAIL'));
    }

    protected function findComment($id)
    {
        return Comment::withoutGlobalScopes()->findOrFail($id);
    }
}
