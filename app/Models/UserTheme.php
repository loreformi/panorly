<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'preset', 'accent_color', 'background_color',
        'text_color', 'background_image_path', 'layout_density', 'extra',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toExportArray(): array
    {
        return [
            'format_version' => 1,
            'preset' => $this->preset,
            'accent_color' => $this->accent_color,
            'background_color' => $this->background_color,
            'text_color' => $this->text_color,
            'layout_density' => $this->layout_density,
            'extra' => $this->extra,
        ];
    }
}
