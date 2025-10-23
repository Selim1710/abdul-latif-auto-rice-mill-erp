<table id="dataTable" class="table table-bordered table-hover">
    <thead class="bg-primary">
        <tr>
            <th>{{ __('file.Voucher No') }}</th>
            <th>{{ __('file.Voucher Date') }}</th>
            <th>{{ __('file.Voucher Type') }}</th>
            <th>{{ __('file.Debit') }}</th>
            <th>{{ __('file.Credit') }}</th>
            <th>{{ __('file.Description') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $value)
            <tr>
                <td>{{ $value->voucher_no }}</td>
                <td>{{ $value->date }}</td>
                <td>{{ $value->voucher_type }}</td>
                <td>{{ $value->debit }}</td>
                <td>{{ $value->credit }}</td>
                <td>{{ $value->narration }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-red">
                    No data Available
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
