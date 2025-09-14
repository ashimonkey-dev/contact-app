<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = ['name', 'slug', 'user_id'];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 問い合わせフォームのURLを取得
     */
    public function getContactFormUrlAttribute(): string
    {
        return route('contacts.form', ['app' => $this->slug]);
    }

    /**
     * 問い合わせ数を取得
     */
    public function getContactCountAttribute(): int
    {
        return $this->contacts()->count();
    }
}
