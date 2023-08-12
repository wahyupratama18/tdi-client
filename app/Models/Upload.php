<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Upload extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the Upload
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
