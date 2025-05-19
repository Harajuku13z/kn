@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'KLINKLIN')
<img src="{{ asset(config('mail.logo', 'images/logo.png')) }}" class="logo" alt="KLINKLIN Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
