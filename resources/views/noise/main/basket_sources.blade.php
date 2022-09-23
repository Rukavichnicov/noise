@extends('layouts.app')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')
        <t1>Корзина!!!</t1>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('noise.main.includes.table_sources')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        @if($paginator->total() > $paginator->count())
            <br>
            <div class="d-flex justify-content-center">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>
@endsection


