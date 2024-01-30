<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Support\Collection;

class GenericController extends Controller
{
    public function  themesResources($id, Theme $theme): Collection
    {
        return  $theme->getResourcesByThemes ($id);
    }

}
