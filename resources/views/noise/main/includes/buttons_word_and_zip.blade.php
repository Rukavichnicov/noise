<div class="row">
    <div class="col">
        <form action="{{ route('noise.main.basket.downloadReport') }}" method="get">
            @csrf
            <input type="submit"
                   class="form-control btn btn-primary"
                   name="downloadWord"
                   value="Выгрузить список в Word">
        </form>
    </div>
    <div class="col">
        <form action="{{ route('noise.main.basket.downloadArchiveFile') }}" method="get">
            @csrf
            <input type="submit"
                   class="form-control  btn btn-primary"
                   name="downloadZip"
                   value="Скачать общий архив">
        </form>
    </div>
</div>
