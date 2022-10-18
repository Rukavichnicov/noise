@extends('layouts.app')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')
        @include('noise.includes.search')

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('noise.main.includes.table_sources_all')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        @if($paginator->total() > $paginator->count())
            <br>
            <div class="d-flex justify-content-center">
                {{ $paginator->appends(['search' => request()->search])->links() }}
            </div>
        @endif
    </div>
@endsection


