<div class="col-md-8">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr><td width="30%"><b>{{__('file.Tag')}}</b></td><td><b>:</b></td><td>{{ $asset->tag }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Name')}}</b></td><td><b>:</b></td><td>{{ $asset->name }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Type')}}</b></td><td><b>:</b></td><td>{{ $asset->asset_type->name }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Cost')}}</b></td><td><b>:</b></td><td>{{ number_format($asset->cost,2)}}</td></tr>
            <tr><td width="30%"><b>{{__('file.Purchase Date')}}</b></td><td><b>:</b></td><td>{{ $asset->purchase_date ? date('j F, Y',strtotime($asset->purchase_date)) : '' }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Warranty')}} ({{__('file.Months')}})</b></td><td><b>:</b></td><td>{!! $asset->warranty  !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Asset User')}}</b></td><td><b>:</b></td><td>{!! $asset->user !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Location')}}</b></td><td><b>:</b></td><td>{!! $asset->location !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Asset Condition')}}</b></td><td><b>:</b></td><td>{{  ASSET_STATUS[$asset->status] }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Description')}}</b></td><td><b>:</b></td><td>{!! $asset->description !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Created By')}}</b></td><td><b>:</b></td><td>{{  $asset->created_by  }}</td></tr>
           <tr><td width="30%"><b>{{__('file.Create Date')}}</b></td><td><b>:</b></td><td>{{  $asset->created_at ? date(config('settings.date_format'),strtotime($asset->created_at)) : ''  }}</td></tr>
           @if($asset->modified_by)<tr><td width="30%"><b>{{__('file.Modified By')}}</b></td><td><b>:</b></td><td>{{  $asset->modified_by  }}</td></tr>@endif
           @if($asset->modified_by)<tr><td width="30%"><b>{{__('file.Modified Date')}}</b></td><td><b>:</b></td><td>{{  $asset->updated_at ? date(config('settings.date_format'),strtotime($asset->updated_at)) : ''  }}</td></tr>@endif
        </table>
    </div>
</div>
<div class="col-md-4 text-center">
    @if($asset->photo)
        <img src='storage/{{ ASSET_PHOTO_PATH.$asset->photo }}' alt='{{ $asset->name }}' style='width:200px;'/>
    @endif
</div>
