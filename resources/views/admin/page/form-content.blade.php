<div class="form-group">
    <label for="name" class="form-control-label">页面uri*</label>

    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
           value="{{ isset($page) ? $page->name : old('name') }}"
           autofocus>

    @if ($errors->has('name'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('name') }}</strong>
        </div>
    @endif
</div>


<div class="form-group">
    <label for="display_name" class="form-control-label">页面名称*</label>

    <input id="display_name" type="text" class="form-control{{ $errors->has('display_name') ? ' is-invalid' : '' }}" name="display_name"
           value="{{ isset($page) ? $page->display_name : old('display_name') }}">

    @if ($errors->has('display_name'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('display_name') }}</strong>
        </div>
    @endif
</div>
{{ csrf_field() }}

<div class="form-group">
    <label for="content" class="form-control-label">页面内容*</label>

    <textarea spellcheck="false" id="simplemde-textarea"
              data-save-id="{{ isset($page)?'page.edit.'.$page->id.'.by@' . request()->ip():'page.create' }}"
              type="text" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content"
              rows="25"
              style="line-height: 1.85em; resize: vertical">{{ isset($page) ? $page->content : old('content') }}</textarea>
    @if ($errors->has('content'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('content') }}</strong>
        </div>
    @endif
</div>

<div class="mb-3" style="font-size: 80%">
    <a class="text-secondary font-italic" data-toggle="collapse" href="#page-extra-info" role="button" aria-expanded="false">
        <span title="评论信息" data-toggle="tooltip">其它信息	&raquo;</span>
    </a>
</div>

<div class="collapse" id="page-extra-info">
    <div class="form-group">
        <label for="comment_info" class="form-control-label">评论信息</label>
        <select style="margin-top: 5px" id="comment_info" name="comment_info" class="form-control">
            <?php $comment_info = isset($page) && $page->configuration ? $page->configuration->config['comment_info'] : ''?>
            <option value="default" {{ $comment_info=='default'?' selected' : '' }}>默认</option>
            <option value="force_disable" {{ $comment_info=='force_disable'?' selected' : '' }}>强制关闭</option>
            <option value="force_enable" {{ $comment_info=='force_enable'?' selected' : '' }}>强制开启</option>
        </select>
    </div>
    <div class="form-group">
        <label for="comment_type" class="form-control-label">评论类型</label>
        <select id="comment_type" name="comment_type" class="form-control">
            <?php $comment_type = isset($page) && $page->configuration ? $page->configuration->config['comment_type'] : ''?>
            <option value="default" {{ $comment_type=='default'?' selected' : '' }}>默认</option>
            <option value="raw" {{ $comment_type=='raw'?' selected' : '' }}>自带评论</option>
            <option value="disqus" {{ $comment_type=='disqus'?' selected' : '' }}>Disqus</option>
        </select>
    </div>
    <div class="form-group">
        <label for="allow_resource_comment" class="form-control-label">是否允许评论</label>
        <select id="allow_resource_comment" name="allow_resource_comment" class="form-control">
            <?php $allow_resource_comment = isset($page) ? $page->getConfig('allow_resource_comment', 'default') : 'default'?>
            <option value="default" {{ $allow_resource_comment=='default'?' selected' : '' }}>默认</option>
            <option value="false" {{ $allow_resource_comment=='false'?' selected' : '' }}>禁止评论</option>
            <option value="true" {{ $allow_resource_comment=='true'?' selected' : '' }}>允许评论</option>
        </select>
    </div>
</div>

<div class="form-group">
    <?php $display = isset($page) && $page->configuration ? $page->configuration->config['display'] : 'false'?>
    <div class="form-check">
        <label class="form-check-label">
            <input type="radio" class="form-check-input"
                   {{ (isset($page)) && $display == 'true' ? ' checked ':'' }}
                   name="display"
                   value="true">显示在主页
        </label>
    </div>
    <div class="form-check">
        <label class="form-check-label">
            <input type="radio" class="form-check-input"
                   {{ (!isset($page)) || $display == 'false' ? ' checked ':'' }}
                   name="display"
                   value="false">不显示在主页
        </label>
    </div>
</div>

<div class="form-group">
    <?php $sort_order = isset($page) && $page->configuration ? $page->configuration->config['sort_order'] : '1'?>
    <label for="sort_order" class="form-control-label">顺序</label>
    <input id="sort_order" type="number" class="form-control" name="sort_order"
           value="{{ $sort_order }}">
</div>
