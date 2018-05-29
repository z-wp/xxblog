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
            @foreach(['facebook','twitter','github','weibo','instagram','googleplus','tumblr','stackoverflow',
            'dribbble','linkedin','gitlab','pinterest','youtube'] as $social)
                <?php $social_link = "social_" . $social ?>
                @if(isset($$social_link) && $$social_link)
                    <div class="col">
                        <div class="description-block">
                            @include('partials.social_icon', ['name' => $social])
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    @if(isset($other_information) && $other_information)
        {!! $other_information !!}
    @endif
</div>