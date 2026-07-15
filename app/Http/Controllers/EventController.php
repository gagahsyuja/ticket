<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Http\Requests\EventFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['kategori', 'tikets']);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'asc');
        $query->orderBy('tanggal_waktu', $sort);

        $events = $query->paginate(10);

        return view('pages.admin.events.index', compact('events'));
    }

    public function create()
    {
        $kategoris = Kategori::all();

        return view('pages.admin.events.create', compact('kategoris'));
    }

    public function store(EventFormRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('events', 'public');
        } else {
            $data['gambar'] = 'konser.jpg';
        }

        $data['user_id'] = auth()->id();

        $tikets = $data['tikets'];
        unset($data['tikets']);

        $event = Event::create($data);

        foreach ($tikets as $tiket) {
            $event->tikets()->create($tiket);
        }

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        $kategoris = Kategori::all();
        $tikets = $event->tikets;
        $hasSales = $event->hasSales();

        return view('pages.admin.events.edit', compact('event', 'kategoris', 'tikets', 'hasSales'));
    }

    public function update(EventFormRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($event->hasSales() && $event->tanggal_waktu != $data['tanggal_waktu']) {
            return back()->withErrors(['tanggal_waktu' => 'Tidak dapat mengubah tanggal event yang sudah memiliki penjualan.'])->withInput();
        }

        if ($request->hasFile('gambar')) {
            if ($event->gambar && $event->gambar !== 'konser.jpg' && Storage::disk('public')->exists($event->gambar)) {
                Storage::disk('public')->delete($event->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        $tikets = $data['tikets'];
        unset($data['tikets']);

        $event->update($data);

        $existingTiketIds = [];

        foreach ($tikets as $tiketData) {
            if (isset($tiketData['id'])) {
                $tiket = $event->tikets()->find($tiketData['id']);
                if ($tiket) {
                    $tiket->update($tiketData);
                    $existingTiketIds[] = $tiketData['id'];
                }
            } else {
                $newTiket = $event->tikets()->create($tiketData);
                $existingTiketIds[] = $newTiket->id;
            }
        }

        if (!$event->hasSales()) {
            $event->tikets()->whereNotIn('id', $existingTiketIds)->delete();
        }

        return redirect()->route('events.index')->with('success', 'Event berhasil diupdate.');
    }

    public function destroy(Event $event)
    {
        if ($event->hasSales()) {
            return back()->with('error', 'Tidak dapat menghapus event yang sudah memiliki penjualan.');
        }

        if ($event->gambar && $event->gambar !== 'konser.jpg' && Storage::disk('public')->exists($event->gambar)) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function show(Event $event)
    {
        $event->load(['kategori', 'tikets']);

        $relatedEvents = Event::with('tikets')
            ->where('kategori_id', $event->kategori_id)
            ->where('id', '!=', $event->id)
            ->where('tanggal_waktu', '>', now())
            ->take(4)
            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }
}
