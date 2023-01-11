@extends('layouts.app')

@section('title', 'Ajax')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="cover-container w-100 h-100 p-3 mx-auto">
                    <main class="px-3">
                        <h1>Try ajax</h1>
                    </main>
                    <form id="get-name" class="form-control" action="{{ route('api.ajax.index') }}" method="get">
                        <label class="form-label">
                            Введите id:
                            <input class="form-control" id="id_noise" name="id_noise" type="number">
                        </label>
                        <input type="submit" name="submit" id="submit" value="Отправить">
                    </form>
                    <p id="name_show"></p>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script>
        $("#get-name").on("submit", function (event) {
            $.ajax({
                url: '{{ route('api.ajax.index') }}',
                method: 'get',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    let nameShow = document.getElementById("name_show");
                    nameShow.innerHTML = 'Имя вашего источника шума: ' + data.noiseSource;
                }
            });
            event.preventDefault();
        });
    </script>
@endsection
