@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Birthday Hub',
    'subtitle' => 'Celebrate your shop owners',
    'showBack' => true,
    'backUrl' => route('admin.dashboard'),
    'role' => 'Admin Access',
    'showBottomNav' => true
];
@endphp

<style>
    .festive-bg {
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        min-height: 100vh;
    }
    
    .birthday-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }
    
    .birthday-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #fca5a5;
    }

    .today-card {
        background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        border: 2px solid #fecaca;
    }

    .today-card::before {
        content: "🎂";
        position: absolute;
        top: -10px;
        right: 10px;
        font-size: 40px;
        opacity: 0.1;
        transform: rotate(15deg);
    }
    
    .confetti-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 99;
    }

    .avatar-ring {
        position: relative;
        padding: 4px;
        border-radius: 50%;
        background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
    }

    .avatar-img {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 24px;
        color: #f5576c;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pill-today {
        background: #fee2e2;
        color: #ef4444;
    }

    .pill-upcoming {
        background: #f1f5f9;
        color: #64748b;
    }

    .festive-button {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .festive-button:hover {
        box-shadow: 0 10px 15px -3px rgba(245, 87, 108, 0.4);
        transform: scale(1.02);
    }
</style>

<div class="festive-bg pb-24">
    <div id="confetti" class="confetti-container"></div>

    <main class="max-w-4xl mx-auto p-6 space-y-8">
        
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight mb-2">Birthday Hub 🎊</h2>
                    <p class="text-slate-400">Celebrate the people who make your business thrive.</p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 text-center border border-white/20">
                        <span class="block text-2xl font-bold">{{ $todayBirthdays->count() }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Today</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 text-center border border-white/20">
                        <span class="block text-2xl font-bold">{{ $upcomingBirthdays->count() }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Upcoming</span>
                    </div>
                </div>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-rose-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Today's Section -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                    <iconify-icon icon="lucide:star" class="text-amber-400"></iconify-icon>
                    Celebrating Today
                </h3>
            </div>

            @forelse($todayBirthdays as $user)
            <div class="birthday-card today-card animate__animated animate__fadeInUp">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="avatar-ring">
                        <div class="avatar-img">
                            {{ substr($user->shop ? $user->shop->name : $user->name, 0, 1) }}
                        </div>
                    </div>
                    
                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1">
                            <h4 class="text-xl font-bold text-slate-900">{{ $user->shop ? $user->shop->name : $user->name }}</h4>
                            <span class="status-pill pill-today">HAPPY BIRTHDAY!</span>
                        </div>
                        <p class="text-slate-500 text-sm mb-3">
                            <iconify-icon icon="lucide:user" width="12"></iconify-icon> {{ $user->name }} • 
                            <iconify-icon icon="lucide:calendar" width="12"></iconify-icon> {{ $user->dob->format('d M, Y') }}
                        </p>
                        
                        <div class="flex flex-wrap justify-center sm:justify-start gap-4">
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-600">
                                <iconify-icon icon="lucide:phone" class="text-slate-400"></iconify-icon>
                                {{ $user->phone }}
                            </div>
                            @if($user->shop && $user->shop->bit)
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-600">
                                <iconify-icon icon="lucide:map-pin" class="text-slate-400"></iconify-icon>
                                {{ $user->shop->bit->name }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="tel:{{ $user->phone }}" class="festive-button flex-1 sm:flex-none">
                            <iconify-icon icon="lucide:phone-call"></iconify-icon>
                            Call Now
                        </a>
                        <a href="https://wa.me/{{ $user->phone }}?text=Happy%20Birthday%20from%20Vamika%20Enterprise!" target="_blank" class="bg-emerald-500 text-white p-3 rounded-xl hover:bg-emerald-600 transition-all">
                            <iconify-icon icon="ic:baseline-whatsapp" width="20"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-200">
                <iconify-icon icon="lucide:smile" width="48" class="text-slate-200 mb-4"></iconify-icon>
                <p class="text-slate-400 font-medium italic">No birthdays today. Rest up for the next celebration!</p>
            </div>
            @endforelse
        </section>

        <!-- Upcoming Section -->
        <section class="space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400">Upcoming Birthdays</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($upcomingBirthdays as $user)
                <div class="birthday-card hover:border-blue-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                            {{ substr($user->shop ? $user->shop->name : $user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="text-sm font-bold text-slate-900 truncate">{{ $user->shop ? $user->shop->name : $user->name }}</h5>
                            <p class="text-xs text-slate-500">{{ $user->dob->format('d M') }} • {{ $user->days_until }} days away</p>
                        </div>
                        <span class="status-pill pill-upcoming">Coming Up</span>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-8 text-slate-400 text-sm">
                    No birthdays in the next 7 days.
                </div>
                @endforelse
            </div>
        </section>

    </main>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($todayBirthdays->count() > 0)
            // Trigger confetti if there are birthdays today
            const duration = 3 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) {
              return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
              const timeLeft = animationEnd - Date.now();

              if (timeLeft <= 0) {
                return clearInterval(interval);
              }

              const particleCount = 50 * (timeLeft / duration);
              confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
              confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
        @endif
    });
</script>
@endsection
