<div class="card mb-3">
    <div class="card-header bg-white"><i class="fa fa-tags fa-fw"></i><a class="text-dark" href="{{ route('tag.index') }}">标签</a></div>
    <div class="card-body">
        <ul class="tags">
            @forelse($tags as $tag)
                @if(str_contains(urldecode(request()->getPathInfo()),'tag/'.$tag->name))
                    <li>
                        <span class="tag active" title="{{ $tag->name }}">
                        {{ $tag->name }}
                            <span class="font-weight-bold">{{ $tag->posts_count }}</span>
                    </span>
                    </li>
                @else
                    <li>
                        <a title="{{ $tag->name }}" href="{{ route('tag.show',$tag->name) }}" class="tag">
                            {{ $tag->name }}
                            <span class="font-weight-bold">{{ $tag->posts_count }}</span>
                        </a>
                    </li>
                @endif
            @empty <p class="meta-item center-block">No tags.</p>
            @endforelse
        </ul>
    </div>
</div>