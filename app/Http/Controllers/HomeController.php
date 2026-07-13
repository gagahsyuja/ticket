<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategori::all();

        $eventsQuery = Event::with(['kategori', 'tikets']);

        if ($request->has('kategori') && $request->kategori)
        {
            $eventsQuery->where('kategori_id', $request->kategori);
        }

        $events = $eventsQuery->get()->map(function ($event) {
            $event->tikets_min_harga = $event->tikets->min('harga') ?? 0;
            return $event;
        });

        return view('home', [
            'categories' => $categories,
            'events' => $events
        ]);
    }
}
