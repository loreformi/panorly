<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'url', 'icon', 'icon_type', 'icon_path',
        'color', 'gradient_from', 'gradient_to', 'size', 'open_in_new_tab',
        'is_visible', 'sort_order',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
