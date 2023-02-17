@extends('layouts.app')

@push('style-scripts')
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
                            <h3 class="card-title">Employees List</h3>
                            @can('create', \App\Models\Employee::class)
                                <div class="ms-auto pageheader-btn">
                                    <a href="{{ route('employees.create') }}">
                                        <button class="btn btn-secondary">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New
                                        </button>
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- show success and unsuccess message -->
                        @include('layouts.error_display')
                        <!-- End show success and unsuccess message -->

                        <div class="card-body">
                            <div class="table-responsive export-table">
                                <table class="table table-bordered text-nowrap border-bottom w-100" id="custom_export_datatable">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Emp Code</th>
                                        <th class="text-white">Emp Name</th>
                                        <th class="text-white">Email</th>
                                        <th class="text-white">Phone Number</th>
                                        <th class="text-white">Department</th>
                                        <th class="text-white">Designation</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list-data">
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $employee->user->employee_code }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($employee->avatar == '')
                                                        <img src="{{ asset(config('constants.uploadPaths.defaultProfilePic')) }}" alt="profile-user" class="avatar  profile-user brround cover-image">
                                                    @else
                                                        <img src="{{ asset(config('constants.uploadPaths.profilePic').$employee->avatar) }}" alt="pic" class="avatar  profile-user brround cover-image">
                                                    @endif
                                                    <span class="ms-2"> {{ $employee->first_name }} {{ $employee->last_name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->mobile_number }}</td>
                                            <td>{{ $employee->department->name }}</td>
                                            <td>{{ $employee->designation->name }}</td>
                                            <td>
                                                <a href="{{ route('employees.edit',$employee->id) }}">
                                                    <button class="btn btn-secondary">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
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

@include('layouts/export_datatable')

@section('script')
    <script>
        $(function(e) {
            //______File-Export Data Table
            var table = $('#custom_export_datatable').DataTable({
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
                .appendTo('#custom_export_datatable_wrapper .col-md-6:eq(0)');

        });
    </script>
@endsection

