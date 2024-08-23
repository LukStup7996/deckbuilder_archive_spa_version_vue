<?php
namespace deckbuilder_archive_spa_version_vue\api\controller;

use deckbuilder_archive_spa_version_vue\api\services\CardArchiveService;
use deckbuilder_archive_spa_version_vue\api\services\DeckArchiveService;
use deckbuilder_archive_spa_version_vue\api\views\JsonView;
use deckbuilder_archive_spa_version_vue\api\services\DeckBuilderService;
use deckbuilder_archive_spa_version_vue\api\DTOs\OwnedDeckDTO;

class DeckbuilderController
{
    private $jsonView;
    private $deckBuilderService;
    private $deckArchiveService;
    private $cardArchiveService;
    private $maindeck;
    private $sidedeck;
    private $maybedeck;

    public function __construct(){
        $this->jsonView = new JsonView();
        $this->deckBuilderService = new DeckBuilderService();
        $this->cardArchiveService =  new CardArchiveService();
        $this->deckArchiveService = new DeckArchiveService();
        $this->maindeck = [];
        $this->sidedeck = [];
        $this->maybedeck = [];
    }

    public function route(){
        $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        switch(strtolower($action)){
            case 'createdeck':
                $userIdInput = filter_input(INPUT_GET, "userid", FILTER_SANITIZE_NUMBER_INT);
                $deckNameInput = filter_input(INPUT_GET, "deckname", FILTER_SANITIZE_STRING);
                $formatInput = filter_input(INPUT_GET, "format", FILTER_SANITIZE_STRING);
                $this->createNewDecklist($userIdInput, $deckNameInput, $formatInput);
                break;
            case 'addcard':
                $userIdInput = filter_input(INPUT_GET, "userid", FILTER_SANITIZE_NUMBER_INT);
                $cardIdInput = filter_input(INPUT_GET, "cardid", FILTER_SANITIZE_STRING);
                $deckIdInput = filter_input(INPUT_GET, "deckid", FILTER_SANITIZE_NUMBER_INT);
                $quantityInput = filter_input(INPUT_GET, "quantity", FILTER_SANITIZE_NUMBER_INT);
                $defineSideBoard = filter_input(INPUT_GET, "sideboard", FILTER_SANITIZE_STRING);
                $defindeMaybeBoard = filter_input(INPUT_GET, "maybeboard", FILTER_SANITIZE_STRING);
                $confirmLegality = $this->verifyDeckLegalityForArchiver($userIdInput, $deckIdInput);
                if($confirmLegality && $defineSideBoard == "No" && $defindeMaybeBoard == "No"){
                    $this->addCardToMainboard($cardIdInput, $deckIdInput, $quantityInput);
                }elseif($confirmLegality && $defineSideBoard == "Yes" && $defindeMaybeBoard == "No"){
                    $this->addCardToSideboard($cardIdInput, $deckIdInput, $quantityInput);
                }elseif($confirmLegality && $defineSideBoard == "No" && $defindeMaybeBoard == "Yes"){
                    $this->addCardToMaybeboard($cardIdInput, $deckIdInput, $quantityInput);
                }
                break;
            case 'removecard':
                $userIdInput = filter_input(INPUT_GET, "userid", FILTER_SANITIZE_NUMBER_INT);
                $cardIdInput = filter_input(INPUT_GET, "cardid", FILTER_SANITIZE_STRING);
                $deckIdInput = filter_input(INPUT_GET, "deckid", FILTER_SANITIZE_NUMBER_INT);
                $defineSideBoard = filter_input(INPUT_GET, "sideboard", FILTER_SANITIZE_STRING);
                $defindeMaybeBoard = filter_input(INPUT_GET, "maybeboard", FILTER_SANITIZE_STRING);
                $confirmLegality = $this->verifyDeckLegalityForArchiver($userIdInput, $deckIdInput);
                if($confirmLegality && $defineSideBoard == "No" && $defindeMaybeBoard == "No"){
                    $this->removeCardFromMainboard($cardIdInput, $deckIdInput);
                    $succesM = "Successfully removed card";
                    $this->jsonView->display($succesM); 
                }elseif($confirmLegality && $defineSideBoard == "Yes" && $defindeMaybeBoard == "No"){
                    $this->removeCardFromSideboard($cardIdInput, $deckIdInput);
                    $succesM = "Successfully removed card";
                    $this->jsonView->display($succesM);
                }elseif($confirmLegality && $defineSideBoard == "No" && $defindeMaybeBoard == "Yes"){
                    $this->removeCardFromSideboard($cardIdInput, $deckIdInput);
                    $succesM = "Successfully removed card";
                    $this->jsonView->display($succesM);
                }else{
                    $errorM = "Couldn't removed card";
                    $this->jsonView->display($errorM);
                }
                break;
            case 'deletedeck':
                $userIdInput = filter_input(INPUT_GET, "userid", FILTER_SANITIZE_NUMBER_INT);
                $deckIdInput = filter_input(INPUT_GET, "deckid", FILTER_SANITIZE_NUMBER_INT);
                $confirmTermination = filter_input(INPUT_GET, "confirm", FILTER_SANITIZE_STRING);
                $confirmLegality = $this->verifyDeckLegalityForArchiver($userIdInput, $deckIdInput);
                if ($confirmLegality == $deckIdInput) {
                    $this->deleteDecklist($deckIdInput, $confirmTermination);
                } else {
                    $this->jsonView->display("Invalid or expired token, or deck ID mismatch.");
                }
                break;
            default:
                $errorM = "Unknown Action";
                $this->jsonView->display($errorM);                                
        }
    }

