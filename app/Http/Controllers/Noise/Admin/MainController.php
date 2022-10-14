<?php

namespace App\Http\Controllers\Noise\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class MainController extends Controller
{
    public function __construct()
    {

    }
    protected function userIsAdminOrFail() {
        if (! Gate::allows('admin')) {
            abort(403, 'У вашего пользователя не достаточно прав');
        }
    }
}
