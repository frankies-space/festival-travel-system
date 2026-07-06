<?php

namespace Database\Seeders;

use App\Models\Festival;
use Illuminate\Database\Seeder;

class FestivalSeeder extends Seeder
{
    public function run(): void
    {
        $festivals = [
            [
                'name' => 'Tomorrowland',
                'location' => 'Boom, België',
                'start_date' => '2026-07-18',
                'end_date' => '2026-07-20',
                'description' => 'Het grootste dance-festival van Europa met wereldberoemde DJ\'s.',
                'max_capacity' => 50,
                'ticket_price' => 199.00,
            ],
            [
                'name' => 'Lowlands',
                'location' => 'Biddinghuizen, Nederland',
                'start_date' => '2026-08-14',
                'end_date' => '2026-08-16',
                'description' => 'Driedaags festival met muziek, theater, film en literatuur.',
                'max_capacity' => 50,
                'ticket_price' => 169.00,
            ],
            [
                'name' => 'Rock am Ring',
                'location' => 'Nürburg, Duitsland',
                'start_date' => '2026-06-05',
                'end_date' => '2026-06-07',
                'description' => 'Legendarisch rock- en metal-festival in de Eifel.',
                'max_capacity' => 50,
                'ticket_price' => 149.00,
            ],
        ];

        foreach ($festivals as $festival) {
            Festival::create($festival);
        }
    }
}
