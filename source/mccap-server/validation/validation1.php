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
        echo json_encode($paramArr[0]);
        echo '<br>';
        echo json_encode($paramArr[1]);
        echo '<br>';
        execute($paramArr[0], $paramArr[1], $paramArr[2]);
        echo '----1/3<br>';
        execute($paramArr[0], $paramArr[1], $paramArr[3]);
        echo '----1/2<br>';
        execute($paramArr[0], $paramArr[1], $paramArr[4]);
        echo '----2/3<br>';
    }else {
        printParamErr();
    }
}

/**
 * get the parameter.
 * @return 
 */
function getParam() {
    $paramArr[0] = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, ); //define the idArr1;
    $paramArr[1] = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29); //define the idArr2
    $paramArr[2] = 1/3; //define the num
    $paramArr[3] = 1/2; //define the num
    $paramArr[4] = 2/3; //define the num
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
    $statics = getValidateStatics($tmp);
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