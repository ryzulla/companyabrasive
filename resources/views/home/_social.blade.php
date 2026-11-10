@php
    $socialMap = [
        'social_facebook'  => ['fa-brands fa-facebook-f', 'Facebook'],
        'social_instagram' => ['fa-brands fa-instagram', 'Instagram'],
        'social_linkedin'  => ['fa-brands fa-linkedin-in', 'LinkedIn'],
        'social_youtube'   => ['fa-brands fa-youtube', 'YouTube'],
        'social_tiktok'    => ['fa-brands fa-tiktok', 'TikTok'],
        'social_twitter'   => ['fa-brands fa-x-twitter', 'X (Twitter)'],
    ];
    $socialLinks = collect($socialMap)->filter(fn($m, $k) => !empty($s[$k]));
@endphp
@if($socialLinks->isNotEmpty())
<div class="social-links" style="display:flex; justify-content:center; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
    @foreach($socialLinks as $key => [$icon, $label])
        <a href="{{ $s[$key] }}" target="_blank" rel="noopener noreferrer me" aria-label="{{ $label }}" title="{{ $label }}"
           style="width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.08); color:#cbd5e1; display:inline-flex; align-items:center; justify-content:center; font-size:0.9rem; transition:all .2s;"
           onmouseover="this.style.background='#ea580c';this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#cbd5e1'">
            <i class="{{ $icon }}"></i>
        </a>
    @endforeach
</div>
@endif
