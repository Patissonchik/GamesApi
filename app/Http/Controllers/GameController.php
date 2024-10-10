<?php
namespace App\Http\Controllers;

use App\DTO\GameDTO;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class GameController extends Controller
{
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->gameService->getAllGames());
    }

    public function store(StoreGameRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $gameDTO = new GameDTO($data['title'], $data['developer'], $data['genres']);
            return response()->json($this->gameService->createGame($gameDTO), 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $game = $this->gameService->findGameById($id);
            return response()->json(GameDTO::fromModel($game));
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateGameRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $gameDTO = new GameDTO(
                $data['title'] ?? '',
                $data['developer'] ?? '',
                $data['genres'] ?? []
            );

            return response()->json($this->gameService->updateGame($id, $gameDTO));
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->gameService->deleteGame($id);
            return response()->json('Видео игра удалена', 204);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getByGenre($genre): JsonResponse
    {
        return response()->json($this->gameService->getGamesByGenre($genre));
    }
}
