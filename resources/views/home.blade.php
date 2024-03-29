@extends('layouts.admin')
@section('content')

{{--    {{ dd ($latest_logins[0]->id ) }}--}}
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="{{ $chart1->options['column_class'] }}">
                            <h3>{!! $chart1->options['chart_title'] !!}</h3>
                            {!! $chart1->renderHtml() !!}
                        </div>
                        <div class="{{ $settings2['column_class'] }}">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-0">
                                    <div class="text-value">{{ number_format($settings2['total_number']) }}</div>
                                    <div>{{ $settings2['chart_title'] }}</div>
                                    <br />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="overflow-x: auto;">
                            <h3>{{ 'Login Login'  }}</h3>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>

                                        <th>Full name</th><th>Email</th><th>Logged in at</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($latest_logins as $latest_login)
                                        <tr>

                                            <td>{{$latest_login->name . ' ' . $latest_login->last_name}} </td><td> {{$latest_login->email}}</td> <td>{{$latest_login->updated_at}}</td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Widget - latest entries --}}
{{--                        <div class="{{ $settings3['column_class'] }}" style="overflow-x: auto;">--}}
{{--                            <h3>{{ $settings3['chart_title'] }}</h3>--}}
{{--                            <table class="table table-bordered table-striped">--}}
{{--                                <thead>--}}
{{--                                    <tr>--}}
{{--                                        @foreach($settings3['fields'] as $key => $value)--}}
{{--                                            <th>--}}
{{--                                                {{ trans(sprintf('cruds.%s.fields.%s', $settings3['translation_key'] ?? 'pleaseUpdateWidget', $key)) }}--}}
{{--                                            </th>--}}
{{--                                        @endforeach--}}
{{--                                    </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                    @forelse($settings3['data'] as $entry)--}}
{{--                                        <tr>--}}
{{--                                            @foreach($settings3['fields'] as $key => $value)--}}
{{--                                                <td>--}}
{{--                                                    @if($value === '')--}}
{{--                                                        {{ $entry->{$key} }}--}}
{{--                                                    @elseif(is_iterable($entry->{$key}))--}}
{{--                                                        @foreach($entry->{$key} as $subEentry)--}}
{{--                                                            <span class="label label-info">{{ $subEentry->{$value} }}</span>--}}
{{--                                                        @endforeach--}}
{{--                                                    @else--}}
{{--                                                        {{ data_get($entry, $key . '.' . $value) }}--}}
{{--                                                    @endif--}}
{{--                                                </td>--}}
{{--                                            @endforeach--}}
{{--                                        </tr>--}}
{{--                                        @empty--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="{{ count($settings3['fields']) }}">{{ __('No entries found') }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforelse--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}

                        <div class="{{ $chart4->options['column_class'] }}">
                            <h3>{!! $chart4->options['chart_title'] !!}</h3>
                            {!! $chart4->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>{!! $chart1->renderJs() !!}{!! $chart4->renderJs() !!}
@endsection
