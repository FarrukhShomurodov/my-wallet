<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_url',
        'category_id',
        'is_default'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_category');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
