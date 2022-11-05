@extends('layouts.app')

@section('title', 'Добавить источник шума')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')
            @if (!isset($_GET['severalSources']))
                @include('noise.main.precreate')
            @else
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('noise.main.sources.store') }}">
                            @csrf
                            <input type="hidden" name="count" value="{{ $count }}">
                            @for($i = 1; $i <= $count; $i++)
                                @include('noise.main.includes.data_single_noise_source')
                            @endfor
                            <label class="form-label">Обоснование шумовой характеристики (общее для всех)*: </label>
                            <textarea class="form-control" name="foundation" maxlength="1000" required>{{ old('foundation') }}</textarea>
                            <label class="form-label">Загрузите pdf файл обосновывающих шумовые характеристики (общий для
                                всех):* </label>
                            <input class="form-control" type="file" name="file_name" accept=".pdf" required>
                            <p><small>* - Поле обязательно для заполнения</small></p>
                            <input type="submit" class="form-control btn btn-primary" value="Добавить в базу данных" name="submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
          @endif
    </div>
@endsection


