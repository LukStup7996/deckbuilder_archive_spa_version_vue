<?php
namespace deckbuilder_archive_spa_version_vue\api\DTOs;

class OwnedDeckDTO
{
    public $deckId;
    public $userId;
    public $deckName;
    public $format;
    public static function map($owned)
    {
        $ownedDeckDTO = new OwnedDeckDTO();
        $ownedDeckDTO->deckId = $owned->deckId;
        $ownedDeckDTO->userId = $owned->userId;
        $ownedDeckDTO->deckName = $owned->deckName;
        $ownedDeckDTO->format = $owned->format;
        return $ownedDeckDTO;
    }
}