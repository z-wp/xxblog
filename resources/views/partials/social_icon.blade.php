<?php $social_link = "social_" . $name ?>
@if(isset($$social_link) && $$social_link)
    <?php
    if ($name == "googleplus") {
        $icon_name = 'google-plus-square';
    } elseif ($name == "stackoverflow") {
        $icon_name = 'stack-overflow';
    } elseif ($name == "linkedin") {
        $icon_name = 'linkedin-square';
    } else {
        $icon_name = $name;
    }
    ?>
    @if($name == 'googleplus')
        <a href="{{ $$social_link }}" target="_blank" title="{{ ucfirst($name) }}" class="description-header text-muted"><i class="{{ "fa fa-$icon_name fa-lg fa-fw" }}"></i></a>
    @else
        <a href="{{ $$social_link }}" target="_blank" title="{{ ucfirst($name) }}" class="description-header text-muted"><i class="{{ "fa fa-$icon_name fa-lg fa-fw" }}"></i></a>
    @endif
@endif