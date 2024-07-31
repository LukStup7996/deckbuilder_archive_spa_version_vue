<?php
namespace deckbuilder_archive_spa_version_vue\api\controller;
use deckbuilder_archive_spa_version_vue\api\controller\CardArchiveController;
use deckbuilder_archive_spa_version_vue\api\controller\UserAccountController;
use deckbuilder_archive_spa_version_vue\api\controller\DeckbuilderController;
use deckbuilder_archive_spa_version_vue\api\controller\DeckArchiveController;
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
        $this->cors();

        $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        switch(strtolower($action)){
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
            case 'searchbyuser':
            case 'searchbyname':
            case 'searchbyformat':
            case 'searchalldecks':
            case 'displayowned':
            case 'displaydeckcontents':
                $this->searchForDeck();
                break;    
            case 'createarchiver':
            case 'loginarchiver':
            case 'updatearchivername':
            case 'updatearchiverpassword':    
            case 'deletearchiver':
                $this->handleAccount();
                break;
            case 'createdeck':
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
    public function cors() {
    
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
                header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');
                header('Access-Control-Allow-Credentials: true');
                            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
            exit(0);
        }
        
        echo "You have CORS!";
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
