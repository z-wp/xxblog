<?php
$blocked= is_object($ip) && $ip->blocked;
$ip_str = is_object($ip) ? $ip->id : $ip;
?>
<button class="btn swal-dialog-target {{ $blocked?' btn-danger':' btn-default' }}"
        data-dialog-msg="{{ $blocked?'取消阻塞':'阻塞' }} IP {{ $ip_str }} ? {{ $blocked?'':'阻塞后此IP将不能访问你的网站' }}"
        data-url="{{ route('ip.block', $ip_str) }}"
        data-dialog-title="{{ $blocked?'取消阻塞':'阻塞' }}"
        data-toggle="tooltip"
        data-dialog-type="{{ $blocked?'success':'danger' }}"
        title="{{ $blocked ? 'Un Block':'Block' }}">
    <i class="fa {{ $blocked?'fa-check':'fa-close' }} fa-fw"></i>
</button>