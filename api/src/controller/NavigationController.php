<?php
namespace deckbuilder_archive_spa_version_vue\api\controller;

use deckbuilder_archive_spa_version_vue\api\views\JsonView;

class NavigationController
{
    private $cardController;
    private $deckController;
    private $accountController;
    private $builderController;
    private $jsonView;

    public function __construct(){
        $this->cardController = new CardArchiveController();
        $this->deckController = new DeckArchiveController();
        $this->accountController = new UserAccountController();
        $this->builderController = new DeckbuilderController();
        $this->jsonView = new JsonView();
    }

    public function route(){
        $this->setCorsHeaders();
        
        $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        switch(strtolower($action)){
            // Kartenbezogene Routen
            case 'filterallcards':
            case 'filterbyid':
            case 'filterbyname':
            case 'filterbytype':
            case 'filterbysuper':
            case 'filterbysub':
            case 'filterbyvalue':
            case 'filterbycost':
                $this->searchForCard();
                break;
            // Deckbezogene Routen
            case 'searchbyuser':
            case 'searchbyname':
            case 'searchbyformat':
            case 'searchalldecks':
            case 'displayowned':
            case 'displaydeckcontents':
                $this->searchForDeck();
                break;    
            // Benutzerbezogene Routen
            case 'createarchiver':
            case 'loginarchiver':
            case 'logoutarchiver':
            case 'getarchiver':
            case 'updatearchivername':
            case 'updatearchiverpassword':    
            case 'deletearchiver':
                $this->handleAccount();
                break;
            // Deckbau Routen
            case 'createdeck':
            case 'addcard':
            case 'removecard':
            case 'updatedeck':
            case 'selectdeck':
            case 'deletedeck':
            case 'displaydeckcontent':
                $this->buildDeck();
                break;    
            default:
                $errorM = "Unknown Action";
                $this->jsonView->display($errorM);                                
        }
    }

    private function setCorsHeaders() {
        $allowedOrigins = [
            'http://localhost:8080', // URL der Vue-Entwicklungsumgebung
            'http://localhost/deckbuilder_archive_spa_version_vue', // URL der Produktionsumgebung
        ];
        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');
            header('Access-Control-Allow-Credentials: true');
        }

        // Preflight-Anfragen behandeln
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }
    }

    private function searchForCard(){
        $this->cardController->route();
    }

    private function searchForDeck(){
        $this->deckController->route();
    }

    private function handleAccount(){
        $this->accountController->route();
    }

    private function buildDeck(){
        $this->builderController->route();
    }
}
