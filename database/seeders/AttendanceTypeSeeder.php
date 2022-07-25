<?php

namespace Database\Seeders;

use App\Models\AttendanceType;
use Illuminate\Database\Seeder;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attRecord1 = AttendanceType::create([
            'name' => 'Present',
        ]);
        $attRecord2 = AttendanceType::create([
            'name' => 'Late',
        ]);
        $attRecord3 = AttendanceType::create([
            'name' => 'Absent',
        ]);
    }
}
