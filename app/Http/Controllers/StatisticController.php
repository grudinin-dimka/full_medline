<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\Validator;

/* Модели */
use App\Models\Tracking;
use Carbon\Carbon;

/* Ошибки */
use Throwable;

class StatisticController extends Controller
{
    /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
    /* |                      GET                          |*/
    /* |___________________________________________________|*/
    public function getTrackingStatisticsList(Request $request)
    {
        try {
            $statistics = Tracking::all()->reverse()->values()->take(1000);

            foreach ($statistics as $key => $statistic) {
                $statistic->created = $statistic->created_at
                    ->timezone(env('APP_TIMEZONE', 'Asia/Yekaterinburg')) // Конвертируем в часовой пояс приложения
                    ->format('Y-m-d\TH:i'); // Формат без секунд
            };

            return response()->json([
                "success" => true,
                "debug" => false,
                "message" => "Данные получены.",
                "result" => $statistics,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "success" => false,
                "debug" => true,
                "message" => $e->getMessage(),
                "result" => null,
            ], 500);
        };
    }

    /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
    /* |                      POST                         |*/
    /* |___________________________________________________|*/
    public function getTrackingStatisticsRange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end' => 'required|date',
        ], [
            'required' => 'Поле :attribute обязательно.',
            'date' => 'Формат :attribute неверен.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "debug" => true,
                "errors" => $validator->errors(),
                "message" => "Некорректные данные.",
                "result" => null,
            ], 422);
        };

        try {
            if (is_array($request->types) && $request->types) {
                if (count($request->types) === 1) {
                    $statistics = Tracking::where('created_at', '>=', Carbon::parse($request->start))
                        ->where('created_at', '<=', Carbon::parse($request->end)->addDay())
                        ->whereIn('type', $request->types)
                        ->get()
                        ->groupBy(
                            function ($item) {
                                return $item->created_at->format('Y-m-d'); // Группируем только по дате (без времени)
                            }
                        );

                    $countDays = Carbon::parse($request->start)->diffInDays(Carbon::parse($request->end));
                    $statisticsFormated = [];
                    $firsDay = Carbon::parse($request->start);

                    for ($i = $countDays; $i >= 0; $i--) {
                        $statisticsFormated[Carbon::parse($firsDay)->format('Y-m-d')] = count($statistics[Carbon::parse($firsDay)->format('Y-m-d')] ?? []);

                        $firsDay->addDay();
                    };
                } else {
                    $statistics = Tracking::where('created_at', '>=', Carbon::parse($request->start))
                        ->where('created_at', '<=', Carbon::parse($request->end)->addDay())
                        ->whereIn('type', $request->types)
                        ->get()
                        ->groupBy([
                            'type',
                            function ($item) {
                                return $item->created_at->format('Y-m-d'); // Группируем только по дате (без времени)
                            },
                        ]);

                    $countDays = Carbon::parse($request->start)->diffInDays(Carbon::parse($request->end));
                    $statisticsFormated = [];
                    $firsDay = Carbon::parse($request->start);

                    foreach ($statistics as $key => $statistic) {
                        $statisticsFormated[$key] = [];
                    };

                    for ($i = $countDays; $i >= 0; $i--) {
                        foreach ($statistics as $key => $statistic) {
                            $statisticsFormated[$key][Carbon::parse($firsDay)->format('Y-m-d')] = count($statistic[Carbon::parse($firsDay)->format('Y-m-d')] ?? []);
                        }

                        $firsDay->addDay();
                    };
                };
            };

            return response()->json([
                "success" => true,
                "debug" => false,
                "message" => "Данные успешно получены.",
                "result" => $statisticsFormated,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "success" => false,
                "debug" => true,
                "message" => $e->getMessage(),
                "result" => null,
            ], 500);
        };
    }

    /* Получение данных для диаграммы */
    public function getTrackingStatisticsDiagram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
        ], [
            'required' => 'Отсутствует тип.',
            'string' => 'Значение типа не является строкой.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "debug" => true,
                "errors" => $validator->errors(),
                "message" => "Некорректные данные.",
                "result" => null,
            ], 422);
        };

        try {
            switch ($request->type) {
                case 'Клиники':
                    $statistic = Tracking::where('type', 'Клиники')
                        ->get()
                        ->groupBy(['meta'])
                        ->map(function ($group) {
                            return count($group);
                        })
                        ->toArray();
                    break;
                case 'Источники':
                    $statistic = Tracking::where('type', 'Посещение')->where('meta', '<>', 'Сайт')
                        ->get()
                        ->groupBy(['meta'])
                        ->map(function ($group) {
                            return count($group);
                        })
                        ->toArray();
                    break;
                default:
                    $statistic = [];
                    break;
            }

            if (count($statistic) >= 10) {
                /* Подсчёт суммы значений массива */
                $statisticSum = array_sum($statistic);
    
                $statisticFiltered = [];
    
                foreach ($statistic as $key => $value) {
                    if (round($value / $statisticSum * 100, 0) < 1) {
                        if (!isset($statisticFiltered["Другое"])) {
                            $statisticFiltered["Другое"] = $value;
                        } else {
                            $statisticFiltered["Другое"] += $value;
                        };
    
                        continue;
                    }
    
                    $statisticFiltered[$key] = $value;
                };

                $statistic = $statisticFiltered;
            };

            return response()->json([
                "success" => true,
                "debug" => false,
                "message" => "Данные успешно получены.",
                "result" => $statistic ?? [],
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "success" => false,
                "debug" => true,
                "message" => $e->getMessage(),
                "result" => null,
            ], 500);
        };
    }
}
