<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Flyer;
use App\Models\LandingSetting;
use App\Models\Partner;
use App\Models\Syllabus;
use App\Models\TrainingHistory;
use Illuminate\Contracts\View\View;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ubah dari Banner::first() menjadi Banner::latest()->get() atau Banner::all()
        $banners = Banner::latest()->get();
        $partners = Partner::all();
        $flyers = Flyer::all();
        $syllabi = Syllabus::all();
        $histories = TrainingHistory::latest()->get();
        $flyerTitleSegments = LandingSetting::flyerTitleSegments();
        $flyerDescriptionSegments = LandingSetting::flyerDescriptionSegments();

        return view('public.landing', compact(
            'banners',
            'partners',
            'flyers',
            'syllabi',
            'histories',
            'flyerTitleSegments',
            'flyerDescriptionSegments'
        ));
    }

    public function showFlyer(Flyer $flyer): View
    {
        return view('public.flyer', compact('flyer'));
    }
}
