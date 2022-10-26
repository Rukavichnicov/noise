@extends('layouts.app')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if(empty($paginator->total()))
                            <p class="lead text-center">Вы пока не добавили в список ни одного источника шума.
                                Чтобы добавить, зайдите на вкладку "Источники шума".</p>
                        @else
                            @include('noise.main.includes.table_sources_users')
                            @include('noise.main.includes.buttons_word_and_zip')
                        @endif
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


