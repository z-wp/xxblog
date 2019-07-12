<?php
$blocked= is_object($ip) && $ip->blocked;
$ip_str = is_object($ip) ? $ip->id : $ip;
?>
<button class="btn swal-dialog-target {{ $blocked?' btn-danger':' btn-default' }}"
        data-dialog-msg="{{ $blocked?__('web.UNBLOCK'):__('web.BLOCK') }} IP {{ $ip_str }} ? {{ $blocked?'': __('web.BLOCK_DESCRIPTION') }}"
        data-url="{{ route('ip.block', $ip_str) }}"
        data-dialog-title="{{ $blocked?__('web.UNBLOCK'):__('web.BLOCK') }}"
        data-toggle="tooltip"
        data-dialog-type="{{ $blocked?'success':'danger' }}"
        title="{{ $blocked ? 'Un Block':'Block' }}">
    <i class="fa {{ $blocked?'fa-check':'fa-close' }} fa-fw"></i>
</button>