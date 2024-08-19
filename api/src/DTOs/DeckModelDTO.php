<?php
namespace deckbuilder_archive_spa_version_vue\api\DTOs;

class DeckModelDTO
{
    public $deckId;
    public $userId;
    public $deckName;
    public $format;
    public $url;
public static function map($deckModel, $url){
        $deckModelDTO = new DeckModelDTO();
        $deckModelDTO->deckId = $deckModel->deckId;
        $deckModelDTO->userId = $deckModel->userId;
        $deckModelDTO->deckName = $deckModel->deckName;
        $deckModelDTO->format = $deckModel->format;
        $deckModelDTO->url = $url."?action=displaydeckcontents&deckid=".$deckModel->deckId;
        return $deckModelDTO;
    }
}