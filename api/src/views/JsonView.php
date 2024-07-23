<?php
namespace deckbuilder_archive_spa_version_vue\api\views;

class JsonView implements ViewInterface
{
    public function display($data){
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}