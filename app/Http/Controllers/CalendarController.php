<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Calendar;
use Carbon\Carbon;
use App\Models\Event;

class CalendarController extends Controller
{
    /**
     * Show the calendar main page
     *
     * @return Illuminate\View\View
     */
    public function index($year = null, $month = null, $day = null)
    {
        $date = Carbon::now();

        if (!is_null($year) && !is_null($month) && !is_null($day))
        {
            $date = Carbon::createFromDate("$year-$month-$day");
        }

        $calendar = new Calendar();

        $params = $calendar->getCalendarMonth($date);

        return view('calendar.index', $params);
    }
}
