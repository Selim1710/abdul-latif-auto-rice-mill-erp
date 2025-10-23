<div class="col-md-12 text-center">
    @if($user->avatar)
        <img src='storage/{{ USER_PHOTO_PATH.$user->avatar }}' alt='{{ $user->name }}' style='width:200px;'/>
    @else
        <img src='images/{{ $user->gender == 'Male' ? 'male' : 'female' }}.svg' alt='Default Image' style='width:200px;'/>
    @endif
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr><td width="30%"><b>{{__('file.Name')}}</b></td><td><b>:</b></td><td>{{ $user->name }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Username')}}</b></td><td><b>:</b></td><td>{{ $user->username }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Role')}}</b></td><td><b>:</b></td><td>{{ $user->role->role_name }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Phone')}}</b></td><td><b>:</b></td><td>{{ $user->phone }}</td></tr>
            <tr><td width="30%"><b>{{__('file.Email')}}</b></td><td><b>:</b></td><td>{!! $user->email ? $user->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>' !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Gender')}}</b></td><td><b>:</b></td><td>{!! GENDER_LABEL[$user->gender] !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Status')}}</b></td><td><b>:</b></td><td>{!! STATUS_LABEL[$user->status] !!}</td></tr>
            <tr><td width="30%"><b>{{__('file.Created By')}}</b></td><td><b>:</b></td><td>{{  $user->created_by  }}</td></tr>
            @if($user->modified_by)<tr><td width="30%"><b>{{__('file.Modified By')}}</b></td><td><b>:</b></td><td>{{  $user->modified_by  }}</td></tr>@endif
            <tr><td width="30%"><b>{{__('file.Create Date')}}</b></td><td><b>:</b></td><td>{{  $user->created_at ? date(config('settings.date_format'),strtotime($user->created_at)) : ''  }}</td></tr>
            @if($user->modified_by)<tr><td width="30%"><b>{{__('file.Modified Date')}}</b></td><td><b>:</b></td><td>{{  $user->updated_at ? date(config('settings.date_format'),strtotime($user->updated_at)) : ''  }}</td></tr>@endif
            <tr><td width="30%"><b>{{__('file.Account Deletable')}}</b></td><td><b>:</b></td><td>{!! DELETABLE_LABEL[$user->deletable] !!}</td></tr>
    </div>
</div>
