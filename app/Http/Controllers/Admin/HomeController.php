<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    /**
     * @throws Exception
     */
    public function index(): Factory|View|Application
    {
        $settings1 = [
            'chart_title'           => 'Latest Users',
            'chart_type'            => 'bar',
            'report_type'           => 'group_by_date',
            'model'                 => \App\Models\User::class,
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '60',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $chart1 = new LaravelChart($settings1);

        $settings2 = [
            'chart_title'           => 'Total Numbers of Users for the Year',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => \App\Models\User::class,
            'group_by_field'        => 'email_verified_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'year',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings2['total_number'] = 0;
        if (class_exists($settings2['model'])) {
            $settings2['total_number'] = $settings2['model']::when( true, function ($query) use ($settings2) {
                if (isset($settings2['filter_days'])) {
                    return $query->where($settings2['filter_field'], '>=',
                        now()->subDays($settings2['filter_days'])->format('Y-m-d'));
                }
                if (isset($settings2['filter_period'])) {
                    switch ($settings2['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday')); break;
                        case 'month': $start = date('Y-m').'-01'; break;
                        case 'year': $start = date('Y').'-01-01'; break;
                    }
                    if (isset($start)) {
                        return $query->where($settings2['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings2['aggregate_function']}($settings2['aggregate_field'] ?? '*');
        }

        $settings3 = [
            'chart_title'           => 'Latest Login',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => \App\Models\User::class,
            'group_by_field'        => 'updated_at',
            'group_by_period'       => 'year',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '40',
            'fields'                => [
                'name'       => '',
                'email'      => '',
                'updated_at' => '',
            ],
            'translation_key' => 'user',
        ];

        $settings3['data'] = [];
        if (class_exists($settings3['model'])) {
            $settings3['data'] = $settings3['model']::latest()
                ->take($settings3['entries_number'])
                ->get();
        }

        if (! array_key_exists('fields', $settings3)) {
            $settings3['fields'] = [];
        }

        $settings4 = [
            'chart_title'           => 'Total Statements',
            'chart_type'            => 'line',
            'report_type'           => 'group_by_date',
            'model'                 => \App\Models\Statement::class,
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'month',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
            'translation_key'       => 'statement',
        ];

        $chart4 = new LaravelChart($settings4);

        $latest_logins = User::query ()
            ->orderBy ('updated_at','desc')
            ->whereKeyNot ('1')
            ->limit(30)->get ();

        return view('home', compact('chart1', 'chart4', 'settings2', 'settings3','latest_logins'));
    }

}
