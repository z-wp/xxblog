<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Repositories\CommentRepository;
use function GuzzleHttp\Promise\all;
use Validator;
use Gate;
use Illuminate\Http\Request;
use XblogConfig;

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
                return redirect($redirect)->with('success', '修改成功');
            return back()->with('success', '修改成功');
        }
        return back()->withErrors('修改失败');
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
//        dd($request->all());
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

        $validator = Validator::make($request->only('captcha'), ['captcha' => 'required|captcha']);
        if ($validator->fails()) {
            return response()->json(
                ['status' => 500, 'msg' => 'Captcha incorrect !']
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
            return back()->with('success', '删除成功');
        }
        if (request()->expectsJson()) {
            return response()->json(['status' => 500, 'msg' => '删除失败']);
        }
        return back()->withErrors('删除失败');
    }

    protected function findComment($id)
    {
        return Comment::withoutGlobalScopes()->findOrFail($id);
    }
}
