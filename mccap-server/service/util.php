<?php
$serv_dir = str_replace('\\', '/', dirname(__FILE__)).'/';
$data_dir = $serv_dir.'../data/';
$config_dir = $serv_dir.'../config/';
require_once $config_dir.'main.php';

/*
 * This is php script for the common functions.
 */

/**
 * Validate the ID of cog
 * @param type $cogId
 * @return boolean
 */
function validateCogId($cogId) {
    if(strlen($cogId) > 7) {
        return TRUE;
    }  else {
        return FALSE;
    }
}

/**
 * Determine if the category of cluster is transport and metabolism.
 * @param type $cogId
 * @return boolean
 */
function isTranMeta($cogId) {
    $alphabet = array('D', 'E', 'F', 'G', 'H', 'I', 'P');
    if(validateCogId($cogId) && in_array($cogId[7], $alphabet)) {
        return TRUE;
    }else {
        return FALSE;
    }
}

/**
 * Determine if the category of cluster is transcription and translation.
 * @param type $cogId
 * @return boolean
 */
function is2Tran($cogId) {
    $alphabet = array('J', 'K', 'L');
    if(validateCogId($cogId) && in_array($cogId[7], $alphabet)) {
        return TRUE;
    }else {
        return FALSE;
    }
}

/**
 * Determine if the category of cluster is General function prediction only.
 * @param type $cogId
 * @return boolean
 */
function isGFPO($cogId) {
    $alphabet = array('R');
    if(validateCogId($cogId) && in_array($cogId[7], $alphabet)) {
        return TRUE;
    }else {
        return FALSE;
    }
}

/**
 * Determine the category of cluster by COG ID.
 * @param type $cogId
 * @return int
 */
function determineCategory($cogId) {
    if(isTranMeta($cogId)) {
        return 1; // transport and metabolism
    }else if(is2Tran($cogId)) {
        return 2; // transcription and translation
    }else if(isGFPO($cogId)) {
        return 3; // general function prediction only
    }else {
        return 4; // others
    }
}

/**
 * Determine the category of ceg_base.
 * @param type $cegBase
 * @return type
 */
function deterCegBaseCate($cegBase) {
    $cogId = $cegBase['cogid'];
    return determineCategory($cogId);
}

function cate2Name($category) {
    $categoryName = '';
    if($category == 1) {
        $categoryName = 'Transport & Metabolism';
    }elseif ($category == 2) {
        $categoryName = 'Transcription & Translation';
    }elseif ($category == 3) {
        $categoryName = 'General function prediction only';
    }elseif ($category == 4) {
        $categoryName = 'Others';
    }
    return $categoryName;
}

function idArr2Str($arr) {
    $str = "";
    for($i = 0;$i < count($arr);$i++) {
        $str = $str . idNum2Str($arr[$i]);
    }
    return $str;
}

function idNum2Str($id) {
    $str = "";
    if($id < 10) {
        $str = "0" . (String)$id;
    }  else {
        $str = (String)$id;
    }
    return $str;
}

function getPageUrl($pageName, $idArr = array(1 ,2, 3, 4)) {
    $host = getHost();
    $param = idArr2Str($idArr);
    switch ($pageName) {
        case "entirePathway":
            $preUrl = "/mccap-server/app/page/mapEntirePathway.php?id=";
            break;
        case "metaPathway":
            $preUrl = "/mccap-server/app/page/mapMetaPathway.php?id=";
            break;
        case "entireModule":
            $preUrl = "/mccap-server/app/page/mapEntireModule.php?id=";
            break;
        case  "metaModule":
            $preUrl = "/mccap-server/app/page/mapMetaModule.php?id=";
            break;
        default:
            $preUrl = "/mccap-server/app/page/error.php";
            $param = "";
            break;
    }
    $url = $host . $preUrl . $param;
    return $url;
}


