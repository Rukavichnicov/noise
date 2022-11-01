<div class="row justify-content-center mt-2">
    <div class="col-md-12">
            <form method="get" action="{{ route('noise.main.sources.search') }}">
                @csrf
                <div class="row gx-0 mb-2">
                    <div class="col-10 gx-0">
                        <input class="form-control" type="search" name="search" placeholder="Поиск"
                               @if(Request::route()->getName() !== 'noise.main.sources.index')
                               value="{{ request()->search }}"
                               @endif
                        >
                    </div>
                    <div class="col-1 gx-0">
                        <input class="form-control bg-light text-dark" type="submit" value="Поиск" name="submit_search">
                    </div>
                    <div class="col-1 gx-0">
                        <input formaction="{{ route('noise.main.sources.index') }}" class="form-control bg-light text-dark" type="submit" value="Сброс" name="reset">
                    </div>
                </div>
            </form>
    </div>
</div>
