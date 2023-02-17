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
                    @include('layouts.error_display')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter</h3>
                        </div>
                        <div class="card-body">
                            <form method="get" action="{{ route('holidays.index') }}">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">Year<span class="text-danger">*</span></label>
                                                <select name="year" class="form-control mb-4 select2 @error('year') is-invalid @enderror"  id="year" required>
                                                    <option  value="" >-- Select Year --</option>
                                                    @for($year = date('Y'); $year >= 2020; $year--)
                                                        @php
                                                            $yearCondition = "";
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
                                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                                <label class="form-label">States<span class="text-danger">*</span></label>
                                                <select name="state_id"  class="form-control mb-4 select2 @error('state_id') is-invalid @enderror select2"  id="state_id" required">
                                                    <option  value="" >-- Select State --</option>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}" {{ app('request')->input('state_id') == $state->id ? 'selected' : "" }}>{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('filter_type'))
                                                    <span class="text-danger">{{ $errors->first('filter_type') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
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
                                <span>Holidays</span>

                            </h3>

                            <div class="ms-auto pageheader-btn">
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add</button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="{{ route('holidays.store') }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add Holiday</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <input type="hidden" value="holiday" name="type">
                                                            <label class="form-label">Holiday<span class="text-danger">*</span></label>
                                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-4 @error('name') is-invalid @enderror" placeholder="Enter Name" id="name" required>
                                                            @if ($errors->has('name'))
                                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label">Date<span class="text-danger">*</span></label>
                                                            <input type="date" name="date" value="{{ old('date') }}" class="form-control mb-4 @error('date') is-invalid @enderror" placeholder="Enter Name" id="date" required>
                                                            @if ($errors->has('date'))
                                                                <span class="text-danger">{{ $errors->first('date') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                                            <select type="text" name="state" class="form-control mb-4 @error('name') is-invalid @enderror"  id="state" required>
                                                                @foreach($states as $state)
                                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('state'))
                                                                <span class="text-danger">{{ $errors->first('state') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label">Year<span class="text-danger">*</span></label>
                                                            <select type="text" name="year" class="form-control mb-4 @error('year') is-invalid @enderror"  id="year" required>
                                                                <option  value="" >-- Year --</option>
                                                                @for($years = date('Y'); $years >= 2020; $years--)
                                                                    <option value="{{ $years }}">{{ $years }}</option>
                                                                @endfor
                                                            </select>
                                                            @if ($errors->has('year'))
                                                                <span class="text-danger">{{ $errors->first('year') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-secondary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom data-table">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Year</th>
                                        <th class="text-white">Date</th>
                                        <th class="text-white">Name</th>
                                        <th class="text-white">State</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list-data">
                                    @foreach($holidays as $holiday)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $holiday->year }}</td>
                                            <td>{{ $holiday->date }}</td>
                                            <td>{{ $holiday->name }}</td>
                                            <td>{{ $holiday->state->name }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $holiday->id }}">
                                                    <i class="fa fa-edit"></i> Edit</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="editModal{{ $holiday->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post" action="{{ route('holidays.update', $holiday->id) }}">
                                                                @method('PATCH')
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Holiday</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label">Name<span class="text-danger">*</span></label>
                                                                            <input type="text" name="name" value="{{ $holiday->name }}" class="form-control mb-4 @error('name') is-invalid @enderror" placeholder="Enter Name" id="name" required>
                                                                            @if ($errors->has('name'))
                                                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                                                            @endif
                                                                            <input type="hidden" value="holiday" name="type">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label">Date<span class="text-danger">*</span></label>
                                                                            <input type="date" name="date" value="{{ $holiday->date }}" class="form-control mb-4 @error('date') is-invalid @enderror" placeholder="Enter Name" id="date" required>
                                                                            @if ($errors->has('date'))
                                                                                <span class="text-danger">{{ $errors->first('date') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                                                            <select type="text" name="state" class="form-control mb-4 @error('name') is-invalid @enderror"  id="state" required>
                                                                                @foreach($states as $state)
                                                                                    <option value="{{ $state->id }}" {{ $state->id === $holiday->state->id ? 'selected' : "" }}>{{ $state->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('state'))
                                                                                <span class="text-danger">{{ $errors->first('state') }}</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label">Year<span class="text-danger">*</span></label>
                                                                            <select type="text" name="year" class="form-control mb-4 @error('name') is-invalid @enderror "  id="year" required>
                                                                                <option  value="" >-- Year --</option>
                                                                                @for($year = date('Y'); $year >= 2020; $year--)
                                                                                    <option value="{{ $year }}" {{ $year == $holiday->year ? 'selected' : "" }}>{{ $year }}</option>
                                                                                @endfor
                                                                            </select>
                                                                            @if ($errors->has('year'))
                                                                                <span class="text-danger">{{ $errors->first('year') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-secondary">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <!-- filter list  -->
                                    <tbody class="filter-data">

                                    </tbody>
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

