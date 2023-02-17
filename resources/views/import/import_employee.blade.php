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
                                <h3 class="card-title">Employee/Import</h3>
                            </div>

                            <!-- show success and unsuccess message -->
                            @include('layouts.error_display')
                            <!-- End show success and unsuccess message -->

                            <form method="post" action="{{ route('imports.store') }}" id="importform" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body pb-2">
                                <div class="col-lg">
                                    <label class="form-label">Upload Excel Sheet</label>
                                    <input type="file" name="file" class="custom-file-input" id="customFile">
                                    @if ($errors->has('file'))
                                    <span class="text-danger">{{ $errors->first('file') }}</span>
                                    @endif
                                </div><br>
                                <div class="col-lg">
                                   <a href="{{asset('/import/import.xlsx')}}" target="blanck">Download Template</a>
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

@endsection
