<?php
namespace deckbuilder_archive_spa_version_vue\api\services;
use deckbuilder_archive_spa_version_vue\api\gateways\DeckBuilderGateway;

class DeckBuilderService
{
    private $deckBuilderGateway;
    public function __construct(){
        $this->deckBuilderGateway = new DeckBuilderGateway(
            DBHost,
            DBName,
            DBUsername,
            DBPassword
        );
    }
    public function createDecklist($userId, $deckName, $format){
        $newDecklist = $this->deckBuilderGateway->createDecklist($userId, $deckName, $format);
        return $newDecklist;
    }
    public function deleteDecklistEntry($deckId){
        if($deckId){
            $this->deckBuilderGateway->removeDeckContentsFromDB($deckId);
            $this->deckBuilderGateway->deleteDecklistEntry($deckId);
            return true;
        }else{
            return false;
        }
    }
    public function removeDeckContents($deckId){
        $this->deckBuilderGateway->removeDeckContentsFromDB($deckId);
        return true;
    }
    public function uploadMainDeckContents($cardId,$deckId,$quantity){
        $this->deckBuilderGateway->pushMainDeckContentIntoDB($cardId,$deckId,$quantity);
        return true;        
    }
    public function removeMainDeckContents($cardId,$deckId){
        $this->deckBuilderGateway->removeMainDeckContent($cardId,$deckId);
        return true;        
    }
    public function uploadSideDeckContents($cardId,$deckId,$quantity){
        $this->deckBuilderGateway->pushSideDeckContentIntoDB($cardId,$deckId,$quantity);
        return true;        
    }
    public function removeSideDeckContents($cardId,$deckId){
        $this->deckBuilderGateway->removeSideDeckContent($cardId,$deckId);
        return true;        
    }
    public function uploadMaybeDeckContents($cardId,$deckId,$quantity){
        $this->deckBuilderGateway->pushMaybeDeckContentIntoDB($cardId,$deckId,$quantity);
        return true;        
    }
    public function removeMaybeDeckContents($cardId,$deckId){
        $this->deckBuilderGateway->removeMaybeDeckContent($cardId,$deckId);
        return true;        
    }
}