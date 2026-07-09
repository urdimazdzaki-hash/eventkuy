@extends('layouts.app')

@section('title', 'Cuaca - EventKuy')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

.weather-scope, .weather-scope * {
    font-family: 'Poppins', sans-serif;
}

@keyframes aurora {
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

@keyframes floating{
    0%,100%{transform:translateY(0px) rotate(0deg);}
    50%{transform:translateY(-14px) rotate(2deg);}
}

@keyframes floatingSlow{
    0%,100%{transform:translateY(0px);}
    50%{transform:translateY(-22px);}
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(34px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes fadeIn{
    from{opacity:0;}
    to{opacity:1;}
}

@keyframes pulseDot{
    0%,100%{opacity:1; box-shadow:0 0 0 0 rgba(74,222,128,.55);}
    50%{opacity:.7; box-shadow:0 0 0 8px rgba(74,222,128,0);}
}

@keyframes shimmerBar{
    0%{background-position:-200% 0;}
    100%{background-position:200% 0;}
}

.glass{
    backdrop-filter:blur(24px) saturate(160%);
    -webkit-backdrop-filter:blur(24px) saturate(160%);
    background:rgba(255,255,255,.6);
    border:1px solid rgba(255,255,255,.4);
    box-shadow:0 8px 32px rgba(15,23,42,.08), inset 0 1px 0 rgba(255,255,255,.5);
}

.dark .glass{
    background:rgba(30,41,59,.55);
    border:1px solid rgba(255,255,255,.12);
    box-shadow:0 20px 50px -12px rgba(0,0,0,.55), inset 0 1px 0 rgba(255,255,255,.06);
}

.glass-deep{
    backdrop-filter:blur(30px) saturate(180%);
    -webkit-backdrop-filter:blur(30px) saturate(180%);
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.22);
}

.dark .glass-deep{
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.08);
}

.animate-up{
    opacity:0;
    animation:fadeUp .8s cubic-bezier(.22,1,.36,1) forwards;
}

.animate-in{
    opacity:0;
    animation:fadeIn 1s ease forwards;
}

.weather-bg{
    background:
        radial-gradient(circle at 15% 20%,#60a5fa 0%,transparent 38%),
        radial-gradient(circle at 85% 15%,#a78bfa 0%,transparent 38%),
        radial-gradient(circle at 75% 85%,#f472b6 0%,transparent 32%),
        linear-gradient(135deg,#0b1120,#1e2a5e,#312e81 60%,#1e1b4b);
    background-size:300% 300%;
    animation:aurora 18s ease infinite;
}

.float{ animation:floating 6s ease-in-out infinite; }
.float-slow{ animation:floatingSlow 9s ease-in-out infinite; }
.float-delay{ animation-delay:1.2s; }
.float-delay-2{ animation-delay:2.4s; }

.hover-card{
    transition:transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s ease;
    will-change:transform;
}

.hover-card:hover{
    transform:translateY(-8px) scale(1.012);
    box-shadow:0 34px 70px -20px rgba(15,23,42,.28);
}

.dark .hover-card:hover{
    box-shadow:0 34px 70px -20px rgba(0,0,0,.6);
}

.tilt-card{
    transform-style:preserve-3d;
    transition:transform .25s ease;
}

.chip{
    backdrop-filter:blur(10px);
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.18);
}

.dark .chip-light{
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.08);
}

.progress-track{
    position:relative;
    overflow:hidden;
    background:rgba(148,163,184,.25) !important;
    border:1px solid rgba(255,255,255,.12);
}

.dark .progress-track{
    background:rgba(148,163,184,.18) !important;
    border:1px solid rgba(255,255,255,.08);
}

.progress-fill{
    position:relative;
    transition:width 1.2s cubic-bezier(.22,1,.36,1);
}

.progress-fill::after{
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.45),transparent);
    background-size:200% 100%;
    animation:shimmerBar 2.4s linear infinite;
}

.live-dot{
    width:8px;
    height:8px;
    border-radius:9999px;
    background:#4ade80;
    animation:pulseDot 2s ease infinite;
}

.no-scrollbar::-webkit-scrollbar{ display:none; }
.no-scrollbar{ -ms-overflow-style:none; scrollbar-width:none; }

.reveal{
    opacity:0;
    transform:translateY(24px);
    transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
}

.reveal.is-visible{
    opacity:1;
    transform:translateY(0);
}

.ring-fade{
    background:conic-gradient(from 0deg,rgba(255,255,255,.5),rgba(255,255,255,0) 70%);
}
</style>

<div class="weather-scope relative overflow-hidden">

    <div class="absolute inset-0 weather-bg opacity-95"></div>

    <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-400 rounded-full blur-3xl opacity-30 float"></div>

    <div class="absolute top-1/3 -right-24 w-[420px] h-[420px] bg-fuchsia-400 rounded-full blur-3xl opacity-20 float-slow float-delay"></div>

    <div class="absolute bottom-0 right-10 w-[450px] h-[450px] bg-purple-500 rounded-full blur-3xl opacity-30 float float-delay-2"></div>

    <div class="absolute bottom-10 left-1/4 w-72 h-72 bg-cyan-400 rounded-full blur-3xl opacity-20 float-slow"></div>

    <div class="relative max-w-7xl mx-auto px-5 sm:px-8 py-10">

        <div class="glass-deep rounded-[40px] p-8 sm:p-12 text-white animate-up relative overflow-hidden">

            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative flex flex-col lg:flex-row justify-between items-center gap-10">

                <div>

                    <div class="flex items-center gap-2 mb-4">
                        <span class="live-dot"></span>
                        <p class="uppercase tracking-[6px] text-white/70 text-xs font-semibold">
                            EventKuy Weather Center
                        </p>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-bold mt-1 tracking-tight leading-[1.05]">
                        Weather Dashboard
                    </h1>

                    <p class="mt-5 text-base sm:text-lg text-white/75 max-w-xl leading-relaxed">
                        Pantau seluruh kondisi cuaca venue event secara real-time
                        lengkap dengan mitigasi hujan, prakiraan, dan analisis risiko.
                    </p>

                </div>

                <div class="mt-4 lg:mt-0 text-center relative">

                    <div class="absolute inset-0 -m-6 rounded-full ring-fade blur-2xl opacity-40"></div>

                    <div class="relative w-56 h-56 mx-auto rounded-full glass-deep flex flex-col items-center justify-center">

                        <i class="ph-duotone ph-cloud-sun text-[92px] text-yellow-300 float"></i>

                        <div class="text-6xl font-bold mt-1 tracking-tight">
                            {{ $eventsWithWeather->count() }}
                        </div>

                        <div class="text-white/70 mt-1 text-xs uppercase tracking-widest">
                            Event Dipantau
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-7 mt-10">

            <div class="glass rounded-[28px] p-7 sm:p-8 hover-card animate-up relative overflow-hidden" style="animation-delay:.05s">

                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-blue-400/20 blur-2xl"></div>

                <div class="relative flex justify-between items-center">

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">
                            Total Event
                        </p>

                        <h2 class="text-4xl sm:text-5xl font-bold mt-3 dark:text-white tracking-tight">
                            {{ $eventsWithWeather->count() }}
                        </h2>

                    </div>

                    <div class="w-16 h-16 sm:w-[70px] sm:h-[70px] rounded-2xl bg-blue-500/20 flex items-center justify-center shrink-0">

                        <i class="ph-fill ph-calendar text-3xl text-blue-500"></i>

                    </div>

                </div>

            </div>

            <div class="glass rounded-[28px] p-7 sm:p-8 hover-card animate-up relative overflow-hidden" style="animation-delay:.12s">

                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-red-400/20 blur-2xl"></div>

                <div class="relative flex justify-between items-center">

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">
                            High Risk
                        </p>

                        <h2 class="text-4xl sm:text-5xl font-bold mt-3 text-red-500 tracking-tight">
                            {{ $eventsWithWeather->filter(fn($i)=>($i['forecast'][0]['rain_probability'] ?? 0) > 70)->count() }}
                        </h2>

                    </div>

                    <div class="w-16 h-16 sm:w-[70px] sm:h-[70px] rounded-2xl bg-red-500/20 flex items-center justify-center shrink-0">

                        <i class="ph-fill ph-warning-circle text-3xl text-red-500"></i>

                    </div>

                </div>

            </div>

            <div class="glass rounded-[28px] p-7 sm:p-8 hover-card animate-up relative overflow-hidden" style="animation-delay:.19s">

                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-green-400/20 blur-2xl"></div>

                <div class="relative flex justify-between items-center">

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">
                            Aman
                        </p>

                        <h2 class="text-4xl sm:text-5xl font-bold mt-3 text-green-500 tracking-tight">
                            {{ $eventsWithWeather->filter(fn($i)=>($i['forecast'][0]['rain_probability'] ?? 0) <= 50)->count() }}
                        </h2>

                    </div>

                    <div class="w-16 h-16 sm:w-[70px] sm:h-[70px] rounded-2xl bg-green-500/20 flex items-center justify-center shrink-0">

                        <i class="ph-fill ph-check-circle text-3xl text-green-500"></i>

                    </div>

                </div>

            </div>

            <div class="glass rounded-[28px] p-7 sm:p-8 hover-card animate-up relative overflow-hidden" style="animation-delay:.26s">

                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-indigo-400/20 blur-2xl"></div>

                <div class="relative flex justify-between items-center">

                    <div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">
                            Hari Ini
                        </p>

                        <h2 class="text-2xl sm:text-3xl font-bold mt-3 dark:text-white tracking-tight">
                            {{ now()->translatedFormat('d M Y') }}
                        </h2>

                    </div>

                    <div class="w-16 h-16 sm:w-[70px] sm:h-[70px] rounded-2xl bg-indigo-500/20 flex items-center justify-center shrink-0">

                        <i class="ph-fill ph-clock text-3xl text-indigo-500"></i>

                    </div>

                </div>

            </div>

        </div>

       @if ($eventsWithWeather->isEmpty())

<div class="mt-10 reveal">

    <div class="glass rounded-[36px] p-12 sm:p-16 text-center animate-up">

        <div class="w-32 h-32 mx-auto rounded-full glass-deep flex items-center justify-center float-slow">

            <i class="ph-duotone ph-cloud-slash text-7xl text-white"></i>

        </div>

        <h2 class="text-2xl sm:text-3xl font-bold text-white mt-8 tracking-tight">
            Belum Ada Event Outdoor
        </h2>

        <p class="text-white/70 mt-3 max-w-lg mx-auto leading-relaxed">
            Tambahkan event beserta kota venue untuk mulai memonitor prakiraan
            cuaca secara realtime.
        </p>

        <a href="{{ route('events.create') }}"
            class="inline-flex items-center gap-3 mt-8 px-7 py-4 rounded-2xl bg-white text-slate-900 font-semibold hover:scale-105 active:scale-95 transition shadow-lg shadow-black/20">

            <i class="ph-bold ph-plus"></i>

            Tambah Event

        </a>

    </div>

</div>

@else

<div class="space-y-10 mt-10">

@foreach($eventsWithWeather as $index=>$item)

@php

$event=$item['event'];

$forecast=$item['forecast'];

$today=$forecast[0]??null;

$rain=$today['rain_probability']??0;

$level=$today['mitigasi']['level']??'aman';

@endphp

<div
class="glass rounded-[34px] overflow-hidden hover-card animate-up tilt-card"
style="animation-delay:{{$index*0.08}}s">

<div class="relative p-7 sm:p-10">

<div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-blue-400/20 blur-3xl pointer-events-none"></div>

<div class="relative flex flex-col lg:flex-row justify-between gap-10">

<div class="flex-1">

<div class="flex flex-wrap items-center gap-3">

<h2 class="text-2xl sm:text-4xl font-bold dark:text-white tracking-tight">

{{ $event->nama_event }}

</h2>

<span class="{{ data_get($today, 'mitigasi.badge_class', 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400') }} px-4 py-1.5 rounded-full text-xs font-bold tracking-wide">

{{ data_get($today, 'mitigasi.label', 'Data Belum Tersedia') }}

</span>

</div>

<div class="flex flex-wrap items-center gap-3 mt-6 text-sm sm:text-[15px] text-gray-600 dark:text-gray-300">

<div class="chip chip-light flex items-center gap-2 px-4 py-2 rounded-full font-medium">

<i class="ph-fill ph-map-pin text-blue-500"></i>

{{ $event->kota_venue }}

</div>

<div class="chip chip-light flex items-center gap-2 px-4 py-2 rounded-full font-medium">

<i class="ph-fill ph-calendar text-indigo-500"></i>

{{ $event->tanggal_event->translatedFormat('d F Y') }}

</div>

<div class="chip chip-light flex items-center gap-2 px-4 py-2 rounded-full font-medium">

<i class="ph-fill ph-hourglass text-amber-500"></i>

H-{{ $event->hari_menuju_event }}

</div>

</div>

@if($today)

<div class="mt-9 flex flex-col sm:flex-row items-start sm:items-center gap-10">

<div>

<div class="text-6xl sm:text-8xl font-bold dark:text-white tracking-tighter leading-none">

{{ $today['avg_temp'] }}°

</div>

<div class="capitalize text-gray-500 dark:text-gray-400 mt-3 font-medium text-base">

{{ $today['description'] }}

</div>

</div>

<div class="flex flex-col gap-6 w-full sm:w-auto">

<div>

<div class="flex justify-between text-sm mb-2.5 dark:text-gray-300 font-medium">

<span class="flex items-center gap-1.5">
<i class="ph-fill ph-drop text-blue-400"></i>
Potensi Hujan
</span>

<span class="font-bold">

{{ $rain }}%

</span>

</div>

<div class="w-full sm:w-80 h-3.5 rounded-full progress-track">

<div

class="h-3.5 rounded-full progress-fill

{{

$rain>=80?'bg-gradient-to-r from-red-500 to-rose-400':

($rain>=60?'bg-gradient-to-r from-orange-400 to-amber-300':

($rain>=40?'bg-gradient-to-r from-yellow-400 to-amber-200':'bg-gradient-to-r from-emerald-400 to-teal-300'))

}}"

style="width:{{$rain}}%">

</div>

</div>

</div>

<div class="flex flex-wrap gap-2.5">

@foreach($today['mitigasi']['rekomendasi'] as $r)

<span class="px-4 py-2.5 rounded-xl chip chip-light dark:text-gray-200 text-sm font-medium">

{{ $r }}

</span>

@endforeach

</div>

</div>

</div>

@endif

</div>

<div class="mt-2 lg:mt-0 flex items-center justify-center">

<div class="w-40 h-40 sm:w-52 sm:h-52 rounded-full glass-deep flex items-center justify-center float-slow shadow-xl">

@if($rain>=80)

<i class="ph-duotone ph-cloud-rain text-[90px] sm:text-[110px] text-blue-400"></i>

@elseif($rain>=60)

<i class="ph-duotone ph-cloud-lightning text-[90px] sm:text-[110px] text-yellow-400"></i>

@elseif($rain>=40)

<i class="ph-duotone ph-cloud text-[90px] sm:text-[110px] text-slate-400"></i>

@else

<i class="ph-duotone ph-sun text-[90px] sm:text-[110px] text-yellow-300"></i>

@endif

</div>

</div>

</div>

</div>

<div class="px-7 sm:px-10 pb-9">

<p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4 flex items-center gap-2">
<i class="ph-fill ph-calendar-blank"></i>
Prakiraan Mingguan
</p>

<div class="flex gap-4 sm:gap-5 overflow-x-auto pb-2 no-scrollbar">

@foreach($forecast as $day)

<div class="min-w-[168px] sm:min-w-[178px] glass rounded-[26px] p-6 text-center hover-card reveal">

<div class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wide">

{{ \Carbon\Carbon::parse($day['date'])->translatedFormat('D') }}

</div>

<div class="mt-3 flex justify-center">

@if($day['rain_probability']>=80)

<i class="ph-duotone ph-cloud-rain text-5xl text-blue-400"></i>

@elseif($day['rain_probability']>=60)

<i class="ph-duotone ph-cloud-lightning text-5xl text-yellow-400"></i>

@elseif($day['rain_probability']>=40)

<i class="ph-duotone ph-cloud text-5xl text-slate-400"></i>

@else

<i class="ph-duotone ph-sun text-5xl text-yellow-300"></i>

@endif

</div>

<div class="mt-3 text-3xl sm:text-4xl font-bold dark:text-white tracking-tight">

{{ $day['avg_temp'] }}°

</div>

<div class="capitalize text-xs mt-2 text-gray-500 dark:text-gray-400 font-medium">

{{ $day['description'] }}

</div>

<div class="mt-6">

<div class="h-2.5 rounded-full progress-track">

<div

class="h-2.5 rounded-full progress-fill

{{

$day['rain_probability']>=80?'bg-gradient-to-r from-red-500 to-rose-400':

($day['rain_probability']>=60?'bg-gradient-to-r from-orange-400 to-amber-300':

($day['rain_probability']>=40?'bg-gradient-to-r from-yellow-400 to-amber-200':'bg-gradient-to-r from-green-400 to-emerald-300'))

}}"

style="width:{{$day['rain_probability']}}%">

</div>

</div>

<div class="mt-2.5 text-xs font-bold dark:text-gray-300">

{{ $day['rain_probability'] }}%

</div>

</div>

</div>

@endforeach

</div>

<div class="flex justify-end mt-9">

<a
href="{{ route('events.show',$event) }}"
class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-semibold hover:scale-105 active:scale-95 transition shadow-lg shadow-black/10 text-[15px]">

Lihat Detail Event

<i class="ph-bold ph-arrow-right"></i>

</a>

</div>

</div>

</div>

@endforeach

</div>

@endif

    </div>

</div>

<script>
(function(){
    if (typeof window === 'undefined') return;

    var revealTargets = document.querySelectorAll('.weather-scope .reveal');

    if ('IntersectionObserver' in window && revealTargets.length) {
        var revealObserver = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        revealTargets.forEach(function(el){ revealObserver.observe(el); });
    } else {
        revealTargets.forEach(function(el){ el.classList.add('is-visible'); });
    }

    var tiltCards = document.querySelectorAll('.weather-scope .tilt-card');

    tiltCards.forEach(function(card){
        var maxTilt = 3;

        card.addEventListener('mousemove', function(e){
            var rect = card.getBoundingClientRect();
            var x = (e.clientX - rect.left) / rect.width - 0.5;
            var y = (e.clientY - rect.top) / rect.height - 0.5;

            card.style.transform = 'perspective(1000px) rotateX(' + (-y * maxTilt) + 'deg) rotateY(' + (x * maxTilt) + 'deg)';
        });

        card.addEventListener('mouseleave', function(){
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
        });
    });
})();
</script>

@endsection