<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Carbon\Carbon;

class CheckWorkingHours
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only apply to salespersons
        if ($user && $user->role === 'salesperson') {
            // Fetch global settings
            $settings = Setting::whereIn('key', ['salesperson_work_start', 'salesperson_work_end'])
                              ->pluck('value', 'key');

            $startTimeStr = $settings['salesperson_work_start'] ?? '09:00';
            $endTimeStr = $settings['salesperson_work_end'] ?? '18:00';

            $start = Carbon::createFromTimeString($startTimeStr, 'Asia/Kolkata');
            $end = Carbon::createFromTimeString($endTimeStr, 'Asia/Kolkata');
            $now = Carbon::now('Asia/Kolkata');

            if (!$now->between($start, $end)) {
                view()->share('is_off_hours', true);
                view()->share('work_hours_string', $startTimeStr . ' - ' . $endTimeStr);

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Action allowed only during working hours (' . $startTimeStr . ' - ' . $endTimeStr . ').',
                        'is_view_only' => true
                    ], 403);
                }
                
                if (!$request->isMethod('GET')) {
                    return redirect()->back()->with('error', 'Action allowed only during working hours (' . $startTimeStr . ' - ' . $endTimeStr . ').');
                }
            } else {
                view()->share('is_off_hours', false);
            }
        }

        return $next($request);
    }
}
