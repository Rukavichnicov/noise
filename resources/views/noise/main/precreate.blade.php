<div class="row">
    <div class="col">
        <h3>Правила добавления данных об источниках шума</h3>
        <p>Для того чтобы ваши данные отобразились в базе данных, они должны соответствовать следующим критериям:</p>
        <ul>
            <li>
                Шумовые характеристики источника шума должны быть обоснованы паспортом, руководством по эксплуатации,
                ГОСТом, справочником и т.п.
            </li>
            <li>
                Файл обоснования шумовой характеристики должен содержать титульный лист (чтобы было понятно на что идёт
                ссылка) и одну страницу с шумовыми характеристиками.
                Допускается добавлять еще листы с техническими характеристиками оборудования, но не более 3 листов
                дополнительно.
            </li>
            <li>Файл должен быть в формате pdf.</li>
            <li>Размер файла обоснования шумовой характеристики не должен превышать 2 Мб.</li>
            <li>Введенные в форму данные должны соответствовать приложенному файлу обоснования шумовой характеристики.</li>
            <li>Не допускается использование гиперссылок в описании на сторонние ресурсы.</li>
        </ul>
        <div>
        </div>
        <div class="row g-3">
            <div class="col-md">
                <form method="GET" class="row g-3" action="{{ route('noise.main.sources.create') }}">
                    @csrf
                    <div class="col-md-auto">
                        <label for="severalSources" class="form-label">Введите количество источников шума которые
                            описаны на 1 листе</label>
                    </div>
                    <div class="col-md-1">
                        <input type="number" class="form-control" min="1" max="30" name="severalSources"
                               id="severalSources" required>
                    </div>
                    <div class="col-md-1">
                        <input type="submit" value="Добавить" name="submitAdd" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


