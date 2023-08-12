<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'remarks',
        'is_active',
    ];

    public const SEMESTERREMARK = [
        1 => 'Ganjil',
        2 => 'Genap',
        3 => 'Antara',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all of the classrooms for the Semester
     */
    /* public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    } */

    /**
     * Get all of the schedules for the Semester
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function readTA(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->year.' '.self::SEMESTERREMARK[$this->remarks],
        );
    }

    public function activate(): self
    {
        Semester::query()->update(['is_active' => false]);

        $this->is_active = true;
        $this->save();

        return $this;
    }

    /* public function activeBadge(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->necessaryActiveInfo(),
        );
    } */

    /* private function badge(): string
    {
        $info = $this->necessaryActiveInfo();

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 '.$info->badge.' text-white">'.$info->text.'</span>';
    } */

    /* private function necessaryActiveInfo(): object
    {
        return match ($this->is_active) {
            1 => (object) [
                'badge' => 'bg-green-500',
                'text' => 'Aktif',
            ],
            0 => (object) [
                'badge' => 'bg-red-500',
                'text' => 'Tidak Aktif',
            ],
        };
    } */
}
