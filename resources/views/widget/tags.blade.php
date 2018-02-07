<div class="order-md-2 mb-4">
    <h5 class="d-flex justify-content-between align-items-center mb-2">
        <a class="text-muted" href="{{ route('tag.index') }}">标签</a>
        <span class="badge badge-secondary badge-pill">{{ count($tags) }}</span>
    </h5>
    <ul class="tags bg-white p-3 shadow rounded">
        @forelse($tags as $tag)
            @if(request()->is('tag/'.$tag->name))
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