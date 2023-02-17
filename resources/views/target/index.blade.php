@extends('layouts.app')

@push('style-scripts')
    <!-- INTERNAL Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
@endpush

@section('style')
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
                            <form method="get" action="{{ route('targets.index') }}">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">Year<span class="text-danger">*</span></label>
                                                <select name="year" class="form-control mb-4 @error('year') is-invalid @enderror"  id="year" required>
                                                    <option  value="" >-- Year --</option>
                                                    @for($year = date('Y'); $year >= 2020; $year--)
                                                        @php
                                                            $yearCondition = Date('Y');
                                                            if(app('request')->input('year') != ''){
                                                                $yearCondition = app('request')->input('year');
                                                            }
                                                        @endphp
                                                        <option value="{{ $year }}" {{ $year == $yearCondition ? 'selected' : "" }}>{{ $year }}</option>
                                                    @endfor
                                                </select>
                                                @if ($errors->has('year'))
                                                    <span class="text-danger">{{ $errors->first('year') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">Month<span class="text-danger">*</span></label>
                                                <select name="month" class="form-control mb-4 @error('month') is-invalid @enderror select2"  id="month" required>
                                                    <option  value="">-- Month --</option>
                                                    @for($months=1; $months<=12; $months++)
                                                        @php
                                                            $monthCondition = Date('m');
                                                            if(app('request')->input('month') != ''){
                                                                $monthCondition = app('request')->input('month');
                                                            }
                                                        @endphp
                                                        <option value="{{ date('m', mktime(0,0,0,$months,1)) }}"
                                                            {{ $monthCondition == date('m', mktime(0,0,0,$months,1)) ? 'selected' : '' }}>
                                                            {{ date('F',mktime(0,0,0,$months,1)) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @if ($errors->has('month'))
                                                    <span class="text-danger">{{ $errors->first('month') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">Type</label>
                                                <select name="filter_type" class="form-control mb-4 @error('filter_type') is-invalid @enderror select2" id="filter_type" required>
                                                    <option value="monthly" {{ app('request')->input('filter_type') == 'monthly' ? "Selected" : "" }}>Monthly</option>
                                                    <option value="daily" {{ app('request')->input('filter_type') == 'daily' ? "Selected" : "" }}>Daily</option>
                                                </select>
                                                @if ($errors->has('filter_type'))
                                                    <span class="text-danger">{{ $errors->first('filter_type') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="submit" name="submit" value="filter" class="btn btn-success">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Targets/Lists
                            </h3>

                            <div class="ms-auto pageheader-btn">

                                <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addCityModal">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New</button>
                            </div>

                            {{-- Add Target modal --}}
                            <div class="modal fade" id="addCityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="{{ route('targets.store') }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Target</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Year<span class="text-danger">*</span></label>
                                                        <select name="year" class="form-control mb-4 @error('year') is-invalid @enderror"  id="year" required>
                                                            <option  value="" >-- Year --</option>
                                                            @for($years = date('Y'); $years >= 2020; $years--)
                                                                <option value="{{ $years }}">{{ $years }}</option>
                                                            @endfor
                                                        </select>
                                                        @if ($errors->has('year'))
                                                            <span class="text-danger">{{ $errors->first('year') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Month<span class="text-danger">*</span></label>
                                                        <select name="month" class="form-control mb-4 @error('month') is-invalid @enderror"  id="month" required>
                                                            <option  value="">-- Month --</option>
                                                            @for($months=1; $months<=12; $months++)
                                                                <option value="{{ date('m', mktime(0,0,0,$months,1)) }}">
                                                                    {{ date('F',mktime(0,0,0,$months,1)) }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        @if ($errors->has('month'))
                                                            <span class="text-danger">{{ $errors->first('month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Employee<span class="text-danger">*</span></label>
                                                        <select name="user_id" class="form-control mb-4 @error('user_id') is-invalid @enderror"  id="user_id" required>
                                                            <option  value="" >-- Select Employee --</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('user_id'))
                                                            <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Target<span class="text-danger">*</span></label>
                                                        <input type="number" name="target" class="form-control mb-4 @error('target') is-invalid @enderror"  id="target" required>
                                                        @if ($errors->has('target'))
                                                            <span class="text-danger">{{ $errors->first('target') }}</span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- show success and unsuccess message -->
                        @include('layouts.error_display')
                        <!-- End show success and unsuccess message -->


                        <div class="card-body">
                            <div class="table-responsive export-table">

                                @isset($filterType)
                                    @if($filterType === 'monthly')
                                        @include('target.monthly_target')
                                    @elseif($filterType === 'daily')
                                        @include('target.daily_target')
                                    @endif
                                @endisset

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
