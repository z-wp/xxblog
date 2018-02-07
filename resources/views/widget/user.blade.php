<div class="card card-user mb-4" style="overflow: hidden">
    <?php
    if (isset($profile_image) && $profile_image)
        $style = "background: url($profile_image) no-repeat center center;background-size: cover";
    else
        $style = '';
    ?>
    <div class="card-user-header bg-placeholder" style="{{ $style }}">
        <h3 class="card-user-username">{{ $author or '' }}</h3>
        <h5 class="card-user-desc">{{ $description or '' }}</h5>
    </div>
    <div class="card-user-image">
        <img class="rounded-circle"
             src="{{ $avatar or 'https://raw.githubusercontent.com/lufficc/images/master/Xblog/logo.png' }}"
             alt="User Avatar">
    </div>
    <div class="card-user-footer">
        <div class="row">
            <?php $count = count(config('social'))?>
            @foreach(config('social') as $key => $value)
                <div class="col center-block">
                    <div class="description-block">
                        <a href="{{ $value['url'] }}" title="{{ ucfirst($key) }}" class="description-header text-muted"><i class="{{ 'fa fa-'.$value['icon'].' fa-lg' }}"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>