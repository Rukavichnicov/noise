<div class="row justify-content-center mt-2">
    <div class="col-md-12">
            <form method="get" action="{{ route('noise.main.sources.search') }}">
                <div class="row gx-0 mb-2">
                    <div class="col-10 gx-0">
                        <input class="form-control" type="search" name="search" placeholder="Поиск"
                               value="{{ request()->search }}">
                    </div>
                    <div class="col-2 gx-0">
                        <input class="form-control bg-light text-dark" type="submit" value="Поиск" name="submit_search">
                    </div>
                </div>
            </form>
    </div>
</div>
