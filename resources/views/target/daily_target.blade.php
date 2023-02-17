<table class="table table-bordered text-nowrap border-bottom data-table">
    <thead class="bg-primary">
    <tr>
        <th class="text-white">Sr.</th>
        <th class="text-white">Emp Code</th>
        <th class="text-white">Emp Name</th>
        <th class="text-white">Date</th>
        <th class="text-white">Target</th>
{{--        <th class="text-white">Action</th>--}}
    </tr>
    </thead>
    <tbody class="list-data">
    @foreach($targets as $target)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $target->user->employee_code }}</td>
            <td>{{ $target->user->name }}</td>
            <td>{{ $target->date }}</td>
            <td>{{ $target->target }}</td>
{{--            <td>--}}
{{--                --}}{{--                <a href="{{ route('target.edit', $target->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>--}}
{{--            </td>--}}
        </tr>
    @endforeach
    </tbody>
    <!-- filter list  -->
    <tbody class="filter-data">
    <tr>
        <th>Sr.</th>
        <th>Emp Code</th>
        <th>Emp Name</th>
        <th>Date</th>
        <th>Target</th>
{{--        <th>Action</th>--}}
    </tr>
    </tbody>
    <!-- end filter list -->
</table>
