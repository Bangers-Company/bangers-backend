<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Get mobile application configuration and feature flags.
     */
    public function features()
    {
        return response()->json([
            'flags' => config('features.flags', [])
        ]);
    }
}
