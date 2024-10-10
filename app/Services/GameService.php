<?php
namespace App\Services;

use App\Models\Game;
use App\DTO\GameDTO;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GameService
{
    public function createGame(GameDTO $data): GameDTO
    {
        try {
            $game = Game::create([
                'title' => $data->title,
                'developer' => $data->developer,
                'genres' => json_encode($data->genres, JSON_UNESCAPED_UNICODE),
            ]);
            return GameDTO::fromModel($game);
        } catch (\Exception $e) {
            throw new \RuntimeException("Ошибка при создании игры: " . $e->getMessage());
        }
    }

    public function getAllGames(): array
    {
        return Game::all()->map(fn($game) => GameDTO::fromModel($game))->toArray();
    }

    public function findGameById($id): Game
    {
        try {
            return Game::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \RuntimeException("Данные не найдены");
        }
    }

    public function updateGame($id, GameDTO $data): GameDTO
    {
        try {
            $game = $this->findGameById($id);
            $game->update([
                'title' => $data->title,
                'developer' => $data->developer,
                'genres' => json_encode($data->genres, JSON_UNESCAPED_UNICODE),
            ]);
            return GameDTO::fromModel($game);
        } catch (ModelNotFoundException $e) {
            throw new \RuntimeException("Данные не найдены");
        }
    }

    public function deleteGame($id): void
    {
        try {
            $game = $this->findGameById($id);
            $game->delete();
        } catch (ModelNotFoundException $e) {
            throw new \RuntimeException("Данные не найдены");
        }
    }

    public function getGamesByGenre($genre): array
    {
        return Game::whereJsonContains('genres', $genre)
            ->get()
            ->map(fn($game) => GameDTO::fromModel($game))
            ->toArray();
    }
}
