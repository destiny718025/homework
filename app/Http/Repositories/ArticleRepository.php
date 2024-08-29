<?php


namespace App\Http\Repositories;


use App\Models\Article;
use App\Models\ArticleTranslation;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository
{
    private Article $article;

    private ArticleTranslation $articleTranslation;

    public function __construct(
        Article $article,
        ArticleTranslation $articleTranslation
    ) {
        $this->article = $article;
        $this->articleTranslation = $articleTranslation;
    }

    public function all(): Collection
    {
        return $this->article
            ->with(['translations' => function ($query) {
//                $query->where('lang', 'zh');
            }])
            ->get();
    }

    public function getById(int $id): Article
    {
        return $this->article
            ->where('id', $id)
            ->with('translations')
            ->first();
    }

    public function store(array $payload)
    {
        $article = $this->article->create([
            'author_id' => $payload['author_id']
        ]);

        foreach ($payload['translations'] as $translation) {
            $this->articleTranslation->create([
                'article_id' => $article->id,
                'lang' => $translation['lang'],
                'title' => $translation['title'],
                'content' => $translation['content']
            ]);
        }
    }

    public function destroy(int $id)
    {
        $this->article
            ->where('id', $id)
            ->delete();
    }
}