@php /** @var \Illuminate\Support\ViewErrorBag $errors */ @endphp
@if($errors->any())
    <div class="row justify-content-center mt-2">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <ul>
                    @foreach($errors->all() as $errorsTxt)
                        <li>{{ $errorsTxt }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
@if(session('success'))
    <div class="row justify-content-center mt-2">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session()->get('success') }}
            </div>
        </div>
    </div>
@endif
@guest
    <div class="row justify-content-center mt-2">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                Зарегистрируйтесь или войдите для получения возможности составлять свои списки источников шума,
                выгружать их общим списком, а также выгружать все паспорта для них.
            </div>
        </div>
    </div>
@endguest

