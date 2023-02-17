<table class="table table-bordered text-nowrap border-bottom data-table">
    <thead class="bg-primary">
    <tr>
        <th class="text-white">Sr.</th>
        <th class="text-white">Emp Code</th>
        <th class="text-white">Emp Name</th>
        <th class="text-white">Target</th>
        <th class="text-white">Achieved</th>
        <th class="text-white">Action</th>
    </tr>
    </thead>
    <tbody class="list-data">
    @foreach($targets as $target)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $target->user->employee_code }}</td>
            <td>{{ $target->user->name }}</td>
            <td>{{ $target->target }}</td>
            <td>{{ $target->achieve_target }}</td>
            <td>
                <button type="submit"  class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editTargetModal{{ $target->id }}">
                    <i class="fa fa-edit"></i> Edit</button>

                <form method="post" action="{{ route('targets.destroy', $target->id) }}" style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"  class="btn btn-danger">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </form>
            </td>

            {{-- Edit Target modal --}}
            <div class="modal fade" id="editTargetModal{{ $target->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('targets.update', $target->id) }}">
                            @csrf
                            @method('patch')
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
                                            @for($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}" {{ $target->year == $year ? "selected" : "" }}>{{ $year }}</option>
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
                                            @for($month = 1; $month <= 12; $month++)
                                                <option value="{{ date('m', mktime(0,0,0,$month,1)) }}" {{ $target->month == $month ? "selected" : "" }}>
                                                    {{ date('F',mktime(0,0,0,$month,1)) }}
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
                                                <option value="{{ $user->id }}" {{ $target->user_id == $user->id ? "selected" : "" }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('user_id'))
                                            <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Target<span class="text-danger">*</span></label>
                                        <input type="number" name="target" class="form-control mb-4 @error('target') is-invalid @enderror"  id="target" value="{{ $target->target }}" required>
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

        </tr>
    @endforeach
    </tbody>
    <!-- filter list  -->
    <tbody class="filter-data">
    <tr>
        <th>Sr.</th>
        <th>Emp Code</th>
        <th>Emp Name</th>
        <th>Target</th>
        <th>Achieved</th>
        <th>Action</th>
    </tr>
    </tbody>
    <!-- end filter list -->
</table>
