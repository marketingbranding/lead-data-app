<?php

namespace App\Services;

class MangHarisTips
{
    public static function contextual(string $route): ?string
    {
        return match (true) {
            str_contains($route, 'dashboard') => 'Laporkan bug atau kendala via tombol laporan di kanan bawah ya biar cepat ditangani!',

            str_contains($route, 'konsumens') => 'Ada konsumen aktif nih. Cek yang data-nya belum lengkap!',
            str_contains($route, 'bi-checkings') => 'BI Checking butuh 3-7 hari. Pantau lead time biar gak kedodoran!',
            str_contains($route, 'psjbs') => 'Jangan lupa isi cara pembayaran: FLPP, Cash, atau Cash Bertahap.',
            str_contains($route, 'pemberkasans') => 'Tipe pemberkasan: Registrasi, Banding, PIP, Revisi, atau Lengkap.',
            str_contains($route, 'proses-banks') => 'Proses bank 14-30 hari. Kalau reject, konsumen otomatis batal.',
            str_contains($route, 'ppjb-dev') => 'Tahap PPJB Dev! Pastikan semua dokumen terupload.',
            str_contains($route, 'akads') => 'Akad adalah tahap penting! Cek kelengkapan dokumen konsumen.',
            str_contains($route, 'basts') => 'BAST — serah terima! Selamat, konsumen segera huni kavlingnya!',

            str_contains($route, 'daily-lead') => 'Lead baru perlu segera ditindaklanjuti. Isi laporan harian!',
            str_contains($route, 'campaign') => 'Pantau terus campaign marketing biar leads maksimal!',
            str_contains($route, 'monitoring-jalans') => 'Monitoring diisi tiap hari Senin, per cabang!',

            str_contains($route, 'expenses') => 'Catat pengeluaran detail biar laporan keuangan rapi.',
            str_contains($route, 'dana-talangans') => 'Pastikan nominal dan tenor sesuai kesepakatan konsumen.',

            str_contains($route, 'kavlings') => 'Cek status kavling sebelum assign ke konsumen baru!',
            str_contains($route, 'sales') => 'Data sales perlu diupdate berkala biar akurat.',
            str_contains($route, 'lead-times') => 'Lead time yang realistis bikin target makin mudah dicapai!',

            default => null,
        };
    }

    public static function randomNyeleneh(): string
    {
        $tips = [
            'Mang Haris ngintip... Eh, lagi serius banget yak! 👍',
            'Jangan lupa minum, nanti dehidrasi pas ngurus berkas! 💧',
            'Psst... Mang Haris denger kamu jagoan ngurus pipeline!',
            'Tahukah kamu? Mang Haris juga suka kopi. Tapi lebih suka lihat kamu rapiin data.',
            'Warning: Terlalu lama di depan komputer. Istirahat bentar, yuk!',
            'Mang Haris mau cerita... Eh lupa! Lain kali aja 😅',
            'Udah berapa konsumen yang kamu proses hari ini? Keren! 🔥',
            'Fun fact: rata-rata Akad selesai 14 hari. Kamu bisa lebih cepat!',
            'Coba tebak Mang Haris lagi ngapain? Jawab: ngawasin kamu kerja. Bangga! 🫡',
            'Ada masalah teknis? Langsung lapor via tombol bug report ya!',
            'Mang Haris salting lihat kamu semangat banget hari ini 😄',
            'Jangan lupa senyum, ada Mang Haris yang selalu dukung kamu!',
            'Pro tip: kopi enak bikin entry data makin semangat ☕',
            'Mang Haris lagi diet... Pantang lihat data kosong! Isi dong! 😤',
            'Kamu tahu? Mang Haris bangga punya tim kayak kamu!',
            'Sedang mikir? Mang Haris juga. Tapi kamu pasti bisa selesaiin! 💪',
            'Hari ini cuaca panas? Tenang, Mang Haris support dari sini! 🥵',
            'Jangan lupa solat ya, rezeki berkah berkah! 🕌',
        ];

        return $tips[array_rand($tips)];
    }
}
