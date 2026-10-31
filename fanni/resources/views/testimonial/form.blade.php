@php
    $company = $s['company_name'] ?? config('app.name', 'Website');
    $logo = !empty($s['company_logo']) ? \Illuminate\Support\Facades\Storage::url($s['company_logo']) : null;
    $waLink = !empty($s['wa_number']) ? 'https://wa.me/' . $s['wa_number'] : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Testimoni | {{ $company }}</title>
    @if(!empty($s['favicon']))<link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::url($s['favicon']) }}">@endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/testimonial-form.css') }}?v={{ filemtime(public_path('css/testimonial-form.css')) }}">
</head>
<body>
    <div class="bar">
        @if($logo)<img src="{{ $logo }}" alt="{{ $company }}">@else<b>{{ $company }}</b>@endif
    </div>

    <main>
        <div class="card">
        @if($state === 'form')
            <h1>Bagikan pengalaman Anda</h1>
            <p class="lead">Terima kasih telah bekerja sama dengan {{ $company }}. Ceritakan pengalaman Anda singkat saja. Testimoni akan kami tinjau dulu sebelum ditampilkan di website.</p>

            <form method="POST" action="{{ route('testimonial.store', $invite->token) }}" id="tForm">
                @csrf
                <div class="hp" aria-hidden="true"><label>Jangan diisi <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                <div class="field">
                    <label for="author">Nama Anda</label>
                    <input type="text" id="author" name="author" value="{{ old('author') }}" maxlength="100" required autocomplete="name" placeholder="Contoh: Budi Santoso">
                    @error('author')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="pos">Jabatan & Perusahaan</label>
                    <input type="text" id="pos" name="pos" value="{{ old('pos') }}" maxlength="100" required autocomplete="organization-title" placeholder="Contoh: Purchasing, PT Maju Jaya">
                    @error('pos')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="text">Testimoni</label>
                    <textarea id="text" name="text" maxlength="1000" required placeholder="Ceritakan kualitas produk, layanan, atau pengalaman bekerja sama dengan kami...">{{ old('text') }}</textarea>
                    <div class="hint"><span>Minimal 20 karakter</span><span id="count">0 / 1000</span></div>
                    @error('text')<p class="err">{{ $message }}</p>@enderror
                </div>

                <label class="consent">
                    <input type="checkbox" name="consent" value="1" @checked(old('consent')) required>
                    <span>Saya setuju nama, jabatan, dan testimoni ini ditampilkan di website {{ $company }}.</span>
                </label>
                @error('consent')<p class="err" style="margin:-12px 0 16px;">{{ $message }}</p>@enderror

                <button type="submit" class="btn" id="tBtn">Kirim Testimoni</button>
            </form>

        @elseif($state === 'thanks')
            <div class="state">
                <div class="ico ok"><i class="fa-solid fa-check"></i></div>
                <h1>Terima kasih!</h1>
                <p>Testimoni Anda sudah kami terima dan akan kami tinjau sebelum ditampilkan. Kami sangat menghargai waktu Anda.</p>
                <a href="{{ url('/') }}" class="btn">Kunjungi Website</a>
            </div>

        @else
            @php
                $msg = match ($state) {
                    'used' => ['Link sudah dipakai', 'Link ini sudah digunakan untuk mengirim testimoni dan tidak bisa dipakai lagi. Terima kasih atas masukan Anda.'],
                    'expired' => ['Link sudah kedaluwarsa', 'Masa berlaku link ini sudah habis. Silakan minta link baru kepada kami.'],
                    default => ['Link tidak valid', 'Link tidak ditemukan atau sudah dicabut. Periksa kembali link yang Anda terima, atau minta link baru kepada kami.'],
                };
            @endphp
            <div class="state">
                <div class="ico warn"><i class="fa-solid fa-link-slash"></i></div>
                <h1>{{ $msg[0] }}</h1>
                <p>{{ $msg[1] }}</p>
                @if($waLink)<a href="{{ $waLink }}" class="btn" target="_blank" rel="noopener">Hubungi Kami</a>@else<a href="{{ url('/') }}" class="btn">Ke Beranda</a>@endif
            </div>
        @endif
        </div>
    </main>

    <footer>{{ $s['footer_text'] ?? '© ' . date('Y') . ' ' . $company }}</footer>

    @if($state === 'form')
    <script>
        var ta = document.getElementById('text'), cnt = document.getElementById('count');
        function upd() { cnt.textContent = ta.value.length + ' / 1000'; }
        ta.addEventListener('input', upd); upd();
        document.getElementById('tForm').addEventListener('submit', function () {
            var b = document.getElementById('tBtn'); b.disabled = true; b.textContent = 'Mengirim...';
        });
    </script>
    @endif
</body>
</html>
