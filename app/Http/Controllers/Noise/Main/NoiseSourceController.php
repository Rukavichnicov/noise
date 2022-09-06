<?php

namespace App\Http\Controllers\Noise\Main;

use App\Models\FileNoiseSource;
use App\Models\NoiseSource;
use App\Models\TypeNoiseSource;
use App\Repositories\NoiseSourceRepository;
use App\Repositories\TypeNoiseSourceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function __construct()
    {
        parent::__construct();
        $this->noiseSourceRepository = app(NoiseSourceRepository::class);
        $this->typeNoiseSourceRepository = app(TypeNoiseSourceRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->noiseSourceRepository->getAllWithPaginate(10);
        return view('noise.main.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (isset($_GET['severalSources']) && $_GET['severalSources'] > 30) {
            $_GET['severalSources'] = 30;
        }
        $count = $_GET['severalSources'] ?? 1;

        $typeList = $this->typeNoiseSourceRepository->getListCategories();

        return view('noise.main.create', compact('count', 'typeList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $downloadable_file_noise_source = $request->file('file_name');
        $file_noise_source = new FileNoiseSource();
        $file_noise_source->file_name = $downloadable_file_noise_source->getClientOriginalName();
        $file_noise_source->save();
        $downloadable_file_noise_source->store('resources/file_sources/not_check');
        $i = 1;

        $array = $request->input();
        for ($i = 1; $i <= $array['count']; $i++) {
            $item = new NoiseSource;
            $item->name = $array['name_' . $i];
            $item->mark = $array['mark_' . $i];
            $item->distance = $array['distance_' . $i];
            $item->la_31_5 = $array['la_31_5_' . $i];
            $item->la_63 = $array['la_63_' . $i];
            $item->la_125 = $array['la_125_' . $i];
            $item->la_250 = $array['la_250_' . $i];
            $item->la_500 = $array['la_500_' . $i];
            $item->la_1000 = $array['la_1000_' . $i];
            $item->la_2000 = $array['la_2000_' . $i];
            $item->la_4000 = $array['la_4000_' . $i];
            $item->la_8000 = $array['la_8000_' . $i];
            $item->la_eq = $array['la_eq_' . $i];
            $item->la_max = $array['la_max_' . $i];
            $item->foundation = $array['foundation'];
            $item->remark = $array['remark_' . $i];
            $item->id_file_path = $file_noise_source->id;
            $item->id_type_of_source = $array['id_type_of_source_' . $i];
            $item->id_user = Auth::id();
            $item->save();
        }
        if ($item) {
            return redirect()->route('noise.main.sources.index')
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }
}
