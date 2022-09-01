<?php

namespace App\Http\Controllers\Noise\Main;

use App\Models\NoiseSource;
use App\Models\TypeNoiseSource;
use App\Repositories\NoiseSourceRepository;
use Illuminate\Http\Request;

class NoiseSourceController extends MainController
{
    /**
     * @var NoiseSourceRepository
     */
    private NoiseSourceRepository $noiseSourceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->noiseSourceRepository = app(NoiseSourceRepository::class);
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
        // TODO количество источников, указать правильно
        $count = 1;
        $item = new NoiseSource();

        // TODO выделить в отдельный класс
        $columns = implode(', ', ['id', 'name']);

        $TypeNoiseSource = new TypeNoiseSource;
        $typeList = $TypeNoiseSource
            ->selectRaw($columns)
            ->toBase()
            ->get();
        return view('noise.main.create', compact('count', 'item', 'typeList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd(__METHOD__, $request);
    }
}
