<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Tracking;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackLoad
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $page = "", string $subpage = ""): Response
    {
        $type = "";
        $meta = "";

        switch ($page) {
            case 'attendance':
                $type = "Посещение";                
                $meta = "Посещение";
                break;
            case 'specialists':
                $type = "Специалисты";
                $meta = "Специалисты";
                break;
            case 'prices':
                $type = "Цены";
                $meta = "Цены";
                break;
            case 'shedule':
                $type = "Расписание";
                $meta = "Расписание врачей";
                break;
            case 'news':
                $type = "Новости";
                $meta = "Список новостей";
                break;
            case 'video':
                $type = "Видео";
                $meta = "Раздел с видео";
                break;
            case 'contacts':
                $type = "Контакты";
                $meta = "Раздел с контактами";
                break;
            case 'about':
                $type = "О нас";
                $meta = "Раздел с информацией о нас";
                break;
            default:
                $type = "not defined";
                $meta = "not defined";
                break;
        }

        try {
            // Поиск браузера
            $browser = match (true) {
                str_contains($request->userAgent(), 'Edge') => 'Edge',
                str_contains($request->userAgent(), 'Opera') => 'Opera',
                str_contains($request->userAgent(), 'Firefox') => 'Firefox',
                str_contains($request->userAgent(), 'Chrome') => 'Chrome',
                str_contains($request->userAgent(), 'Safari') => 'Safari',
                default => 'Unknown',
            };

            if ($subpage != "") {
                $meta = $meta . " - " . $subpage;
            }

            $track = Tracking::create([
                "type" => $type ?? "not defined",
                "ip" => $request->ip() ?? "not defined",
                "user_agent" => $browser ?? "not defined",
                "meta" => $meta ?? "not defined",
            ]);

            return $next($request);
        } catch (Throwable $e) {
            Log::error($e);

            return $next($request);
        };
    }
}
