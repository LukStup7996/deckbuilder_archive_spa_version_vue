<?php
namespace deckbuilder_archive_spa_version_vue\api\controller;

use deckbuilder_archive_spa_version_vue\api\services\DeckArchiveService;
use deckbuilder_archive_spa_version_vue\api\views\JsonView;
use deckbuilder_archive_spa_version_vue\api\services\DeckBuilderService;
use deckbuilder_archive_spa_version_vue\api\DTOs\OwnedDeckDTO;
use deckbuilder_archive_spa_version_vue\api\config\JwtHelper;

class DeckbuilderController
{
    private $jsonView;
    private $deckBuilderService;
    private $deckArchiveService;

    public function __construct(){
        $this->jsonView = new JsonView();
        $this->deckBuilderService = new DeckBuilderService();
        $this->deckArchiveService = new DeckArchiveService();
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
            case 'removecard':
            case 'updatedeck':
            case 'selectdeck':
                $jwt = $this->getBearerToken();
                $payload = $this->authenticate($jwt);
                if ($payload !== "ERROR") {
                    $this->verifyDeckLegalityForArchiver($payload['user_id'], $payload['deck_id']);
                } else {
                    $this->jsonView->display("Invalid or expired token.");
                }
                break;
            case 'deletedeck':
                $getDeckId = filter_input(INPUT_GET, "deckid", FILTER_SANITIZE_STRING);
                $confirmTermination = filter_input(INPUT_GET, "confirm", FILTER_SANITIZE_STRING);
                $jwt = $this->getBearerToken();
                $payload = $this->authenticate($jwt);
                if ($payload !== "ERROR" && $payload['deck_id'] == $getDeckId) {
                    $this->deleteDecklist($getDeckId, $confirmTermination);
                } else {
                    $this->jsonView->display("Invalid or expired token, or deck ID mismatch.");
                }
                break;
            case 'displaydeckcontent':
                $this->deckBuilderEndcap();
                break;
            default:
                $errorM = "Unknown Action";
                $this->jsonView->display($errorM);                                
        }
    }

    private function createNewDecklist($userId, $deckName, $format){
        if ($userId) {
            $newDeckList = $this->deckBuilderService->createDecklist($userId, $deckName, $format);
            $jwt = JwtHelper::createJwt(['user_id' => $userId, 'deck_id' => $newDeckList->deck_id]);
            $this->jsonView->display(["message" => "Deck created successfully.", "token" => $jwt]);
        } else {
            $errorM = "You need to be logged in in order to use our services.";
            $this->jsonView->display($errorM);
        }
    }

    private function verifyDeckLegalityForArchiver($userIdInput, $deckIdInput){
        $listOfOwnedDecks = $this->deckArchiveService->checkForUserCreatedDBContent($userIdInput);
        $dtoList = [];
        foreach ($listOfOwnedDecks as $owned) {
            $dtoList[] = OwnedDeckDTO::map($owned);
        }

        $belongsToUser = false;
        foreach ($dtoList as $ownedDecklist) {
            if ($ownedDecklist->deckId == $deckIdInput) {
                $belongsToUser = true;
                break;
            }
        }

        if ($belongsToUser) {
            $jwt = JwtHelper::createJwt(['user_id' => $userIdInput, 'deck_id' => $deckIdInput]);
            $this->jsonView->display(["message" => "Deck selected successfully.", "token" => $jwt]);
        } else {
            $errorM = "No deck by your chosen ID could be affiliated to your user.";
            $this->jsonView->display($errorM);
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

    private function getBearerToken() {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $matches = [];
            preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches);
            if (isset($matches[1])) {
                return $matches[1];
            }
        }
        return null;
    }

    public function authenticate($jwt) {
        $payload = JwtHelper::validateJwt($jwt);
        if ($payload) {
            return $payload;
        } else {
            return "ERROR";
        }
    }

    private function deckBuilderEndcap(){
        $succesM = "You have successfully accessed deckbuilding services.";
        $this->jsonView->display($succesM);
    }
}
