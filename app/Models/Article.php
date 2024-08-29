<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['author_id'];

    public function translations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }
}
