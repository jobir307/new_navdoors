<?php

namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GlassFigure;

class GlassFigureController extends Controller
{
    public function index()
    {
        $glassfigures = GlassFigure::all();

        return view('moderator.glassfigure.index', compact('glassfigures'));
    }
}
