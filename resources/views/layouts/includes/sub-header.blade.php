<div class="subheader py-2  subheader-solid " id="kt_subheader">
    <div  class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-right flex-wrap mr-1">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"> {{ $page_title }}</h5>
        </div>
        <div class="d-flex align-items-center pt-4">
            <ol class="breadcrumb float-right pull-right">
                <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i>{{ $page_title }}</a></li>
                @if (!empty($breadcrumb))
                    @foreach ($breadcrumb as $item)
                        @if(!isset($item['link']))
                        <li class="active">{{ $item['name'] }}</li>
                        @else
                        <li><a href="{{ $item['link'] }}">{{ $item['name'] }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ol>
        </div>
    </div>
</div>
