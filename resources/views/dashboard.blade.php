@extends('layouts.app')

@push('style-scripts')
    <!-- INTERNAL Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
@endpush

@section('style')
    <style>
        .counter-icon {
            width: 3rem;
            height: 3rem;
            line-height: 3rem;
        }
    </style>
@endsection


@section('content')
    <!--app-content open-->
    <div class="app-content">
        <div class="side-app">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <h6 class="bg-primary p-2 text-center text-white mb-0">Yesterday Distributor Met</h6>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="mb-0 number-font">{{ $data['yesterday_distributor_met'] }}</h2>
                                            <div class="counter-icon bg-primary-gradient box-shadow-secondary brround mb-0">
                                                <i class="fe fe-trending-up text-white mb-5" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <h6 class="bg-primary p-2 text-center text-white mb-0">Yesterday Stockiest Met</h6>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="mb-0 number-font">{{ $data['yesterday_stockiest_met'] }}</h2>
                                            <div class="counter-icon bg-danger-gradient box-shadow-secondary brround mb-0">
                                                <i class="icon fa fa-suitcase text-white mb-5" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <h6 class="bg-primary p-2 text-center text-white mb-0">Yesterday Chemist Met</h6>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="mb-0 number-font">{{ $data['yesterday_chemist_met'] }}</h2>
                                            <div class="counter-icon bg-secondary-gradient box-shadow-secondary brround mb-0">
                                                <i class="fa fa-user-md text-white mb-5" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <h6 class="bg-primary p-2 text-center text-white mb-0">Yesterday Working Met</h6>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="mb-0 number-font">{{ $data['yesterday_working_hrs'] }}</h2>
                                            <div class="counter-icon bg-success-gradient box-shadow-secondary brround mb-0">
                                                <i class="fa fa-clock-o text-white mb-5" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Total Kms/Hrs</h3>
                        </div>
                        <div class="card-body pb-0">
                            <div id="line_chart" class="chart-donut"></div>
                        </div>
                    </div>
                </div><!-- COL END -->
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <!-- <div class="card overflow-hidden">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Holidays</h3>
                        </div>
                    </div>
                    <div class="card-body pb-0 pt-4 mb-5">
                        @foreach($data['holidays'] as $holiday)
                        <div class="activity1">
                            <div class="activity-blog">
                                <div class="activity-img brround bg-primary-transparent text-primary">
                                    <i class="fa fa-user-plus fs-20"></i>
                                </div>
                                <div class="activity-details d-flex">
                                    <div>
                                        <b><span class="text-dark"></span> {{ @$holiday->name }}</b>
                                    </div>
                                    <div class="ms-auto fs-13 text-dark fw-semibold"><span class="badge bg-primary text-white">{{ @$holiday->date }}</span></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> -->

                    <div class="card ">
                        <div class="card-header">
                            <h5 class="card-title">Holidays</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="holiday_list_box p-2">
                                @foreach($data['holidays'] as $holiday)
                                    <div class="media mb-5">
                                        <div class="d-flex me-3">
                                            <i class="fa fa-calendar pt-2 text-primary holiday_icons"></i>
                                        </div>
                                        <div class="media-body">
                                            <a href="#" class="text-dark">{{ @$holiday->name }}</a>
                                            <div class="text-muted small">{{ @$holiday->state->name }}</div>
                                        </div>
                                        <button type="button" class="btn btn-info btn-sm d-block">{{ date('d/m/Y', strtotime(@$holiday->date)) }}</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div><!-- COL END -->
            </div>
            <!-- ROW-1 END -->

            <!-- ROW-3 -->
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Employees</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive export-table">
                                <table id="file-datatable" class="table table-bordered key-buttons text-nowrap mb-0 datatable_show_5">
                                    <thead class="border-top bg-primary">
                                    <tr>
                                        <th class="bg-transparent text-white border-bottom-0 w-5">S.no</th>
                                        <th class="bg-transparent text-white border-bottom-0">Name</th>
                                        <th class="bg-transparent text-white border-bottom-0">Date</th>
                                        <th class="bg-transparent text-white border-bottom-0">Status</th>
                                        <th class="bg-transparent text-white border-bottom-0">Check In</th>
                                        <th class="bg-transparent text-white border-bottom-0">Check Out</th>
                                        <th class="bg-transparent text-white border-bottom-0">Working Hrs</th>
                                        <th class="bg-transparent text-white border-bottom-0">Travelled Kms</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($data['employees']))
                                    @foreach($data['employees'] as $employee)
                                        <tr class="border-bottom">
                                            <td class="text-muted fs-15 fw-semibold text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @if($employee['avatar'] == '')
                                                        <img src="{{ asset(config('constants.uploadPaths.defaultProfilePic')) }}" alt="profile-user" class="avatar avatar-md brround mt-1">
                                                    @else
                                                        <img src="{{ asset(config('constants.uploadPaths.profilePic').$employee['avatar']) }}" alt="pic" class="avatar avatar-md brround mt-1">
                                                    @endif
{{--                                                    <img src="{{ asset(config('constants.uploadPaths.profilePic').$employee['avatar']) }}" alt="pic" class="avatar avatar-md brround mt-1">--}}
                                                    <div class="ms-2 mt-0 mt-sm-2 d-block">
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{ $employee['name'] }}</h6>
                                                        <span class="fs-12 text-muted">{{ $employee['email'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted fs-15 fw-semibold">{{ $data['date'] }}</td>
                                            @if($employee['status'] === 'Present')
                                                <td class="text-success fs-15 fw-semibold">{{ $employee['status'] }}</td>
                                            @elseif($employee['status'] === 'Absent')
                                                <td class="text-danger fs-15 fw-semibold">{{ $employee['status'] }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td class="text-success fs-15 fw-semibold">{{ $employee['check_in'] }}</td>
                                            <td class="text-danger fs-15 fw-semibold">{{ $employee['check_out'] }}</td>
                                            <td class="text-info fs-15 fw-semibold">{{ $employee['working_hrs'] }}</td>
                                            <td class="text-danger fs-15 fw-semibold">
                                                    <a href="{{ route('employees.track', ['user' => $employee['user_id'], 'date' => $data['date']]) }}">

                                                    {{ $employee['travelled_kms']." Km" }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>

                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12">

                </div>
            </div><!-- COL END -->
            <!-- ROW-3 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
@endsection


@push('js-scripts')
    <!-- INTERNAL Data tables js-->
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>

    <!-- ECHART JS-->
    <script src="{{asset('assets/plugins/echarts/echarts.js')}}"></script>
    <!-- APEXCHART JS -->
    <script src="{{asset('assets/js/apexcharts.js')}}"></script>
    <script src="{{asset('assets/js/form-js.js')}}"></script>
@endpush

@section('script')
    <script>
        $(document).ready(function() {
            // dataTablePattern('.data-table');
            dataTablePattern('.datatable_show_5');


            /**
             * @lineChart (which is not a function yet)
             * Line Chart code showing kilmoeter and working hours of an employee
             *
             * request (None)
             * response (Showing chart)
             */
            var lineChartOptions = {
                series: [{
                    name: "Kms",
                    data: @json($data['travelled_kms'])
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: true
                },
                stroke: {
                    curve: 'straight'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    title: {
                        text: 'Month'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Travel Kms'
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#line_chart"), lineChartOptions);
            chart.render();
        });
    </script>
@endsection
