<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" xmlns:v-on="http://www.w3.org/1999/xhtml">
<head>
    @php
        $site_title = (isset($site_title) && $site_title ? $site_title : '');
        $site_description = (isset($site_description) && $site_description ? $site_description : '');
    @endphp
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="{{ $author or '' }}">
    <title>@yield('title') - {{ $site_title }}</title>
    <meta name="keywords" content="@yield('keywords'){{ $site_keywords or '' }}">
    <meta name="description" content="@yield('description') {{ $site_description }}">
    <link rel="canonical" href="{{ request()->url() }}">
    <meta property="og:url" content="{{ request()->url() }}">
    @if(isset($page) && $page->created_at && $page->updated_at)
        <meta property="og:type" content="article">
        <meta property="article:published_time" content="{{ $page->created_at->format('c') }}">
        <meta property="article:modified_time" content="{{ $page->updated_at->format('c') }}">
    @elseif(isset($post) && $post->created_at && $post->updated_at)
        <meta property="og:type" content="article">
        <meta property="article:published_time" content="{{ $post->created_at->format('c') }}">
        <meta property="article:modified_time" content="{{ $post->updated_at->format('c') }}">
    @else
        <meta property="og:type" content="website">
    @endif
    <meta property="og:title" content="@yield('title') - {{ $site_title }}">
    <meta property="og:site_name" content="{{ $site_title }}">
    <meta property="og:description" content="@yield('description') {{ $site_description }}">
    <meta name="twitter:title" content="@yield('title') - {{ $site_title }}">
    <meta name="twitter:description" content="@yield('description') {{ $site_description }}">
    <meta name="twitter:url" content="{{ request()->url() }}">
    @if(isset($social_twitter) && $social_twitter)
        <meta name="twitter:site" content="{{ substr(strrchr($social_twitter,'/'), 1) }}">
        <meta name="twitter:creator" content="{{ '@'.substr(strrchr($social_twitter,'/'), 1) }}">
    @endif
    @if(isset($social_facebook) && $social_facebook)
        <meta property="article:publisher" content="{{ substr(strrchr($social_facebook,'/'), 1) }}">
    @endif
    @if(isset($post) && $post->cover_img)
        <meta property="og:image" content="{{ $post->cover_img }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $post->cover_img }}">
    @elseif(!isset($post) && isset($header_bg_image) && $header_bg_image)
        <meta property="og:image" content="{{ $header_bg_image }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:image" content="{{ $header_bg_image}}">
    @endif
    <meta name="theme-color" content="#52768e">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('css')
    <script>
        window.XblogConfig = <?php echo json_encode([
            'csrfToken' => csrf_token(), 'github_username' => isset($github_username) ? $github_username : '',
            'captcha_config' => config('captcha.use')
        ]); ?>
    </script>
    @include('widget.google_analytics')
</head>
<body>
@includeWhen(!isset($include_header) || $include_header, 'layouts.header')
<div id="content-wrap">
    @if((!isset($include_msg) || $include_msg ) && (session()->has('success') || (isset($errors)&&count($errors) > 0)))
        <div class="container">
            @include('partials.msg')
        </div>
    @endif
    @yield('content')
</div>
@includeWhen(!isset($include_footer) || $include_footer, 'layouts.footer')
<script src="{{ mix('js/app.js') }}"></script>
@yield('script')
</body>
</html>
