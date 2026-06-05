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
            'Tahukah kamu? Di Venus, satu hari lebih panjang dari satu tahun. Venus butuh 243 hari buat mutar poros, tapi cuma 225 hari buat ngelilingin Matahari!',
            'Tahukah kamu? Mang Haris juga suka kopi. Tapi lebih suka lihat kamu rapiin data.',
            'Warning: Terlalu lama di depan komputer. Istirahat bentar, yuk!',
            'Fakta: Palung Mariana sedalam ~11 km. Kalau Gunung Everest ditaruh di dasarnya, puncaknya masih tenggelam 2 km di bawah permukaan laut!',
            'Udah berapa konsumen yang kamu proses hari ini? Keren! 🔥',
            'Fun fact: rata-rata Akad selesai 14 hari. Kamu bisa lebih cepat!',
            'Coba tebak Mang Haris lagi ngapain? Jawab: ngawasin kamu kerja. Bangga! 🫡',
            'Ada masalah teknis? Langsung lapor via tombol bug report ya!',
            'Mang Haris salting lihat kamu semangat banget hari ini 😄',
            'Jangan lupa senyum, ada Mang Haris yang selalu dukung kamu!',
            'Pro tip: kopi enak bikin entry data makin semangat ☕',
            'Mang Haris lagi diet... Pantang lihat data kosong! Isi dong! 😤',
            'Kamu tahu? Mang Haris bangga punya tim kayak kamu!',
            'Tip admin: tekan Ctrl+Shift+V buat paste tanpa format. Percaya deh, bakal nyelametin kamu dari formatting kacau!',
            'Fakta: Di Jupiter dan Saturnus, hujannya bukan air, tapi berlian! Tekanan atmosfernya mengubah karbon jadi kristal berlian.',
            'Tahu gak? Voyager 1 membawa Golden Record berisi suara deburan ombak, sapaan 55 bahasa, dan lagu gamelan Jawa. Mungkin alien dengerin gamelan sekarang! 😄',
            'Kenapa ayam kalo berkokok matanya merem? Soalnya udah hafal liriknya! 🐔',
            'Burung, burung apa yang suka nolak? Burung gakgak.',
            'Kenapa komputer kedinginan? Soalnya windows-nya kebuka! 🪟',
            'Gula, gula apa yang bukan gula? Gula aren\'t',
        ];

        return $tips[array_rand($tips)];
    }
}
