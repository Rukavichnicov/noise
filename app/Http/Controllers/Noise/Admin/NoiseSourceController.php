<?php

namespace App\Http\Controllers\Noise\Admin;

use App\Http\Requests\NoiseSourceCreateRequest;
use App\Repositories\FileNoiseSourceRepository;
use App\Repositories\NoiseSourceRepository;
use App\Repositories\TypeNoiseSourceRepository;
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
     * Показ всех не согласованных источников шума, с пагинацией
     *
     * @return View
     */
    public function index(): View
    {
        $paginator = $this->noiseSourceRepository->getAllWithPaginate(30, false);
        return view('noise.admin.check_sources', compact('paginator'));
    }

    /**
     * Показ формы для редактирования источника шума
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd(__METHOD__, $id);
    }

//    /**
//     * Сохранение в БД созданных источников шума и имя файла, а также сохранение файла в папке
//     *
//     * @param NoiseSourceCreateRequest $request
//     * @return RedirectResponse
//     */
//    public function store(NoiseSourceCreateRequest $request): RedirectResponse
//    {
//        try {
//            $downloadableFileNoiseSource = $request->file('file_name');
//            $arrayInput = $request->input();
//
//            DB::beginTransaction();
//            $idFileNoiseSource = $this->fileNoiseSourceRepository->saveFileNoiseSourceBD($downloadableFileNoiseSource);
//            for ($i = 1; $i <= $arrayInput['count']; $i++) {
//                $this->noiseSourceRepository->saveOneModelBD($i, $arrayInput, $idFileNoiseSource);
//            }
//            $downloadableFileNoiseSource->store('resources/file_sources/not_check');
//            DB::commit();
//
//            return redirect()->route('noise.main.sources.index')->with(['success' => 'Успешно сохранено']);
//        } catch (Exception $exception) {
//            DB::rollBack();
//            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
//        }
//    }

    /**
     * Удаление ИШ из БД
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd(__METHOD__, $id);
    }
}
