<?php


namespace App\Http\Sevices;


use App\Http\Repositories\ArticleRepository;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    private ArticleRepository $articleRepo;

    public function __construct(
        ArticleRepository $articleRepository
    ) {
        $this->articleRepo = $articleRepository;
    }

    public function getAll(): Collection
    {
        return $this->articleRepo->all();
    }

    public function get(int $id): Article
    {
        return $this->articleRepo->getById($id);
    }

    public function store(array $payload)
    {
        $this->articleRepo->store($payload);
    }

    public function destroy(int $id)
    {
        $this->articleRepo->destroy($id);
    }
}