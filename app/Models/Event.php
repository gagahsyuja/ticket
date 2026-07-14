<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'user_id',
        'kategori_id',
        'deskripsi',
        'tanggal',
        'lokasi',
        'gambar'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected function getStatusAttribute(): Attribute
    {
        return Attribute::make(
            get: function () {
                switch (true)
                {
                    case $this->tanggal->isBetween(now()->subHours(3), now()):
                        return 'Ongoing';

                    case $this->tanggal->isPast():
                        return 'Completed';

                    default:
                        return 'Upcoming';
                }
            }
        );
    }

    public function hasSales(): Bool
    {
        return $this->orders()->exists();
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('tanggal', '<', now()->subHours(3));
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->whereBetween('tanggal', [
            now()->subHours(3),
            now()
        ]);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('tanggal', '>', now());
    }

    protected function getImageUrlAttribute(): Attribute
    {
        return Attribute::make(
            get: function () {

                $url = $this->gambar;

                if (filter_var($url, FILTER_VALIDATE_URL))
                {
                    return $url;
                }

                if ($url && Storage::disk('public')->exists($url))
                {
                    return Storage::disk('public')->url($url);
                }

                return asset('storage/konser.jpg');
            }
        );
    }
}
