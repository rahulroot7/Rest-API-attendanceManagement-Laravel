@extends('layouts.app')

@push('style-scripts')
    <!-- Parsley css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/parsley/parsley.css')}}">
    <!-- SELECT2 CSS -->
    <link href="{{asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

    <!-- INTERNAL Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
@endpush

@section('style')
    <style>
        #state_id{
            margin-left: 10px;
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
                    @include('layouts.error_display')

                    <form method="get" action="{{ route('cities.index') }}" id="add_city_form">
                        <div class="ms-auto pageheader-btn p-2" style="display: inline-flex">
                            <label class="form-label">States<span class="text-danger">*</span></label>
                            <select name="state_id" id="state_id" class="form-control mb-4 @error('state_id') is-invalid @enderror select2"  id="state_id" required onchange="this.form.submit()">
                                <option  value="" >-- Select State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ app('request')->input('state_id') == $state->id ? 'selected' : "" }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <span>Cities</span>
                            </h3>
                            <div class="ms-auto pageheader-btn">
                                <button type="submit"  class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addCityModal">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="addCityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="{{ route('cities.store') }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add City</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <input type="hidden" value="city" name="type">
                                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-2 @error('name') is-invalid @enderror" data-parsley-trigger="keyup" data-parsley-error-message="Enter city name" placeholder="Enter city name" id="city_name" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">State<span class="text-danger">*</span></label>
                                                        <select type="text" name="state" class="form-control mb-2 @error('name') is-invalid @enderror select2" id="state_name" data-parsley-errors-container="#state_error_box" data-parsley-error-message="Select state" required>                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('state'))
                                                            <span class="text-danger">{{ $errors->first('state') }}</span>
                                                        @endif
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
                                        <th class="text-white">Name</th>
                                        <th class="text-white">State</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list-data">
                                    @foreach($cities as $city)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $city->name }}</td>
                                            <td>{{ $city->state->name }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $city->id }}">
                                                    <i class="fa fa-edit"></i> Edit</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="editModal{{ $city->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post" action="{{ route('cities.update', $city->id) }}" id="edit_city_form">
                                                                @method('PATCH')
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit City</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                                                        <input type="text" name="name" value="{{ $city->name }}" class="form-control mb-2 @error('name') is-invalid @enderror" data-parsley-trigger="keyup" data-parsley-error-message="Enter city name" placeholder="Enter city name" id="edit_city_name" required>
                                                                        @if ($errors->has('name'))
                                                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                                                        @endif
                                                                        <input type="hidden" value="city" name="type">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="form-label">State<span class="text-danger">*</span></label>
                                                                        <select type="text" name="state" class="form-control mb-2 @error('name') is-invalid @enderror select2" data-parsley-errors-container="#edit_state_error_box" id="edit_state_name" required>
                                                                            @foreach($states as $state)
                                                                                <option value="{{ $state->id }}" {{ $state->id === $city->state->id ? 'selected' : "" }}>{{ $state->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('state'))
                                                                            <span class="text-danger">{{ $errors->first('state') }}</span>
                                                                        @endif
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

    <!-- SELECT2 JS -->
    <script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/form-js.js')}}"></script>

    <script src="{{asset('assets/plugins/parsley/parsley.min.js')}}"></script>

@endpush

@section('script')
    <script>
        $(document).ready(function() {
            dataTablePattern('.data-table');

            $('#add_city_form').parsley();
            $('#edit_city_form').parsley();
            $('.select2').select2();

            {{--// DataTable--}}
            {{--$('.data-table').DataTable({--}}
            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    ajax: "{{ route('getCities') }}",--}}
            {{--    columns: [--}}
            {{--        { data: 'id' },--}}
            {{--        { data: 'state' },--}}
            {{--        { data: 'name' },--}}
            {{--    ]--}}
            {{--});--}}

        });

    </script>
@endsection
