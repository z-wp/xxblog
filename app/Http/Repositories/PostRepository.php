<?php
/**
 * Created by PhpStorm.
 * User: lufficc
 * Date: 2016/8/19
 * Time: 17:41
 */

namespace App\Http\Repositories;

use App\Configuration;
use App\Post;
use App\Tag;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use function GuzzleHttp\Promise\queue;
use Illuminate\Http\Request;
use Lufficc\MarkDownParser;
use PhpParser\NodeTraverser;

/**
 * design for cache
 *
 *
 * Class PostRepository
 * @package App\Http\Repository
 */
class PostRepository extends Repository
{

    protected $markDownParser;

    static $tag = 'post';

    /**
     * PostRepository constructor.
     * @param MarkDownParser $markDownParser
     */
    public function __construct(MarkDownParser $markDownParser)
    {
        $this->markDownParser = $markDownParser;
    }

    public function model()
    {
        return app(Post::class);
    }

    public function count()
    {
        $count = $this->remember($this->tag() . '.count', function () {
            return $this->model()->withoutGlobalScopes()->count();
        });
        return $count;
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function pagedPostsWithoutGlobalScopes($page = 20)
    {
        $posts = $this->remember('post.WithOutContent.' . $page . '' . request()->get('page', 1), function () use ($page) {
            return Post::withoutGlobalScopes()->orderBy('created_at', 'desc')->select(['id', 'title', 'slug', 'deleted_at', 'published_at', 'status'])->paginate($page);
        });
        return $posts;
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function pagedPosts($page = 7)
    {
        $posts = $this->remember('post.page.' . $page . '' . request()->get('page', 1), function () use ($page) {
            return Post::select(Post::selectArrayWithOutContent)->with(['tags', 'category'])->withCount('comments')->orderBy('created_at', 'desc')->paginate($page);
        });
        return $posts;
    }

    /**
     * @param $slug string
     * @return Post
     */
    public function get($slug)
    {
        $post = $this->remember('post.one.' . $slug, function () use ($slug) {
            return Post::where('slug', $slug)->with(['tags', 'category', 'configuration'])->withCount('comments')->firstOrFail();
        });
        return $post;
    }

    public function hotPosts($count = 5)
    {
        $posts = $this->remember('post.achieve.' . $count, function () use ($count) {
            return Post::select([
                'title',
                'slug',
                'view_count',
            ])->withCount('comments')->orderBy('view_count', 'desc')->limit($count)->get();
        });
        return $posts;
    }

    public function achieve()
    {
        $posts = $this->remember('post.achieve', function () {
            return Post::select([
                'id',
                'category_id',
                'title',
                'slug',
                'created_at',
            ])->with(['tags', 'category'])->orderBy('created_at', 'desc')->get();
        });
        return $posts;
    }

    public function recommendedPosts(Post $post, $limit = 5)
    {
        $recommendedPosts = $this->remember('post.recommend.' . $post->slug, function () use ($post, $limit) {
            $category = $post->category;
            $tags = [];
            foreach ($post->tags as $tag) {
                array_push($tags, $tag->name);
            }
            $recommendedPosts = Post
                ::where('category_id', $category->id)
                ->Where('id', '<>', $post->id)
                ->orderBy('view_count', 'desc')
                ->select(Post::selectArrayWithOutContent)
                ->limit($limit)
                ->get();
            return $recommendedPosts;
        });
        return $recommendedPosts;
    }

    public function postCount()
    {
        $count = $this->remember('post-count', function () {
            return Post::count();
        });
        return $count;
    }

    public function getWithoutContent($post_id)
    {
        $post = $this->remember('post.one.wc.' . $post_id, function () use ($post_id) {
            return Post::where('id', $post_id)->select(Post::selectArrayWithOutContent)->first();
        });
        if (!$post)
            abort(404);
        return $post;
    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function create(Request $request)
    {
        $this->clearAllCache();

        $ids = [];
        $tags = $request['tags'];
        if (!empty($tags)) {
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                array_push($ids, $tag->id);
            }
        }
        $status = $request->get('status', 0);
        if ($status == 1) {
            $request['published_at'] = Carbon::now();
        }

        $html_content = $this->markDownParser->parse($request->get('content'), false);
        $html_content = $this->convertHtml($html_content);

        $post = auth()->user()->posts()->create(
            array_merge(
                $request->except(['_token', 'description']),
                [
                    'html_content' => $html_content,
                    'description' => $this->markDownParser->parse($request->get('description'), false),
                ]
            )
        );
        $post->tags()->sync($ids);

        $post->saveConfig($request->all());

        return $post;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return bool|int
     */

    public function update(Request $request, Post $post)
    {
        $this->clearAllCache();

        $ids = [];
        $tags = $request['tags'];
        if (!empty($tags)) {
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                array_push($ids, $tag->id);
            }
        }
        $post->tags()->sync($ids);

        $status = $request->get('status', 0);
        if ($status == 1) {
            $request['published_at'] = Carbon::now();
        }

        $post->saveConfig($request->all());

        $html_content = $this->markDownParser->parse($request->get('content'), false);
        $html_content = $this->convertHtml($html_content);
        return $post->update(
            array_merge(
                $request->except(['_token', 'description']),
                [
                    'html_content' => $html_content,
                    'description' => $this->markDownParser->parse($request->get('description'), false),
                ]
            ));
    }

    /**
     * convert
     * <div class="figure **" caption="caption">
     *     <img ..>
     *     <img ..>
     *     ...
     * </div>
     * to
     * <figure class="**">
     *     <p><img ..></p>
     *     <p><img ..></p>
     *     ...
     *     <figcaption>$caption</figcaption>
     * </figure>
     * @param DOMDocument $dom
     * @return bool
     */
    private function parseDiv(DOMDocument $dom)
    {
        $xpath = new DOMXpath($dom);
        $galleries = $xpath->query('//div[contains(@class, "figure")]');
        $changed = false;
        foreach ($galleries as $gallery) {
            $figure = $dom->createElement('figure');
            $figure->setAttribute('class', trim(str_replace('figure', '', $gallery->getAttribute('class'))));
            $frag = $dom->createDocumentFragment();
            $alt = '';
            foreach ($xpath->query('p/img', $gallery) as $image) {
                if (!$alt)
                    $alt = $image->getAttribute('alt');
                //wrapped with div
                $div = $dom->createElement('div');
                $div->appendChild($image);
                $frag->appendChild($div);
            }
            //empty string if no attribute with the given name is found.
            $caption = $gallery->getAttribute('caption');
            if (!$caption)
                $caption = $alt;
            $frag->appendXML("<figcaption>$caption</figcaption>");
            $figure->appendChild($frag);
            $gallery->parentNode->replaceChild($figure, $gallery);
            $changed = true;
        }
        return $changed;
    }

    /**
     * convert
     * <img .. alt='alt' class='figure'>
     * to
     * <figure>
     *     <img .. alt='alt'>
     *     <figcaption>$alt</figcaption>
     * </figure>
     * @param DOMDocument $dom
     * @return bool
     */
    private function parseImage(DOMDocument $dom)
    {
        $xpath = new DOMXpath($dom);
        $images = $xpath->query('//img[contains(@class, "figure")]');
        $changed = false;
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            $alt = $image->getAttribute('alt');
            $figure = $dom->createElement('figure');
            $frag = $dom->createDocumentFragment(); // create fragment
            $imgNode = $dom->createElement('img');
            $imgNode->setAttribute('src', $src);
            $imgNode->setAttribute('alt', $alt);
            $frag->appendChild($imgNode);
            $frag->appendXML("<figcaption>$alt</figcaption>");
            $figure->appendChild($frag);
            $image->parentNode->replaceChild($figure, $image);
            $changed = true;
        }
        return $changed;

    }

    private function convertHtml($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $changed = $this->parseImage($dom);
        $changed = $this->parseDiv($dom) || $changed;
        if ($changed) {
            $html = $dom->saveHTML();
        }
        return $html;
    }

    public function tag()
    {
        return PostRepository::$tag;
    }
}