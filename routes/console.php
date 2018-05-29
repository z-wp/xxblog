<?php

use App\Scopes\VerifiedCommentScope;
use Illuminate\Foundation\Inspiring;
use Lufficc\MarkDownParser;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('inspire');


Artisan::command('post {action}', function ($action) {
    $markdownParser = new MarkDownParser();
    switch ($action) {
        case 'des2html':
            foreach (\App\Post::all() as $post) {
                $post->description = $markdownParser->with($post->descriptio)->clean(false)->parse();
                $this->comment($post->save());
            }
            break;
        case 'content2html':
            foreach (\App\Post::all() as $post) {
                try {
                    $post->html_content = $markdownParser->with($post->content)->clean(false)
                        ->figure(true)
                        ->gallery(true)
                        ->toc(true)
                        ->parse();
                    $post->setMetaInfo('toc', $markdownParser->getToc());
                    $post->save();
                    $this->comment('Converted ' . $post->title);
                } catch (Exception $e) {
                    $this->comment($post->title);
                    $this->comment($e);
                }
            }
            break;
    }

})->describe('post { des2html | content2html }');


Artisan::command('avatar', function () {
    $this->comment(\App\User::whereNull('avatar')->update(['avatar' => config('app.avatar')]));
})->describe("set users's null avatar to default avatar");


Artisan::command('xssProtection', function () {
    $mp = new MarkDownParser();
    foreach (\App\Comment::withoutGlobalScopes()->get() as $comment) {
        $this->comment("----------------------------------------------------------------------------------------\n");
        $this->comment($comment->content . "\n\n");
        $this->comment($comment->html_content . "\n\n");
        $parsed = $mp->with($comment->content)->parse();
        $this->comment($parsed . "\n\n");
        $comment->html_content = $parsed;
        $this->comment('save:' . $comment->save());
        $this->comment("----------------------------------------------------------------------------------------");
    }

})->describe("protect user comments from xss");

Artisan::command('comment:delete-uv', function () {
    $result = \App\Comment::withoutGlobalScope(VerifiedCommentScope::class)->where('status', 0)->delete();
    $this->comment("Delete $result comments.");
    cache()->flush();
})->describe("delete un verified comments");

Artisan::command('ip:delete-ub', function () {
    $result = \App\Ip::where('blocked', 0)->delete();
    $this->comment("Delete $result ips.");
    cache()->flush();
})->describe("delete un blocked ips");

Artisan::command('files:generate-url {disk}', function ($disk) {
    $files = \App\File::all();
    $storage = Storage::disk($disk);
    $count = 0;
    foreach ($files as $file) {
        if (isset($file->url) || $file->url) {
            continue;
        }
        $file->url = $storage->url($file->key);
        $file->disk = $disk;
        $file->save();
        $count += 1;
    }
    $this->comment("generated $count urls.");
    cache()->flush();
})->describe("generate url with disk");