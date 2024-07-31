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
                    $this->validateUserLogin($emailInput, $passwordInput);
                }
                break;
            case 'updatearchivername':
                $getMailAdress = filter_input(INPUT_GET,"mailadress", FILTER_SANITIZE_EMAIL);
                $confirmPasswordInput = filter_input(INPUT_GET, "password", FILTER_SANITIZE_STRING);
                $newNameInput = filter_input(INPUT_GET, "newusername", FILTER_SANITIZE_STRING);
                $archiveUser = $this->accountGateway->getUserByMailAdress($getMailAdress);
                $legalityCheck = $this->verifyAccessLegality($archiveUser->user_id, $confirmPasswordInput);
                if($legalityCheck !== null){
                    $this->updateUserName($legalityCheck, $newNameInput);
                }
                break;
            case 'updatearchiverpassword':
                $getMailAdress = filter_input(INPUT_GET,"mailadress", FILTER_SANITIZE_EMAIL);
                $confirmPasswordInput = filter_input(INPUT_GET, "password", FILTER_SANITIZE_STRING);
                $newPasswordInput = filter_input(INPUT_GET, "newpassword", FILTER_SANITIZE_STRING);
                $archiveUser = $this->accountGateway->getUserByMailAdress($getMailAdress);
                $legalityCheck = $this->verifyAccessLegality($archiveUser->user_id, $confirmPasswordInput);
                if($legalityCheck !== null){
                    $this->updateUserPassword($legalityCheck, $newPasswordInput);
                }
                break;
            case 'deletearchiver':
                $getMailAdress = filter_input(INPUT_GET,"mailadress", FILTER_SANITIZE_EMAIL);
                $confirmPasswordInput = filter_input(INPUT_GET, "password", FILTER_SANITIZE_STRING);
                $archiveUser = $this->accountGateway->getUserByMailAdress($getMailAdress);
                $checkLegality = $this->verifyAccessLegality($archiveUser->user_id, $confirmPasswordInput);
                if($checkLegality == true){
                    $this->terminateArchiveuser($getMailAdress);
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
    private function verifyAccessLegality($userId, $confirmPassword){
        $userData = $this->accountGateway->getUserByID($userId);
        if(password_verify($confirmPassword, $userData->user_password)){
            return true;
        }else{
            return false;
        }
    }
    private function validateUserLogin($mailAdres, $password){
        if(!isset($_SESSION['user'])){
            $userlogin = $this->accountGateway->getUserByMailAdress($mailAdres);
            if(password_verify($password, $userlogin->user_password)){
                $userToken = array(
                    'user_id' => $userlogin->user_id,
                    'user_name' => $userlogin->archive_name,
                    'mail_adress' => $userlogin->mail_adress,
                );
                $this->jsonView->display($userToken);
            }else{
                $errorM = "Couldn't log you in";
                $this->jsonView->display($errorM);
            }
        }
    }
    private function createNewArchiver($mailAdress, $userName, $password){
        $newUserAccount = $this->accountGateway->createArchiveUser($mailAdress, $userName, $password);
        if($newUserAccount){
            $this->jsonView->display("New User: ".$mailAdress." ".$userName." has been created successfully.");
        }else{
            $errorM = "This archiver already exists, please choose a different mail address.";
            $this->jsonView->display($errorM);
        }
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
    private function terminateArchiveuser($mailAdress){
        $success = $this->accountGateway->deleteArchiveUser($mailAdress);
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