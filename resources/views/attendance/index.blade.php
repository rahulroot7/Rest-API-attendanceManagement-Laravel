@extends('layouts.app')

@push('style-scripts')
    <!-- INTERNAL Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

    <!-- Parsley css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/parsley/parsley.css')}}">
@endpush

@section('style')
    <style>
    </style>
@endsection

@section('php')
    @php
        $yearCondition = Date('Y');
        if(app('request')->input('year') != ''){
            $yearCondition = app('request')->input('year');
        }

        $monthCondition = Date('m');
        if(app('request')->input('month') != ''){
            $monthCondition = app('request')->input('month');
        }
    @endphp
@endsection

@section('content')

    <div class="app-content">
        <div class="side-app">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <!-- Row -->

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter</h3>
                        </div>
                        <div class="card-body">
                            <form method="get" id="attd_report" action="{{ route('attendances.index') }}">
                                <div class="row">
                                    <div class="col-md-10 offset-md-2">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">Year<span class="text-danger">*</span></label>
                                                <select name="year" class="form-control mb-4 select2 @error('year') is-invalid @enderror"  id="year" required>
                                                    <option  value="" >-- Select Year --</option>
                                                    @for($year = date('Y'); $year >= 2020; $year--)
                                                        <option value="{{ $year }}" {{ $year == $yearCondition ? 'selected' : "" }}>{{ $year }}</option>
                                                    @endfor
                                                </select>
                                                @if ($errors->has('year'))
                                                    <span class="text-danger">{{ $errors->first('year') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">Month<span class="text-danger">*</span></label>
                                                <select name="month" class="form-control mb-4 @error('month') is-invalid @enderror"  id="month" required>
                                                    <option  value="">-- Month --</option>
                                                    @for($months=1; $months<=12; $months++)
                                                        <option value="{{ date('m', mktime(0,0,0,$months,1)) }}" {{ $months == $monthCondition ? 'selected' : "" }}>
                                                            {{ date('F',mktime(0,0,0,$months,1)) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @if ($errors->has('month'))
                                                    <span class="text-danger">{{ $errors->first('month') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="submit" name="submit" value="filter" class="btn btn-secondary">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">Attendances</h3>
                        </div>

                        <!-- show success and unsuccess message -->
                        @include('layouts.error_display')
                        <!-- End show success and unsuccess message -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom data-table">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Emp Code</th>
                                        <th class="text-white">Emp Name</th>
                                        <th class="text-white">Absent</th>
                                        <th class="text-white">Leave</th>
                                        <th class="text-white">Holiday</th>
                                        <th class="text-white">WeekOff</th>
                                        <th class="text-white">Present</th>
                                        <th class="text-white">Total Paid Days</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list-data">
                                    @foreach($attdData as $attd)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attd['user']['employee_code'] }}</td>
                                            <td>{{ $attd['user']['name'] }}</td>
                                            <td>{{ $attd['attd_status']['absent'] }}</td>
                                            <td>{{ $attd['attd_status']['leave'] }}</td>
                                            <td>{{ $attd['attd_status']['holiday'] }}</td>
                                            <td>{{ $attd['attd_status']['week_off'] }}</td>
                                            <td>{{ $attd['attd_status']['present'] }}</td>
                                            <td>{{ $attd['attd_status']['paid_days'] }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <!-- filter list  -->
                                    <tfoot class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Emp Code</th>
                                        <th class="text-white">Emp Name</th>
                                        <th class="text-white">Absent</th>
                                        <th class="text-white">Leave</th>
                                        <th class="text-white">Holiday</th>
                                        <th class="text-white">WeekOff</th>
                                        <th class="text-white">Present</th>
                                        <th class="text-white">Total Paid Days</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                    </tfoot>
                                    <!-- end filter list -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Row -->
            </div>
            <!-- PAGE-HEADER END -->
        </div>
    </div>
@endsection

@push('js-scripts')

    <!-- INTERNAL Data tables js-->
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>

    <script src="{{asset('assets/js/form-js.js')}}"></script>

@endpush

@section('script')
    <script>
        $(document).ready(function() {
            dataTablePattern('.data-table');
        });
    </script>
@endsection
