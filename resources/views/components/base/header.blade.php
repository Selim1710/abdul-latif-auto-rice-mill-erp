<div id="kt_header" class="header  header-fixed ">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            <div class="dropdown show">
                <a class="dropdown-toggle text-white text-uppercase" href="#" style="color:#000 !important" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flag-icon {{ App::getLocale() == 'bn' ? 'flag-icon-bd' : 'flag-icon-us' }} margin-right-8px"></span> {{ Config::get('languages')[App::getLocale()] }}</a>
                <div class="dropdown-menu text-small text-uppercase" aria-labelledby="dropdownMenuLink">
                    @foreach (Config::get('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                            <a href="{{url('language_switch',$lang)}}" style="color:#000 !important" class="dropdown-item" ><i class="flag-icon {{ $lang == 'bn' ? 'flag-icon-bd' : 'flag-icon-us' }} margin-right-8px"></i>{{ $language }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="topbar">
{{--            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)--}}
{{--            <div class="dropdown">--}}
{{--                <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">--}}
{{--                    <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">--}}
{{--                        <span class="svg-icon svg-icon-xl svg-icon-primary">--}}
{{--                            <i class="fas fa-bell text-primary"></i>--}}
{{--                            @if (Auth::user()->unreadNotifications->count() > 0)--}}
{{--                            <span class="badge badge-danger text-white" style="position: absolute;top:0;right:0;">{{ Auth::user()->unreadNotifications->count() }}</span>--}}
{{--                            @endif--}}
{{--                        </span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div  class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-xl dropdown-menu-anim-up">--}}
{{--                    <form>--}}
{{--                        <div class="d-flex align-items-center py-10 px-8 bgi-size-cover bgi-no-repeat rounded-top bg-primary">--}}
{{--                            <span class="btn btn-md btn-icon bg-white-o-15 mr-4"><i class="flaticon2-shopping-cart-1 text-success"></i></span>--}}
{{--                            <h4 class="text-white m-0 flex-grow-1 mr-3">{{__('file.Notification')}}</h4>--}}
{{--                            <button type="button" class="btn btn-success btn-sm mr-2">{{ Auth::user()->unreadNotifications->count() }}</button>--}}
{{--                        </div>--}}
{{--                        <div class="scroll pr-7 mr-n7 ps ps--active-y" data-scroll="true" data-height="250" data-mobile-height="200">--}}
{{--                            @if (Auth::user()->unreadNotifications->count() > 0)--}}
{{--                                @foreach (Auth::user()->unreadNotifications as $key => $item)--}}
{{--                                <div class="d-flex align-items-center m-5 px-2">--}}
{{--                                    <div class="symbol symbol-40 symbol-light-primary mr-5"><span class="symbol-label"><span class="svg-icon svg-icon-lg svg-icon-primary">{{ $key+1 }}</span></span></div>--}}
{{--                                    <div class="d-flex flex-column font-weight-bold"><span style="font-size: 10px;">{{ $item->data['message'] }}</span></div>--}}
{{--                                </div>--}}
{{--                                @if (Auth::user()->unreadNotifications->count() != ($key+1))--}}
{{--                                <div class="separator separator-solid"></div>--}}
{{--                                @endif--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                            <div class="p-8 text-center font-weight-bolder">--}}
{{--                            {{__('file.All caught up')}}!--}}
{{--                            <br>{{__('file.No new notifications')}}.--}}
{{--                            </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                        @if (Auth::user()->unreadNotifications->count() > 0)--}}
{{--                        <div class="p-5 d-flex align-items-center justify-content-between">--}}
{{--                            <a href="{{route('mark.all.read')}}" class="btn btn-sm btn-primary  "><b>{{__('file.Mark All As Read')}}</b></a>--}}
{{--                            <a href="{{route('delete.all.notification')}}" class="btn btn-sm btn-danger "><b>{{__('file.Delete All')}}</b></a>--}}
{{--                        </div>--}}
{{--                        @endif--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @endif--}}
            <div class="topbar-item">
                <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">{{__('file.Hi')}},</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ Auth::user()->username }}</span>
                    <span class="symbol symbol-35 symbol-light-success"><img class="header-profile-user" src="{{asset('images/profile.svg')}}" alt="Header Avatar"></span>
                </div>
            </div>
        </div>
    </div>
</div>
