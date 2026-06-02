<x-app-layout>
    <x-slot name="header">
        Schedule
    </x-slot>

   @php

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $hours = [];

        for($i = 7; $i <= 20; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        $schedules = auth()->user()
            ->schedules()
            ->with('room')
            ->get();

    @endphp

    <style>

        .schedule-wrapper {
            overflow-x: auto;
        }

        .schedule-grid {
            position: relative;
            display: grid;
            grid-template-columns: 100px repeat(5, 1fr);
            min-width: 1200px;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .header-cell {
            padding: 20px;
            font-weight: bold;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            font-size: 16px;
        }

        .time-cell {
            height: 60px;
            padding-top: 18px;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
        }

        .day-column {
            position: relative;
            border-left: 1px solid #f1f5f9;
        }

        .hour-line {
            height: 60px;
            border-bottom: 1px solid #f1f5f9;
        }

        .event-block {
            position: absolute;
            left: 8px;
            right: 8px;
            background: #0f172a;
            color: white;
            border-radius: 18px;
            padding: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);

            transition: 0.2s;
        }

        .event-block:hover {
            transform: scale(1.02);
        }

        .event-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .event-room {
            font-size: 13px;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .event-time {
            font-size: 12px;
            opacity: 0.75;
        }

    </style>

    <div class="schedule-wrapper">

        <div class="schedule-grid">

            <!-- EMPTY -->
            <div class="header-cell"></div>

            <!-- DAYS -->
            @foreach($days as $day)

                <div class="header-cell">
                    {{ $day }}
                </div>

            @endforeach

            <!-- HOURS -->
            <div>

                @foreach($hours as $hour)

                    <div class="time-cell">
                        {{ $hour }}
                    </div>

                @endforeach

            </div>

            <!-- DAY COLUMNS -->
            @foreach($days as $dayIndex => $day)

                <div class="day-column">

                    @foreach($hours as $hour)
                        <div class="hour-line"></div>
                    @endforeach

                    @foreach($schedules as $item)

                        @if(array_search($item->day_of_week, $days) === $dayIndex)

                            <div
                                class="event-block"
                                data-top="{{ $item->top }}"
                                data-height="{{ $item->height }}"
                            >

                                <div class="event-title">
                                    {{ $item->subject }}
                                </div>

                                <div class="event-room">
                                    Room {{ $item->room->number }}
                                </div>

                                <div class="event-time">
                                    {{ substr($item->start_time,0,5) }}
                                    -
                                    {{ substr($item->end_time,0,5) }}
                                </div>

                            </div>

                        @endif

                    @endforeach

                </div>

            @endforeach

        </div>

    </div>

    <script>

        document.querySelectorAll(".event-block").forEach(block => {

            block.style.top =
                block.dataset.top + "px"

            block.style.height =
                block.dataset.height + "px"

        })

    </script>

</x-app-layout>