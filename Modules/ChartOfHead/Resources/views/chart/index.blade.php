@extends('layouts.app')
@section('title', $page_title)
@section('content')
    @php
    use Modules\ChartOfHead\Entities\Head;
    use Illuminate\Support\Str;
    @endphp
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8 text-left">
                            <div id="tree">
                                <div class="branch">
                                    @foreach(MASTER_HEAD_VALUE as $key => $value)
                                    <div class="entry"><span class="text-primary" data-toggle="tooltip" data-placement="top" title="{{$value}}"><b>{{ Str::limit($value,15) }}</b></span>
                                        <div class="branch">
                                            @foreach(Head::with('subHead','subHead.childHead')->where(['master_head' => $key , 'type' => 1 ])->get() as $head)
                                            <div class="entry"><span class="text-success" data-toggle="tooltip" data-placement="top" title="{{$head->name}}"><b>{{ Str::limit($head->name,15) }}</b></span>
                                                <div class="branch">
                                                    @foreach($head->subHead as $subHead)
                                                    <div class="entry"><span class="text-muted" data-toggle="tooltip" data-placement="top" title="{{$subHead->name}}"><b>{{ Str::limit($subHead->name,15) }}</b></span>
                                                        <div class="branch">
                                                            @foreach($subHead->childHead as $childHead)
                                                            <div class="entry"><span class="text-warning" data-toggle="tooltip" data-placement="top" title="{{$childHead->name}}"><b>{{ Str::limit($childHead->name,15) }}</b></span></div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                      <div class="col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })
    </script>
@endpush
