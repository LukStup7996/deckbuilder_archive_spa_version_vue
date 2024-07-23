<?php
namespace deckbuilder_archive_spa_version_vue\api\controller;
use deckbuilder_archive_spa_version_vue\api\gateways\ReadUserFromDBGateway;
use deckbuilder_archive_spa_version_vue\api\views\JsonView;
use deckbuilder_archive_spa_version_vue\api\config\JwtHelper;

class UserAccountController
{ 
    private $jsonView;
    private $accountGateway;

    public function __construct(){
        $this->accountGateway = new ReadUserFromDBGateway(DBHost, DBName, DBUsername, DBPassword);
        $this->jsonView = new JsonView();
    }

    public function route(){
        $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        switch(strtolower($action)){
            case 'createarchiver':
                $emailInput = filter_input(INPUT_GET, "mailadress", FILTER_SANITIZE_EMAIL);
                $userNamInput = filter_input(INPUT_GET,"username",FILTER_SANITIZE_STRING);
                $passwordInput = filter_input(INPUT_GET,"password",FILTER_SANITIZE_STRING);
                $confirmPasswordInput = filter_input(INPUT_GET,"confirm",FILTER_SANITIZE_STRING);
                if($passwordInput !== $confirmPasswordInput){
                    $errorM = "Please make sure to verify your password";
                    $this->jsonView->display($errorM);
                }else{
                    $this->createNewArchiver($emailInput, $userNamInput, $passwordInput);
                }
                break;
            case 'loginarchiver':
                $emailInput = filter_input(INPUT_GET,"mailadress", FILTER_SANITIZE_EMAIL);
                $passwordInput = filter_input(INPUT_GET,"password", FILTER_SANITIZE_STRING);
                if($emailInput && $passwordInput){
                    $jwt = $this->validateUserLogin($emailInput, $passwordInput);
                    $this->jsonView->display($jwt);
                }
                break;
            case 'logoutarchiver':
                $state = $this->disconnectUser();
                $this->jsonView->display($state);
                break;
            case 'getarchiver':
                $jwt = $this->getBearerToken();
                $userMail = $this->authenticate($jwt);
                if($userMail !== "ERROR"){
                    $this->jsonView->display($userMail);
                }else{
                    $errorM = "Could not get user";
                    $this->jsonView->display($errorM);
                }
                break;
            case 'updatearchivername':
                $newNameInput = filter_input(INPUT_GET, "newusername", FILTER_SANITIZE_STRING);
                $jwt = $this->getBearerToken();
                $payload = $this->authenticate($jwt);
                if($payload !== "ERROR"){
                    $this->updateUserName($payload['user_id'], $newNameInput);
                    $this->jsonView->display("Username updated successfully.");
                }else{
                    $errorM = "Could not change user name";
                    $this->jsonView->display($errorM);
                }
                break;
            case 'updatearchiverpassword':
                $newPasswordInput = filter_input(INPUT_GET, "newpassword", FILTER_SANITIZE_STRING);
                $jwt = $this->getBearerToken();
                $payload = $this->authenticate($jwt);
                if($payload !== "ERROR"){
                    $this->updateUserPassword($payload['user_id'], $newPasswordInput);
                    $this->jsonView->display("Password updated successfully.");
                }else{
                    $errorM = "Could not change user password";
                    $this->jsonView->display($errorM);
                }
                break;
            case 'deletearchiver':
                $confirmPasswordInput = filter_input(INPUT_GET, "password", FILTER_SANITIZE_STRING);
                $jwt = $this->getBearerToken();
                $payload = $this->authenticate($jwt);
                if($payload !== "ERROR" && $this->checkForDeletion($payload['user_id'], $confirmPasswordInput)){
                    $this->terminateArchiveuser($payload['user_id']);
                    $this->jsonView->display("User deleted successfully.");
                } else {
                    $this->jsonView->display("Failed to delete user.");
                }
                break;
            default:
                $errorM = "Unknown Action";
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

    private function checkForDeletion($userId, $confirmPassword){
        $userData = $this->accountGateway->getUserByID($userId);
        if(password_verify($confirmPassword, $userData->user_password)){
            return true;
        }else{
            return false;
        }
    }

    private function createNewArchiver($mailAdress, $userName, $password){
        $checkingForDuplicates = $this->checkForExistingDBEntries($mailAdress);
        if($checkingForDuplicates !== null){
            $errorM = "This archiver already exists, please choose a different mail address.";
            $this->jsonView->display($errorM);
        }else{
            $this->accountGateway->createArchiveUser($mailAdress, $userName, $password);
            $this->jsonView->display("New User: ".$mailAdress." ".$userName." has been created successfully.");
        }
    }

    private function validateUserLogin($mailAdres, $password) {
        $userlogin = $this->accountGateway->getUserByMailAdress($mailAdres);
        if (password_verify($password, $userlogin->user_password)) {
            $userToken = array(
                'user_id' => $userlogin->user_id,
                'user_name' => $userlogin->archive_name,
                'mail_adress' => $userlogin->mail_adress,
            );
            return JwtHelper::createJwt($userToken);
        } else {
            return "ERROR";
        }
    }

    public function authenticate($jwt) {
        $payload = JwtHelper::validateJwt($jwt);
        if ($payload) {
            return $payload;
        } else {
            return "ERROR";
        }
    }

    private function disconnectUser(){
        // JWT is stateless, so no action needed for logout
        return "OK";
    }

    public function updateUserPassword($userId, $newPassword) {
        $success = $this->accountGateway->upadteUserPasswordByMailAdress($userId, $newPassword);
        if ($success) {
            $this->jsonView->display("Password updated successfully.");
        } else {
            $this->jsonView->display("Failed to update password.");
        }
    }

    public function updateUserName($userId, $newUserName) {
        $success = $this->accountGateway->updateArchiverNameByMailAdress($userId, $newUserName);
        if ($success) {
            $this->jsonView->display("Username updated successfully.");
        } else {
            $this->jsonView->display("Failed to update username.");
        }
    }

    private function terminateArchiveuser($userId){
        $success = $this->accountGateway->deleteArchiveUser($userId);
        if($success){ 
            $this->jsonView->display("User deleted successfully.");
        }else{
            $this->jsonView->display("No user found for deletion.");
        }
    }

    private function checkForExistingDBEntries($mailAdress){
        return $this->accountGateway->getUserByMailAdress($mailAdress); 
    }
}

