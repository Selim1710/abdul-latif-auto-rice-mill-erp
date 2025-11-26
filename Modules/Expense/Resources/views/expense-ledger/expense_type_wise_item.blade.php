  <option value="">{{ __('file.Please Select') }}</option>
  @foreach ($expenseItems as $item)
      <option value="{{ $item->id }}">{{ $item->name }}</option>
  @endforeach
