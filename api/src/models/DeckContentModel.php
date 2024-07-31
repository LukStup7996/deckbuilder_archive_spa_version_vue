<?php
namespace deckbuilder_archive_spa_version_vue\api\models;
//Model to store deck content objects based on database properties
class DeckContentModel
{
    public $cardName;
    public $cardId;    
    public $cardType;
    public $subType;
    public $superType;
    public $manaValue;
    public $castingCost;
    public $quantity;
}