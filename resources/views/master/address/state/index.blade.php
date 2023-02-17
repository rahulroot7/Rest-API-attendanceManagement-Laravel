@extends('layouts.app')

@push('style-scripts')
    <!-- INTERNAL Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

    <!-- Parsley css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/parsley/parsley.css')}}">
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
                            <h3 class="card-title">States</h3>

                            <div class="ms-auto pageheader-btn">
                                <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="{{ route('states.store') }}" id="add_state_form">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add State</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-2 @error('name') is-invalid @enderror" data-parsley-trigger="keyup" data-parsley-error-message="Enter state name" placeholder="Enter state name" id="name" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                                        @endif

                                                        <input type="hidden" value="state" name="type">
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
                                <table class="table table-bordered text-nowrap border-bottom data-table" >
                                    <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Name</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list-data">
                                    @foreach($states as $state)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $state->name }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $state->id }}">
                                                    <i class="fa fa-edit"></i> Edit</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="editModal{{ $state->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post" action="{{ route('states.update', $state->id) }}" id="edit_state_form">
                                                                @method('PATCH')
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit State</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                                                        <input type="text" name="name" value="{{ $state->name }}" class="form-control mb-2 @error('name') is-invalid @enderror" data-parsley-trigger="keyup" data-parsley-error-message="Enter state name" placeholder="Enter state name" id="name" required>                                                                        @if ($errors->has('name'))
                                                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                                                        @endif
                                                                        <input type="hidden" value="state" name="type">

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

    <!-- Parsley js -->
    <script src="{{asset('assets/plugins/parsley/parsley.min.js')}}"></script>

@endpush

@section('script')
    <script>
        $(document).ready(function() {
            dataTablePattern('.data-table');

            $('#add_state_form').parsley();
            $('#edit_state_form').parsley();
        });
    </script>
@endsection


