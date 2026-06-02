<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Reservation;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@roomflow.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $teachers = [

            'John Smith',
            'Michael Johnson',
            'David Brown',
            'James Wilson',
            'Robert Taylor',

            'Emily Davis',
            'Sarah Miller',
            'Jessica Anderson',
            'Olivia Thomas',
            'Sophia White',

            'Daniel Harris',
            'William Martin',
            'Matthew Clark',
            'Andrew Lewis',
            'Christopher Walker',

        ];

        foreach ($teachers as $teacher) {

            User::create([
                'name' => $teacher,
                'email' => strtolower(str_replace(' ', '.', $teacher)) . '@university.edu',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]);
        }

        $rooms = [

            [
                'number' => 'A101',
                'capacity' => 30,
                'equipment' => 'Projector',
                'type' => 'Lecture',
                'workstations' => 0,
            ],

            [
                'number' => 'A102',
                'capacity' => 40,
                'equipment' => 'Projector',
                'type' => 'Lecture',
                'workstations' => 0,
            ],

            [
                'number' => 'A103',
                'capacity' => 25,
                'equipment' => 'Projector',
                'type' => 'Lecture',
                'workstations' => 0,
            ],

            [
                'number' => 'B201',
                'capacity' => 35,
                'equipment' => 'Smart Board',
                'type' => 'Lecture',
                'workstations' => 0,
            ],

            [
                'number' => 'B202',
                'capacity' => 50,
                'equipment' => 'Projector',
                'type' => 'Lecture',
                'workstations' => 0,
            ],

            [
                'number' => 'C301',
                'capacity' => 24,
                'equipment' => 'Whiteboard',
                'type' => 'Exercise',
                'workstations' => 0,
            ],

            [
                'number' => 'C302',
                'capacity' => 20,
                'equipment' => 'Projector',
                'type' => 'Exercise',
                'workstations' => 0,
            ],

            [
                'number' => 'LAB1',
                'capacity' => 20,
                'equipment' => 'PCs',
                'type' => 'Laboratory',
                'workstations' => 20,
            ],

            [
                'number' => 'LAB2',
                'capacity' => 24,
                'equipment' => 'PCs',
                'type' => 'Laboratory',
                'workstations' => 24,
            ],

            [
                'number' => 'LAB3',
                'capacity' => 18,
                'equipment' => 'PCs',
                'type' => 'Laboratory',
                'workstations' => 18,
            ],

        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $teachers = User::where('role', 'teacher')->get();

        $roomIds = Room::pluck('id')->toArray();

        $subjects = [

            'Mathematics',
            'Linear Algebra',
            'Statistics',
            'Physics',
            'Mechanics',
            'Programming',
            'Database Systems',
            'Computer Networks',
            'Operating Systems',
            'Web Development',
            'Software Engineering',
            'Artificial Intelligence',
            'Cybersecurity',
            'Project Management',
            'Electronics',

        ];

        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
        ];

        $timeSlots = [

            ['07:00', '08:30'],
            ['08:45', '10:15'],
            ['10:30', '12:00'],
            ['12:15', '13:45'],
            ['14:00', '15:30'],
            ['15:45', '17:15'],

        ];

        foreach ($teachers as $index => $teacher) {

            $room1 = $roomIds[$index % count($roomIds)];
            $room2 = $roomIds[($index + 2) % count($roomIds)];
            $room3 = $roomIds[($index + 4) % count($roomIds)];

            Schedule::create([
                'user_id' => $teacher->id,
                'room_id' => $room1,
                'subject' => $subjects[$index % count($subjects)],
                'day_of_week' => $days[$index % 5],
                'start_time' => $timeSlots[0][0],
                'end_time' => $timeSlots[0][1],
            ]);

            Schedule::create([
                'user_id' => $teacher->id,
                'room_id' => $room2,
                'subject' => $subjects[($index + 3) % count($subjects)],
                'day_of_week' => $days[($index + 2) % 5],
                'start_time' => $timeSlots[2][0],
                'end_time' => $timeSlots[2][1],
            ]);

            Schedule::create([
                'user_id' => $teacher->id,
                'room_id' => $room3,
                'subject' => $subjects[($index + 6) % count($subjects)],
                'day_of_week' => $days[($index + 4) % 5],
                'start_time' => $timeSlots[4][0],
                'end_time' => $timeSlots[4][1],
            ]);
        }

        $rejectionReasons = [

            'Schedule conflict detected.',
            'Room unavailable during requested period.',
            'Equipment maintenance planned.',
            'Room reserved for examination.',
            'Capacity requirements not met.',

        ];

        foreach ($teachers->take(10) as $index => $teacher) {

            Reservation::create([

                'room_id' => $roomIds[$index % count($roomIds)],
                'user_id' => $teacher->id,

                'date' => now()->subDays(rand(5, 30))->toDateString(),

                'start_time' => '10:00',
                'end_time' => '11:30',

                'status' => 'finished',
                'rejection_reason' => null,
            ]);
        }

        foreach ($teachers->take(5) as $index => $teacher) {

            Reservation::create([

                'room_id' => $roomIds[($index + 3) % count($roomIds)],
                'user_id' => $teacher->id,

                'date' => now()->addDays(rand(1, 10))->toDateString(),

                'start_time' => '12:00',
                'end_time' => '13:30',

                'status' => 'active',
                'rejection_reason' => null,
            ]);
        }

        foreach ($teachers->take(5) as $index => $teacher) {

            Reservation::create([

                'room_id' => $roomIds[($index + 5) % count($roomIds)],
                'user_id' => $teacher->id,

                'date' => now()->subDays(rand(1, 20))->toDateString(),

                'start_time' => '14:00',
                'end_time' => '15:30',

                'status' => 'rejected',

                'rejection_reason' =>
                    $rejectionReasons[array_rand($rejectionReasons)],
            ]);
        }
    }
}
