<?php
$data_dir = str_replace('\\', '/', dirname(__FILE__)).'/';
$config_dir = $data_dir.'../config/';
require_once $config_dir.'db.php';

/*
 * Data access from database.
 * This is a simple php script for querying the data/record of ceg_core & ceg_base table.
 * corresponding column:
 * access_num,
 * gid,
 * organismid,
 * koid,
 * description,
 */

/**
 * Query CEG record by organism ID.
 * @param type $id
 * @return type
 */
function queryCegRecByOgID($id) {
    $pdo = getPDO();
    try {
        $sql = 'select * from ceg_core where organismid = :id';
        $ps = $pdo->prepare($sql);
        $ps->bindValue(':id', $id);
        $ps->execute();
        $ps->setFetchMode(PDO::FETCH_ASSOC);
        $result = $ps->fetchAll();
    } catch (Exception $ex) {
        $error = 'An Error has occurred in data accessing.';
        exit();
    }
    return $result;
}

/**
 * Query CEG record by a state which means the expression with where in sql.
 * @param type $state
 * @return type
 */
function queryCegRecByState($state) {
    $pdo = getPDO();
    try {
        $sql = 'select * from ceg_core where '.$state;
        $ps =$pdo->prepare($sql);
        $ps->execute();
        $ps->setFetchMode(PDO::FETCH_ASSOC);
        $result = $ps->fetchAll();
    } catch (Exception $ex) {
        $error = 'An Error has occurred in data accessing.';
        exit();
    }
    return $result;
}

/**
 * Query access_num list.
 * @return type
 */
function queryAccessNum() {
    $pdo = getPDO();
    try {
        $sql = 'select access_num from ceg_base';
        $ps = $pdo->prepare($sql);
        $ps->execute();
        $ps->setFetchMode(PDO::FETCH_ASSOC);
        $result = $ps->fetchAll();
    } catch (Exception $ex) {
        $error = 'An Error has occurred in data accessing.';
        exit();
    }
    return $result;
}

/**
 * Query ceg_base.
 * @return type
 */
function queryAllCegBase() {
    $pdo = getPDO();
    try  {
        $sql = 'select * from ceg_base';
        $ps = $pdo->prepare($sql);
        $ps->execute();
        $ps->setFetchMode(PDO::FETCH_ASSOC);
        $result = $ps->fetchAll();
    } catch (Exception $ex) {
        $error = 'An Error has occurred in data accessing.';
        exit();
    }
    return $result;
}


