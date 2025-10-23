<div class="row" style="margin-top: 16px">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead class="bg-primary">
            <tr class="text-center">
                <th>{{__('file.Serial')}}</th>
                <th>{{__('file.Expense Item')}}</th>
                <th>{{__('file.Expense Cost')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expenses as $key => $value)
            <tr class="text-center">
                <td>{{$loop->index+1}}</td>
                <td>
                   {{$value->name}}
                   <input type="hidden" id="expense_{{$key}}_expense_id" name="expense[{{$key}}][expense_id]" value="{{$value->id}}"/>
                </td>
                <td>
                    <input type="text" class="form-control expense_cost input" id="expense_{{$key}}_expense_cost" name="expense[{{$key}}][expense_cost]" data-type="expenseCost" required/>
                </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"><button type="button" class="btn btn-primary btn-block">{{__('Total')}}</button></td>
                <td><input type="text" class="bg-primary text-white form-control" id = "total_expense_show" readonly/></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-3"><button type="button" class="btn btn-secondary btn-block" onclick="show_form(1)"><i class="fas fa-arrow-circle-left"></i>{{__('file.Previous')}}</button></div>
    <div class="col-md-6"></div>
    <div class="col-md-3"><button type="button" class="btn btn-primary btn-block next_button" data-wizard-type="action-next" data-type="expense">{{__('file.Next')}}{{' '}}<i class="fas fa-arrow-circle-right"></i></button></div>
</div>
