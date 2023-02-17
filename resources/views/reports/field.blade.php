@extends('layouts.app')

@push('style-scripts')
<!-- INTERNAL Bootstrap DatePicker css-->
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">

<!-- Parsley css -->
<link rel="stylesheet" href="{{asset('assets/plugins/parsley/parsley.css')}}">

<!-- SELECT2 CSS -->
<link href="{{asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

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
                        <form method="get" id="field_report_form" action="{{ route('reports.field') }}">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                            <label class="form-label">From Date<span class="text-danger">*</span></label>
                                            <input type="text" name="from_date" value="{{ app('request')->input('from_date') }}" class="form-control mb-2 @error('from_date') is-invalid @enderror datepicker-dropdown" placeholder="01/01/2022" id="date_from" data-parsley-error-message="Select from date" required readonly>
                                            @if ($errors->has('from_date'))
                                            <span class="text-danger">{{ $errors->first('from_date') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                            <label class="form-label">To Date<span class="text-danger">*</span></label>
                                            <input type="text" name="to_date" value="{{ app('request')->input('to_date') }}" class="form-control mb-2 @error('to_date') is-invalid @enderror datepicker-dropdown" placeholder="01/01/2022" id="date_to" data-parsley-error-message="Select to date" required readonly>
                                            @if ($errors->has('to_date'))
                                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                            <div id="employee_error_box">
                                                <label class="form-label">Employee</label>
                                                <select name="user_id" class="form-control mb-2 @error('user_id') is-invalid @enderror select2" id="user_id" data-parsley-error-message="Select Employee" data-parsley-errors-container="#employee_error_box" >
                                                    <option value="">-- Select Employee -- </option>
                                                    <option value="all">All</option>
                                                    @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ app('request')->input('user_id') == $user->id ? "selected" : "" }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('user_id'))
                                            <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
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
                        <h3 class="card-title">Reports/Field</h3>
                    </div>

                    <!-- show success and unsuccess message -->
                    @include('layouts.error_display')
                    <!-- End show success and unsuccess message -->

                    <div class="card-body">
                        <div class="table-responsive export-table">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="field_report">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Emp Code</th>
                                        <th class="text-white">Emp Name</th>
                                        <th class="text-white">Date</th>
{{--                                        <th class="text-white">Attendance</th>--}}
                                        <th class="text-white">Working Hrs</th>
                                        <th class="text-white">Travelled Km</th>
                                        <th class="text-white">Stockiest Met</th>
                                        <th class="text-white">Distributor Met</th>
                                        <th class="text-white">Chemist Met</th>
                                    </tr>
                                </thead>
                                <tbody class="list-data">
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ @$report->user->employee_code }}</td>
                                        <td>{{ @$report->user->name }}</td>
                                        <td>{{ @$report->date }}</td>
{{--                                        <td>{{ @$report->date }}</td>--}}
                                        <td>{{ @$report->working_hrs }}</td>
                                        <td>
                                            <a href="{{ route('employees.track', ['user' => $report->user_id, 'date' => $report->date]) }}">
                                                {{ @$report->travelled_km }}
                                            </a>
                                        </td>
                                        <td>{{ @$report->stockiest_met }}</td>
                                        <td>{{ @$report->distributor_met }}</td>
                                        <td>{{ @$report->chemist_met }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!-- filter list  -->
                                <tfoot class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Emp Code</th>
                                        <th class="text-white">Emp Name</th>
                                        <th class="text-white">Date</th>
{{--                                        <th class="text-white">Attendance</th>--}}
                                        <th class="text-white">Working Hrs</th>
                                        <th class="text-white">Travelled Km</th>
                                        <th class="text-white">Stockiest Met</th>
                                        <th class="text-white">Distributor Met</th>
                                        <th class="text-white">Chemist Met</th>
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

@include('layouts/export_datatable')

@push('js-scripts')
<!-- INTERNAL Bootstrap-Datepicker js -->
<script src="{{asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

<!-- Parsley js -->
<script src="{{asset('assets/plugins/parsley/parsley.min.js')}}"></script>

<!-- SELECT2 JS -->
<script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>

<!-- FORMELEMENTS JS -->
<script src="{{asset('assets/js/form-elements.js')}}"></script>
@endpush

@section('script')
<script>
    $(document).ready(function() {

        $('#field_report_form').parsley();
        $('.select2').select2();

        $('#date_from, #date_to').bootstrapdatepicker({
            format: 'mm/dd/yyyy',
            viewMode: "date",
            clearBtn: true
        });
    });



    $(function(e) {
        //______File-Export Data Table
        var table = $('#field_report').DataTable({
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            scrollX: "100%",
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            }
        });
        table.buttons().container()
            .appendTo('#field_report_wrapper .col-md-6:eq(0)');

    });
</script>
@endsection
