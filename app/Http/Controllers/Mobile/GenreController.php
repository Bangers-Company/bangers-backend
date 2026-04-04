<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Http\Resources\GenreResource;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of all genres.
     */
    public function index()
    {
        return GenreResource::collection(Genre::orderBy('name')->get());
    }
}
