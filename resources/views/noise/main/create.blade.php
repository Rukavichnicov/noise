@php use App\Models\NoiseSource; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('noise.main.sources.store') }}">
                            @csrf
                            @for($i = 1; $i <= $count; $i++)
                            @include('noise.main.includes.data_single_noise_source')
                            @endfor
                            <label class="form-label">Обоснование шумовой характеристики (общее для всех)*: </label>
                            <textarea class="form-control" name="foundation" required></textarea>
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
    </div>
@endsection


