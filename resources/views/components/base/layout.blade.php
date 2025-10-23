<x-base.mobile-header/>
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-row flex-column-fluid page">
        <x-base.sidebar/>
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            <x-base.header/>
            <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                @include('layouts.includes.sub-header')
                @yield('content')
            </div>
            <x-base.footer/>
        </div>
    </div>
</div>
<x-base.user-profile/>
<x-base.scroll-top/>
@include('layouts.includes.scripts')
