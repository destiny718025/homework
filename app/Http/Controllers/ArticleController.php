<?php

namespace App\Http\Controllers;

use App\Http\Sevices\ArticleService;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="This is the API documentation for our application.",
 * )
 */
class ArticleController extends Controller
{
    private ArticleService $articleService;

    public function __construct(
        ArticleService $articleService
    ) {
        $this->articleService = $articleService;
    }

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get articles",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of the article",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", description="The ID of the article"),
     *                  @OA\Property(property="author_id", type="integer", description="The ID of the author"),
     *                  @OA\Property(
     *                      property="translations",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="locale", type="string", description="Language locale for the translation"),
     *                          @OA\Property(property="title", type="string", description="Title of the article"),
     *                          @OA\Property(property="content", type="string", description="Content of the article")
     *                      )
     *                  )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function index()
    {
        $articles = $this->articleService->getAll();
        return response()->json($articles);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     summary="Get an article by ID",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the article to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of the article",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="The ID of the article"),
     *             @OA\Property(property="author_id", type="integer", description="The ID of the author"),
     *             @OA\Property(
     *                 property="translations",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="lang", type="string", description="Language locale for the translation"),
     *                     @OA\Property(property="title", type="string", description="Title of the article"),
     *                     @OA\Property(property="content", type="string", description="Content of the article")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        $article = $this->articleService->get($id);

        return response()->json($article);
    }

    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Create a new article",
     *     tags={"Articles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"author_id", "translations"},
     *             @OA\Property(property="author_id", type="integer", description="The ID of the author"),
     *             @OA\Property(
     *                 property="translations",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"lang", "title", "content"},
     *                     @OA\Property(property="lang", type="string", description="Language locale for the translation"),
     *                     @OA\Property(property="title", type="string", description="Title of the article"),
     *                     @OA\Property(property="content", type="string", description="Content of the article")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="message"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", description="Error message")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $payload = $request->validate([
            'author_id' => 'required|integer',
            'translations' => 'required|array',
            'translations.*.lang' => 'required|string',
            'translations.*.title' => 'required|string',
            'translations.*.content' => 'required|string',
        ]);

        $this->articleService->store($payload);

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/articles/{id}",
     *     summary="Delete an article",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the article to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="message"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $this->articleService->destroy($id);

        return response()->json([
            'message' => 'success'
        ]);
    }
}
