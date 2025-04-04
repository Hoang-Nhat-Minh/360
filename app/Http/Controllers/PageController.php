<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;

class PageController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)->get();
        $locations = Location::with(['sun', 'hotlinks.nextLocation.paronama:id,image', 'hotlinksSpecial', 'paronama:id,image', 'category:id,background_music'])
            ->orderBy('sort')
            ->get();

        return view('frontend.web.main', compact('locations', 'categories'));
    }

    public function api()
    {
        $locations = Location::with([
            'sun',
            'hotlinks.nextLocation.paronama:id,image',
            'hotlinksSpecial',
            'paronama:id,image',
            'category:id,background_music',
            'nextLocation:id,name'
        ])->orderBy('sort')->get();

        return response()->json($locations);
    }
}
