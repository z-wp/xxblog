<div class="row" id="images" data-masonry='{ "itemSelector": ".col", "columnWidth":".col" }'>
    @forelse($images as $image)
        <div class="col col-md-3 col-sm-4 col-6" style="margin-bottom: .4rem">
            <div class="card img-container">
                <img class="card-img-top" src="{{ getImageViewUrl($image->url,null,250) }}">
                <div class="img-overlay">
                    <div class="actions align-self-center text-center">
                        <span class="text-success d-block">{{ $image->name }}</span>
                        <span class="text-success d-block">{{ formatBytes($image->size) }}</span>
                        <span class="text-success d-block">{{ $image->created_at->format('Y-m-d') }}</span>
                        <a class="text-white d-block" href="{{ $image->url }}">原图</a>
                        <a  href="javascript:void(0)" class="text-white btn-clipboard"
                                data-clipboard-text="{{ $image->url }}"
                                data-placement="left"
                                title="Copied">
                            复制
                        </a>
                        <a class="text-danger d-block swal-dialog-target" href="javascript:void(0)"
                           data-dialog-msg="确定删除{{ $image->name }}？"
                           data-url="{{ route('delete.file').'?key='.$image->key.'&type=image' }}"
                           data-key="{{ $image->key }}">删除</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <h4 class="text-secondary mt-3">没有图片</h4>
    @endforelse
</div>
@if($images->lastPage() > 1)
    <div class="row">
        <div class="col-md-12">
            {{ $images->links() }}
        </div>
    </div>
@endif