    private function createNewDecklist($userId, $deckName, $format){
        if ($userId) {
            $newDeckList = $this->deckBuilderService->createDecklist($userId, $deckName, $format);
            $deckToken = array(
                "deck_id" => $newDeckList,
                "deck_name" => $deckName,
                "format" => $format,
            );
            $this->jsonView->display($deckToken);
        } else {
            $errorM = "You need to be logged in in order to use our services.";
            $this->jsonView->display($errorM);
        }
    }

    public function verifyDeckLegalityForArchiver($userId, $deckId) {
        $listOfOwnedDecks = $this->deckArchiveService->checkForUserCreatedDBContent($userId);
        $dtoList = [];
        foreach ($listOfOwnedDecks as $owned) {
            $dtoList[] = OwnedDeckDTO::map($owned);
        }
        $belongsToUser = false;
        foreach ($dtoList as $ownedDeck) {
            if ($ownedDeck->deckId == $deckId) {
                $belongsToUser = true;
                break;
            }
        }
        if ($belongsToUser) {
            return $deckId;
        } else {
            $errorMessage = "No deck by your chosen ID could be affiliated to you.";
            $this->jsonView->display($errorMessage);
        }
    }

    private function deleteDecklist($deckId, $confirmTermination){
        if ($confirmTermination == "Yes") {
            $this->deckBuilderService->deleteDecklistEntry($deckId);
            $successM = "Successfully deleted decklist.";
            $this->jsonView->display($successM);
        } else {
            $errorM = "There has been an issue with deleting your decklist";
            $this->jsonView->display($errorM);
        }
    }
    private function addCardToMainboard($cardId, $deckId, $quantity) {
       $this->removeCardFromMainboard($cardId, $deckId);
       $success = $this->deckBuilderService->uploadMainDeckContents($cardId, $deckId, $quantity);
       if($success){
        $succesM = "Successfully added card";
        $this->jsonView->display($succesM);
       }else{
        $errorM = "Could not add card";
        $this->jsonView->display($errorM);
       }
    } 
    private function removeCardFromMainboard($cardId, $deckId) {
        $this->deckBuilderService->removeMainDeckContents($cardId, $deckId);
    }
    private function addCardToSideboard($cardId, $deckId, $quantity) {
        $this->removeCardFromSideboard($cardId, $deckId);
        $success = $this->deckBuilderService->uploadMainDeckContents($cardId, $deckId, $quantity);
        if($success){
            $succesM = "Successfully added card";
            $this->jsonView->display($succesM);
           }else{
            $errorM = "Could not add card";
            $this->jsonView->display($errorM);
           }
    }
    private function removeCardFromSideboard($cardId, $deckId) {
        $this->deckBuilderService->removeSideDeckContents($cardId, $deckId);
    }
    private function addCardToMaybeboard($cardId, $deckId, $quantity) {
        $this->removeCardFromMaybeboard($cardId, $deckId);
        $success = $this->deckBuilderService->uploadMainDeckContents($cardId, $deckId, $quantity);
        if($success){
            $succesM = "Successfully added card";
            $this->jsonView->display($succesM);
           }else{
            $errorM = "Could not add card";
            $this->jsonView->display($errorM);
           }
    }
    private function removeCardFromMaybeboard($cardId, $deckId) {
        $this->deckBuilderService->removeMaybeDeckContents($cardId, $deckId);
    } 
U10