<?php

namespace App\Models;

use App\Observers\SynchronizationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([SynchronizationObserver::class])]
class Synchronization extends Model
{
    use HasFactory;

    protected $fillable = [
        'sync',
    ];
}
