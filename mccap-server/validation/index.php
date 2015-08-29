<?php
$validation_dir = str_replace('\\', '/', dirname(__FILE__)).'/';
$serv_dir = $validation_dir.'../service/';
require_once $serv_dir.'cegRecordServ.php';
require_once $serv_dir.'organismServ.php';
require_once 'service.php';

/*
 * This is api script for comparing CEG record.
 * param, 'id' , a string of organism id.
 * return, records of CEG with JSON format.
 * example, http://yourHost.com/api/cegRecord/compare.php?id=01020304
 */

/**
 * Init function of API.
 */
function init() {
    if(($paramArr = getParam()) && validateParam($paramArr[0]) && validateParam($paramArr[1])) {
        execute($paramArr[0], $paramArr[1], $paramArr[2]);
    }else {
        printParamErr();
    }
}

/**
 * get the parameter.
 * @return 
 */
function getParam() {
    $paramArr[0] = array(1, 2, 3, 4); //define the idArr1;
    $paramArr[1] = array(1, 2, 3, 4, 5, 6, 7); //define the idArr2
    $paramArr[2] = 2/3; //define the num
    return $paramArr;
}

/**
 * Validate parameter.
 * @param type $idArr
 * @return boolean
 */
function validateParam($idArr) {
    if((count($idArr) > 3) && (count($idArr) == count(array_unique($idArr))) && validateOgIDArr($idArr)) {
        return TRUE;
    }  else {
        return FALSE;
    }
}

/**
 * main service function.
 * @param type $idArrGroup
 */
function execute($idArr1, $idArr2, $num) {
    $tmp = getCompareResultWithCustomNum($idArr1, $idArr2, $num);
    $statics = getCompareStatics($tmp);
    print_r($statics);
}

/**
 * print the error info. 
 */
function printParamErr() {
    $err['status'] = 1;
    $err['error'] = 'An error has occurred in parameter.';
    echo json_encode($err);
}

init();