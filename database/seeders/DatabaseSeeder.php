<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\Counter;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Settings
        $settings = [
            'header_title' => 'POSKO PPDB / SPMB',
            'header_subtitle' => 'SUDIN PENDIDIKAN WILAYAH 1 JAKARTA PUSAT',
            'header_address' => 'Kantor Walikota Jakarta Pusat Blok C Lt. 4, Jl. Tanah Abang I No. 1, Jakarta Pusat',
            'marquee_text' => 'Selamat datang di Posko PPDB/SPMB Suku Dinas Pendidikan Wilayah 1 Jakarta Pusat. Silakan ambil nomor antrean dan tunggu dipanggil.',
            'media_type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/JvXj6gV4Z6A',
            'slideshow_images' => json_encode([
                'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800',
                'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800',
                'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800'
            ]),
            'speech_rate' => '1.0',
            'speech_pitch' => '1.0',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Default Counters (Lokets)
        $counters = [
            ['name' => 'LOKET 1', 'room' => 'LOKET 1', 'sort_order' => 1],
            ['name' => 'LOKET 2', 'room' => 'LOKET 2', 'sort_order' => 2],
            ['name' => 'LOKET 3', 'room' => 'LOKET 3', 'sort_order' => 3],
            ['name' => 'LOKET 4', 'room' => 'LOKET 4', 'sort_order' => 4],
        ];

        foreach ($counters as $c) {
            Counter::updateOrCreate(['name' => $c['name']], [
                'room' => $c['room'],
                'sort_order' => $c['sort_order'],
                'current_call_number' => null
            ]);
        }
    }
}
