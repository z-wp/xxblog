<div class="order-md-2 mb-4">
    <h5 class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted">分类</span>
        <span class="badge badge-secondary badge-pill">{{ count($categories) }}</span>
    </h5>
    <div class="hot-posts">
        <ul class="list-group">
            @foreach($categories as $category)
                @php
                    $is_current = request()->is('category/'.$category->name);
                @endphp
                <a class="{{ $is_current?'bg-light text-success disabled':'' }} list-group-item d-flex justify-content-between list-group-item-action" href="{{ route('category.show',$category->name) }}" title="{{ $category->name }}">
                    <div>
                        <h6 class="my-0">{{ $category->name }}</h6>
                    </div>
                    <span class="text-{{ $is_current?'success':'muted' }}">{{ $category->posts_count }}</span>
                </a>
            @endforeach
        </ul>
    </div>
</div>