<?php

namespace App\Http\Controllers\Noise\Admin;

use App\Http\Requests\NoiseSourceUpdateRequest;
use App\Repositories\FileNoiseSourceRepository;
use App\Repositories\NoiseSourceRepository;
use App\Repositories\TypeNoiseSourceRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
     * Показ всех не согласованных источников шума
     *
     * @return View
     */
    public function index(): View
    {
        $noiseSourcesNotCheck = $this->noiseSourceRepository->getAllNotCheck();
        $arrayRowSpan = $noiseSourcesNotCheck->countBy('id_file_path')->values();

        return view('noise.admin.check_sources', compact('noiseSourcesNotCheck', 'arrayRowSpan'));
    }

    /**
     * Показ формы для редактирования источника шума
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $item = $this->noiseSourceRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }
        $typeList = $this->typeNoiseSourceRepository->getListCategories();
        return view('noise.admin.edit', compact('item','typeList'));
    }

    /**
     * Обновляет информацию об указанном источнике шума
     *
     * @param  NoiseSourceUpdateRequest  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(NoiseSourceUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $noiseSource = $this->noiseSourceRepository->getEdit($id);
            if (empty($noiseSource)) {
                throw new Exception("Запись с id=[$id] для ИШ не найдена.");
            }

            $fileNoiseSource = $this->fileNoiseSourceRepository->getFileNoiseSources($noiseSource->id_file_path);
            if (empty($fileNoiseSource)) {
                throw new Exception("Запись с id=[$noiseSource->id_file_path] для описания не найдена.");
            }

            $data = $request->all();

            DB::beginTransaction();
            $isNoiseSourceUpdated = $noiseSource->update($data);
            $isFileNoiseSourceUpdated = $fileNoiseSource->update($data);

            if ($isNoiseSourceUpdated && $isFileNoiseSourceUpdated) {
                DB::commit();
                return redirect()->route('noise.admin.sources.edit', $noiseSource->id)->with(['success' => 'Успешно сохранено']);
            } else {
                throw new Exception('Ошибка обновления записи в базе данных.');
            }
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->withErrors(['msg' => $exception->getMessage()])->withInput();
        }
    }

    /**
     * Удаление ИШ из БД
     *
     * @param int $idFileSources
     * @return RedirectResponse
     */
    public function destroy(int $idFileSources): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->noiseSourceRepository->deleteNoiseSources($idFileSources);
            $fileNoiseSources = $this->fileNoiseSourceRepository->getFileNoiseSources($idFileSources);
            if ($fileNoiseSources === null) {
                throw new Exception('Не удалось получить запись с id для файла');
            }
            $nameFile = $fileNoiseSources->file_name;
            $pathWithNameNotChecked = PATH_FILES_NOT_CHECK . $nameFile;
            $this->fileNoiseSourceRepository->deleteFileNoiseSources($idFileSources);
            if (Storage::disk()->exists($pathWithNameNotChecked)) {
                Storage::disk()->delete($pathWithNameNotChecked);
            } else {
                throw new Exception('Ошибка удаления файла в хранилище');
            }

            DB::commit();
            return redirect()->route('noise.admin.sources.index')->with(['success' => 'Успешно удалено']);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->withErrors(['msg' => $exception->getMessage()]);
        }
    }

    /**
     * Согласование источников шума
     *
     * @param int $id_file_sources
     * @return RedirectResponse
     */
    public function approve(int $id_file_sources): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->noiseSourceRepository->approveNoiseSources($id_file_sources);
            $fileNoiseSources = $this->fileNoiseSourceRepository->getFileNoiseSources($id_file_sources);
            if ($fileNoiseSources === null) {
                throw new Exception('Не удалось получить запись с id для файла');
            }
            $nameFile = $fileNoiseSources->file_name;
            $pathWithNameNotChecked = PATH_FILES_NOT_CHECK . $nameFile;
            if (Storage::disk()->exists($pathWithNameNotChecked)) {
                $pathWithNameChecked = PATH_FILES_CHECK . $nameFile;
                Storage::move($pathWithNameNotChecked, $pathWithNameChecked);
            } else {
                throw new Exception('Файл источника шума не найден');
            }

            DB::commit();
            return back()->with(['success' => 'Успешно согласовано']);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->withErrors(['msg' => $exception->getMessage()]);
        }
    }
}
