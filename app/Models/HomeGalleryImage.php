<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeGalleryImage extends Model
{
    protected $fillable = ['path', 'alt_text', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
