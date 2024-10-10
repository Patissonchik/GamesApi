<?php

namespace App\DTO;
use App\Models\Game;

class GameDTO
{
    public string $title;
    public string $developer;
    public array $genres;
    public function __construct(string $title, string $developer, array $genres)
    {
        $this->title = $title;
        $this->developer = $developer;
        $this->genres = $genres;
    }

    public static function fromModel(Game $game): self
    {
        return new self($game->title, $game->developer, json_decode($game->genres, true));
    }
}
