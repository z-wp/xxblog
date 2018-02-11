<?php $social = "social_" . $name ?>
@if(isset($$social) && $$social)
    <a href="{{ $$social }}" target="_blank" title="{{ ucfirst($name) }}" class="description-header text-muted"><i class="{{ "fa fa-$name fa-lg fa-fw" }}"></i></a>
@endif