<?php

namespace App\Services;

class MangHarisTips
{
    private static array $contextualTips = [
        'dashboard' => 'Laporkan bug atau kendala via tombol laporan di kanan bawah ya biar cepat ditangani!',
        'konsumens' => 'Ada konsumen aktif nih. Cek yang data-nya belum lengkap!',
        'bi-checkings' => 'BI Checking butuh 3-7 hari. Pantau lead time biar gak kedodoran!',
        'psjbs' => 'Jangan lupa isi cara pembayaran: FLPP, Cash, atau Cash Bertahap.',
        'pemberkasans' => 'Tipe pemberkasan: Registrasi, Banding, PIP, Revisi, atau Lengkap.',
        'proses-banks' => 'Proses bank 14-30 hari. Kalau reject, konsumen otomatis batal.',
        'ppjb-dev' => 'Tahap PPJB Dev! Pastikan semua dokumen terupload.',
        'akads' => 'Akad adalah tahap penting! Cek kelengkapan dokumen konsumen.',
        'basts' => 'BAST — serah terima! Selamat, konsumen segera huni kavlingnya!',
        'daily-lead' => 'Lead baru perlu segera ditindaklanjuti. Isi laporan harian!',
        'campaign' => 'Pantau terus campaign marketing biar leads maksimal!',
        'monitoring-jalans' => 'Monitoring diisi tiap hari Senin, per cabang!',
        'expenses' => 'Catat pengeluaran detail biar laporan keuangan rapi.',
        'dana-talangans' => 'Pastikan nominal dan tenor sesuai kesepakatan konsumen.',
        'kavlings' => 'Cek status kavling sebelum assign ke konsumen baru!',
        'sales' => 'Data sales perlu diupdate berkala biar akurat.',
        'lead-times' => 'Lead time yang realistis bikin target makin mudah dicapai!',
    ];

    public static function contextual(string $route): ?string
    {
        foreach (self::$contextualTips as $key => $tip) {
            if (str_contains($route, $key)) {
                return $tip;
            }
        }

        return null;
    }

    public static function randomContextual(): string
    {
        return self::$contextualTips[array_rand(self::$contextualTips)];
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
