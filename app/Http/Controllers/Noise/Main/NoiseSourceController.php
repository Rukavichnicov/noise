<?php

namespace App\Http\Controllers\Noise\Main;

use App\Http\Requests\NoiseSourceCreateRequest;
use App\Http\Requests\NoiseSourceIndexRequest;
use App\Http\Requests\NoiseSourceSearchRequest;
use App\Repositories\FileNoiseSourceRepository;
use App\Repositories\NoiseSourceRepository;
use App\Repositories\TypeNoiseSourceRepository;
use App\Services\UrlForSorting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NoiseSourceController extends MainController
{
    /**
     * @var NoiseSourceRepository
     */
    private NoiseSourceRepository $noiseSourceRepository;

    /**
     * @var TypeNoiseSourceRepository
     */
    private TypeNoiseSourceRepository $typeNoiseSourceRepository;

    /**
     * @var FileNoiseSourceRepository
     */
    private FileNoiseSourceRepository $fileNoiseSourceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->noiseSourceRepository = app(NoiseSourceRepository::class);
        $this->typeNoiseSourceRepository = app(TypeNoiseSourceRepository::class);
        $this->fileNoiseSourceRepository = app(FileNoiseSourceRepository::class);
    }

    /**
     * Показ всех согласованных источников шума, с пагинацией
     *
     * @param NoiseSourceIndexRequest $request
     * @param UrlForSorting $urlForSorting
     * @return View
     */
    public function index(NoiseSourceIndexRequest $request, UrlForSorting $urlForSorting): View
    {
        $sortField = empty($request->field) ? 'name' : $request->field;
        $sortDirection = empty($request->direction) ? 'ASC' : $request->direction;
        $paginator = $this->noiseSourceRepository->getAllWithPaginate(10, $sortField, $sortDirection);
        return view('noise.main.index', compact('paginator', 'urlForSorting'));
    }

    /**
     * Показ формы для создания источников шума
     *
     * @return View
     */
    public function create(): View
    {
        if (isset($_GET['severalSources']) && $_GET['severalSources'] > 30) {
            $_GET['severalSources'] = 30;
        }
        $count = $_GET['severalSources'] ?? 1;
        $typeList = $this->typeNoiseSourceRepository->getListCategories();
        return view('noise.main.create', compact('count', 'typeList'));
    }

    /**
     * Сохранение в БД созданных источников шума и имя файла, а также сохранение файла в папке
     *
     * @param NoiseSourceCreateRequest $request
     * @return RedirectResponse
     */
    public function store(NoiseSourceCreateRequest $request): RedirectResponse
    {
        try {
            $downloadableFileNoiseSource = $request->file('file_name');
            $arrayInput = $request->input();

            DB::beginTransaction();
            $idFileNoiseSource = $this->fileNoiseSourceRepository->saveFileNoiseSourceBD($downloadableFileNoiseSource, $arrayInput);
            for ($i = 1; $i <= $arrayInput['count']; $i++) {
                $this->noiseSourceRepository->saveOneModelBD($i, $arrayInput, $idFileNoiseSource);
            }
            $downloadableFileNoiseSource->store(PATH_FILES_NOT_CHECK);
            DB::commit();

            return redirect()->route('noise.main.sources.index')
                             ->with(['success' => 'Успешно сохранено, после проверки администратора ваш источник появиться в базе данных']);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    /**
     * Поиск источника шума по запросу
     *
     * @param NoiseSourceSearchRequest $request
     * @param UrlForSorting $urlForSorting
     * @return View
     */
    public function search(NoiseSourceSearchRequest $request, UrlForSorting $urlForSorting): View
    {
        $strSearch = $request->search;
        $sortField = empty($request->field) ? 'name' : $request->field;
        $sortDirection = empty($request->direction) ? 'ASC' : $request->direction;
        $paginator = $this->noiseSourceRepository->getFoundWithPaginate(10, $strSearch, $sortField, $sortDirection);
        return view('noise.main.index', compact('paginator', 'urlForSorting'));
    }
}
