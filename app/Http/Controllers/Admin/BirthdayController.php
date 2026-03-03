<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BirthdayController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Today's Birthdays
        $todayBirthdays = User::whereMonth('dob', $today->month)
            ->whereDay('dob', $today->day)
            ->with('shop')
            ->get();

        // Upcoming Birthdays (Next 7 days excluding today)
        $upcomingBirthdays = collect();
        for ($i = 1; $i <= 7; $i++) {
            $date = $today->copy()->addDays($i);
            $birthdays = User::whereMonth('dob', $date->month)
                ->whereDay('dob', $date->day)
                ->with('shop')
                ->get();
            
            foreach ($birthdays as $user) {
                $user->days_until = $i;
                $upcomingBirthdays->push($user);
            }
        }

        return view('admin.birthdays.index', compact('todayBirthdays', 'upcomingBirthdays'));
    }
}
