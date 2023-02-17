@extends('layouts.app')

@push('style-scripts')
@endPush

@section('content')
    <div class="app-content">
        <div class="side-app">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div class="row col-lg-16 col-md-12">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Employee/Create</h3>
                            </div>

                            <!-- show success and unsuccess message -->
                            @include('layouts.error_display')
                            <!-- End show success and unsuccess message -->

                            <form method="post" action="{{ route('employees.store') }}" id="myform" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body pb-2">
                                    <div class="row row-sm">
                                        <div class="col-lg">
                                            <label class="form-label">First Name<span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control mb-4 @error('first_name') is-invalid @enderror" placeholder="Enter Name" id="first_name" required>
                                            @if ($errors->has('first_name'))
                                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-lg">
                                            <label class="form-label">Last Name<span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control mb-4 @error('last_name') is-invalid @enderror" placeholder="last name" id="last_name" required>
                                            @if ($errors->has('last_name'))
                                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row row-sm">
                                        <div class="col-lg">
                                            <label class="form-label">Email<span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control mb-4 @error('email') is-invalid @enderror" placeholder="email" id="email" required>
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-lg">
                                            <label class="form-label">Employee Code<span class="text-danger">*</span></label>
                                            <input type="text" name="employee_code" value="{{ old('employee_code') }}" class="form-control mb-4 @error('employee_code') is-invalid @enderror" placeholder="Employee Code" id="employee_code" required>
                                            @if ($errors->has('employee_code'))
                                                <span class="text-danger">{{ $errors->first('employee_code') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row row-sm">
                                        <div class="col-lg">
                                            <label class="form-label">Designation<span class="text-danger">*</span></label>
                                            <select name="designation" id="designation" class="form-control custom-control select2" required>
                                                <option value="">Select Designation</option>
                                                @foreach($designations as $designation)
                                                    <option value="{{$designation->id}}">{{$designation->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('designation'))
                                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-lg">
                                            <label class="form-label">Department<span class="text-danger">*</span></label>
                                            <select name="department" id="department" class="form-control custom-control select2" required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('department'))
                                                <span class="text-danger">{{ $errors->first('department') }}</span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="row row-sm">

                                        <div class="col-lg">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <select name="state" id="state" class="form-control custom-control select2" required>
                                                <option value="">Select State</option>
                                                @foreach($states as  $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('state'))
                                                <span class="text-danger">{{ $errors->first('state') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-lg">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <select name="city" id="city" class="form-control custom-control select2" required>
                                                <option value="">Select City</option>
                                            </select>
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row row-sm">
                                        <div class="col-lg">
                                            <label class="form-label">Mobile No<span class="text-danger">*</span></label>
                                            <input type="number" name="mobile_no" value="{{ old('mobile_no') }}" class="form-control mb-4 @error('mobile_no') is-invalid @enderror" placeholder="Enter mobile no" id="mobile_no" required>
                                            @if ($errors->has('mobile_no'))
                                                <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-lg">
                                            <label class="form-label">Profile Picture</label>
                                            <input type="file" name="avatar" value="{{ old('avatar') }}" class="form-control mb-4 @error('avatar') is-invalid @enderror" placeholder="Enter Name" id="avatar">
                                            @if ($errors->has('avatar'))
                                                <span class="text-danger">{{ $errors->first('avatar') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row row-sm">
                                        <div class="col-lg">
                                            <label class="form-label">Role<span class="text-danger">*</span></label>
                                            <select name="role" id="role" class="form-control custom-control select2" required>
                                                <option value="">-- Select Role --</option>
                                                <option value="{{ config('constants.user_roles.employee') }}">Employee</option>
                                                <option value="{{ config('constants.user_roles.manager') }}">Manager</option>
                                            </select>
                                            @if ($errors->has('role'))
                                                <span class="text-danger">{{ $errors->first('role') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-lg" id="managers">
                                            <label class="form-label">Manager<span class="text-danger">*</span></label>
                                            <select name="manager_id" id="manager_id" class="form-control custom-control select2">
                                                <option value="">-- Select Manager --</option>
                                                @foreach($managers as $manager)
                                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('role'))
                                                <span class="text-danger">{{ $errors->first('role') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="mb-0 mt-4 text-center">
                                        <button type="submit" name="insert" value="insert" class="btn btn-secondary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGE-HEADER END -->
    </div>
@endsection

@push('js-scripts')
@endPush

@section('script')
    <script>
        $(document).ready(function() {
            $("#managers").hide();
            $('.select2').select2();
        });


        $("#state").on("change", function(e) {
            const stateId = $(this).val();
            let url = '{{ route("states.cities", ":stateId") }}';
            url = url.replace(':stateId', stateId)
            // const url = `master/states/${stateId}/cities`;
            $.ajax({
                type: 'get',
                url: url,
                data: {
                    // project_id: project_id,
                },

                success: function (data) {
                    const cities = data.data;

                    if(cities.length > 0)
                    {
                        let cityOptions = "<option value='' selected>-- Select City --</option>";
                        $.each(cities, function(v) {
                            let val = cities[v]
                            cityOptions += `<option value="${val['id']}"> ${val['name']}</option>`;
                        });
                        $('#city').html(cityOptions);
                    }else{
                        let cityOptions = '<option value="" selected>-- City not found --</option>';
                        $('#city').html(cityOptions);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });

        })


        $("#role").on("change", function(e) {
            const role = $(this).val();
            const managerRole = {{ config('constants.user_roles.employee') }};
            $("#managers").hide();
            if(role == managerRole){
                $("#managers").show();
            }
        })
    </script>
@endsection
