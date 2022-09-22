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
     * Показ всех не согласованных источников шума, с пагинацией
     *
     * @return View
     */
    public function index(): View
    {
        $noiseSourcesNotCheck = $this->noiseSourceRepository->getAllNotCheck();
        $arrayRowSpan = [];
        $array = [$noiseSourcesNotCheck[0]->id_file_path];
        $i = 0;
        foreach ($noiseSourcesNotCheck as $item) {
            if ($array[0] === $item->id_file_path) {
                $i++;
            } else {
                $arrayRowSpan[] = $i;
                $array = [$item->id_file_path];
                $i = 1;
            }
        }
        $arrayRowSpan[] = $i;
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
    public function update(NoiseSourceUpdateRequest $request, $id)
    {
        $noiseSource = $this->noiseSourceRepository->getEdit($id);

        if (empty($noiseSource)) {
            return back()->withErrors(['msg' => "Запись с id=[{$id}] для ИШ не найдена."])->withInput();
        }
        $fileNoiseSource = $this->fileNoiseSourceRepository->getFileNoiseSources($noiseSource->id_file_path);
        if (empty($fileNoiseSource)) {
            return back()->withErrors(['msg' => "Запись с id=[{$noiseSource->id_file_path}] для описания не найдена."])->withInput();
        }

        $data = $request->all();

        $result1 = $noiseSource->update($data);
        $result2 = $fileNoiseSource->update($data);

        if ($result1 && $result2) {
            return redirect()->route('noise.admin.sources.edit', $noiseSource->id)->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => "Ошибка сохранения."])->withInput();
        }
    }

    /**
     * Удаление ИШ из БД
     *
     * @param int $id_file_sources
     * @return RedirectResponse
     */
    public function destroy(int $id_file_sources)
    {
        try {
            DB::beginTransaction();
            $this->noiseSourceRepository->deleteNoiseSources($id_file_sources);
            $nameFile = ($this->fileNoiseSourceRepository->getFileNoiseSources($id_file_sources))->file_name;
            $oldPathWithName = PATH_FILES_NOT_CHECK . $nameFile;
            $this->fileNoiseSourceRepository->deleteFileNoiseSources($id_file_sources);
            if (Storage::disk()->exists($oldPathWithName)) {
                Storage::disk()->delete($oldPathWithName);
            } else {
                throw new Exception('Ошибка удаления');
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
     */
    public function approve(int $id_file_sources)
    {
        try {
            DB::beginTransaction();
            $this->noiseSourceRepository->approveNoiseSources($id_file_sources);

            $nameFile = ($this->fileNoiseSourceRepository->getFileNoiseSources($id_file_sources))->file_name;
            $oldPathWithName = PATH_FILES_NOT_CHECK . $nameFile;
            if (Storage::disk()->exists($oldPathWithName)) {
                $newPathWithName = PATH_FILES_CHECK . $nameFile;
                Storage::move($oldPathWithName, $newPathWithName);
            } else {
                throw new Exception('Файл источника шума не найден');
            }

            DB::commit();
            return redirect()->route('noise.admin.sources.index')->with(['success' => 'Успешно согласовано']);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->withErrors(['msg' => $exception->getMessage()]);
        }
    }
}
