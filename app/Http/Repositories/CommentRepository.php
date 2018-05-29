<?php
/**
 * Created by PhpStorm.
 * User: lufficc
 * Date: 2016/8/19
 * Time: 17:41
 */

namespace App\Http\Repositories;

use App\Comment;
use App\Notifications\ReceivedComment;
use App\Scopes\VerifiedCommentScope;
use Illuminate\Http\Request;
use Lufficc\Exception\CommentNotAllowedException;
use Lufficc\MarkDownParser;
use Lufficc\Mention;

/**
 * Class CommentRepository
 * @package App\Http\Repository
 */
class CommentRepository extends Repository
{
    static $tag = 'comment';
    protected $mention;

    /**
     * PostRepository constructor.
     * @param Mention $mention
     */
    public function __construct(Mention $mention)
    {
        $this->mention = $mention;
    }

    public function model()
    {
        return app(Comment::class);
    }

    public function count()
    {
        $count = $this->remember($this->tag() . '.count', function () {
            return $this->model()->withTrashed()->count();
        });
        return $count;
    }

    private function getCacheKey($commentable_type, $commentable_id, $includeUnVerified)
    {
        return $commentable_type . '.' . $commentable_id . 'comments.' . $includeUnVerified;
    }

    public function getByCommentable($commentable_type, $commentable_id, $includeUnVerified = null)
    {
        if ($includeUnVerified == null) $includeUnVerified = isAdminById(auth()->id());
        $comments = $this->remember($this->getCacheKey($commentable_type, $commentable_id, $includeUnVerified), function () use ($commentable_type, $commentable_id, $includeUnVerified) {
            $commentable = app($commentable_type)->where('id', $commentable_id)->select(['id'])->firstOrFail();
            $query = $commentable->comments()->with(['user'])->orderBy('id', 'asc');
            if ($includeUnVerified) {
                $query->withoutGlobalScope(VerifiedCommentScope::class);
            }
            return $query->get();
        });
        return $comments;
    }

    public function getAll($page = 20)
    {
        $comments = $this->remember('comment.page.' . $page . '' . request()->get('page', 1), function () use ($page) {
            return Comment::withoutGlobalScopes()->orderBy('created_at', 'desc')->paginate($page);
        });
        return $comments;
    }

    public function create(Request $request)
    {
        $this->clearCache();

        $comment = new Comment();
        $commentable_id = $request->get('commentable_id');
        $commentable = app($request->get('commentable_type'))->where('id', $commentable_id)->firstOrFail();

        if (!$commentable->isShownComment() || !$commentable->allowComment()) {
            throw new CommentNotAllowedException;
        }

        if (auth()->check()) {
            $user = auth()->user();
            $comment->user_id = $user->id;
            $comment->username = $user->name;
            $comment->email = $user->email;
            if (isAdminById($user->id))
                $comment->status = 1;
        } else {
            $comment->username = $request->get('username');
            $comment->email = $request->get('email');
            $comment->site = $request->get('site');
        }
        $reply_id = $request->get('reply_id');
        if ($reply_id) {
            $comment->reply_id = $reply_id;
        }
        $content = $request->get('content');
        $comment->ip_id = $request->ip();
        $comment->content = $this->mention->parse($content);
        $markdownParser = new MarkDownParser($comment->content);
        $comment->html_content = $markdownParser->clean(true)->parse();
        $result = $commentable->comments()->save($comment);


        /**
         * mention user after comment saved
         */
        getAdminUser()->notify(new ReceivedComment($comment));
        $this->mention->mentionUsers($comment, getMentionedUsers($content), $comment->html_content);

        return $result;
    }

    public function update($content, $comment)
    {
        $comment->content = $this->mention->parse($content);
        $markdownParser = new MarkDownParser($comment->content);
        $comment->html_content = $markdownParser->clean(true)->parse();
        $result = $comment->save();
        if ($result)
            $this->clearCache();
        return $result;
    }

    public function delete(Comment $comment, $force = false)
    {
        $this->clearCache();
        if ($force)
            return $comment->forceDelete();
        return $comment->delete();
    }

    public function tag()
    {
        return CommentRepository::$tag;
    }

}