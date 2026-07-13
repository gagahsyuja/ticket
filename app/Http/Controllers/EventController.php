<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $event->load(['kategori', 'tikets']);

        return view('events.show', [
            'event' => $event
        ]);
    }
}
