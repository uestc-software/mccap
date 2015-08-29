<?php
$serv_dir = str_replace('\\', '/', dirname(__FILE__)).'/';
$data_dir = $serv_dir.'../data/';
require_once $data_dir.'cegRecordDA.php';
require_once 'util.php';

/*
 * This is php script for the service function of CEG record.
 */

/**
 * Get CEG record by a array of organism ID.
 * @param type $idArr
 * @return type
 */
function getCegRecByIDArr($idArr) {
    $arrLen = count($idArr, COUNT_NORMAL);
    $state = 'organismid in (';
    $count = 0;
    foreach ($idArr as $id) {
        $count++;
        if($count != $arrLen) {
            $state = $state . $id . ',';
        }else {
            $state = $state. $id . ')';
        }
    }
    $result = queryCegRecByState($state);
    return $result;
}

/**
 * Get an array of all the access_num.
 * @return type
 */
function getAccessNumArr() {
    $result = array();
    $tmpRs = queryAccessNum();
    foreach ($tmpRs as $row) {
        array_push($result, $row['access_num']);
    }
    return $result;
}

/**
 * Validate access_num.
 * @param type $accessNum
 * @return boolean
 */
function validateAccessNum($accessNum) {
    $accessNumArr = getAccessNumArr();
    if(in_array($accessNum, $accessNumArr)) {
        return TRUE;
    }else {
        return FALSE;
    }
}

/**
 * Find clusters of essential gene by the methos of half number.
 * @param type $idArr
 * @return int
 */
function findCegSet($idArr) {
    $totalRecord = getCegRecByIDArr($idArr); //get all of the CEG record by an array of custom organism ID.
    
    $countOg = count($idArr, COUNT_NORMAL);
    $halfCountOg = $countOg / 2; //half num of organisms.
    
    $countCluster = 0; //used to count the number of cluster included in the result.
    
    $tmpCountClusterArr = array();
    $clusterOgArr = array();
    
    //count for every cluster.
    foreach ($totalRecord as $row) {
        $accessNum = $row['access_num'];
        $organismId = $row['organismid'];
        if(!isset($clusterOgArr[$accessNum])) {
            $clusterOgArr[$accessNum] = array(); //prepare an empty array for new cluster to store organism included.
        }
        if(!in_array($organismId, $clusterOgArr[$accessNum])) {
            if(isset($tmpCountClusterArr[$accessNum])) {
                $tmpCountClusterArr[$accessNum] ++; //the count of this cluster plus one.
            } else {
                $tmpCountClusterArr[$accessNum] = 1; //assign a initialization value for new cluster.
            }
            array_push($clusterOgArr[$accessNum], $organismId);
        }
    }
    //find out the set of clusters which count greater than half_count_og.
    $clusterArr = array();
    $countArr = array();
    foreach ($tmpCountClusterArr as $cluster => $count) {
        if($count > $halfCountOg) {
            $countCluster ++;
            array_push($clusterArr, $cluster);
            array_push($countArr, $count);
        }
    }
    
    $result['cluster_arr'] = $clusterArr;
    $result['count_arr'] = $countArr;
    $result['cluster_num'] = $countCluster;
    $result['total_record'] = $totalRecord;
    return $result;
}

/**
 * Get the cluster only.
 * @param type $idArr
 * @return type
 */
function getPureCegArr($idArr) {
    $tmp = findCegSet($idArr);
    $result = $tmp['cluster_arr'];
    return $result;
}

/**
 * Get CEG base record, each cluster with a description.
 * @param type $idArr
 * @return type
 */
function getCegBaseByIdArr($idArr) {
    $result = array();
    $clusterArr = getPureCegArr($idArr);
    $cegBase = queryAllCegBase();
    foreach ($cegBase as $row) {
        if(in_array($row['access_num'], $clusterArr)) {
            array_push($result, $row);
        }
    }
    return $result;
}

/**
 * Get a set of record with cluster, description and category.
 * @param type $idArr
 * @return type
 */
function getCegDescCate($idArr) {
    $result = array();
    $tmp = getCegBaseByIdArr($idArr);
    foreach ($tmp as $row) {
        $cegDescCate['cluster'] = $row['access_num'];
        $cegDescCate['description'] = $row['description'];
        $cegDescCate['category'] = deterCegBaseCate($row);
        array_push($result, $cegDescCate);
    }
    return $result;
}

function downloadCegCustom($idArr) {
    header("Content-type: application/octet-stream;charset=gbk");
    header("Content-Disposition: attachment; filename=ceg.xml");
    $tmp = getCegDescCate($idArr);
    $br = "\n";
    $t = "\t";
    
    echo '<Ceg>'.$br;
    foreach ($tmp as $row) {
        echo '<Cluster>'.$br;
        echo $t.'<Access_num>'.$row['cluster'].'</Access_num>'.$br;
        echo $t.'<Description>'.$row['description'].'</Description>'.$br;
        echo $t.'<Category>'.cate2Name($row['category']).'</Category>'.$br;
        echo '</Cluster>'.$br;
    }
    echo '</Ceg>';
}

function compareTwoGroups($idArr1, $idArr2) {
    $result = array();
    $data1 = getCegDescCate($idArr1);
    $data2 = getCegDescCate($idArr2);
    foreach ($data1 as $row) {
        $cluster = $row["cluster"];
        $rsRow["cluster"] = $cluster;
        $rsRow["description"] = $row["description"];
        $rsRow["category"] = $row["category"];
        $rsRow["group"] = 1;
        $result[$cluster] = $rsRow;
    }
    foreach ($data2 as $row) {
        $cluster = $row["cluster"];
        if (!isset($result[$cluster])) {
            $rsRow["cluster"] = $cluster;
            $rsRow["description"] = $row["description"];
            $rsRow["category"] = $row["category"];
            $rsRow["group"] = 2;
            $result[$cluster] = $rsRow;
        } else {
            $result[$cluster]["group"] = 3;
        }
    }
    return $result;
}

function getCompareResult($idArr1, $idArr2) {
    $result = array();
    $tmp = compareTwoGroups($idArr1, $idArr2);
    foreach ($tmp as $row) {
        array_push($result, $row);
    }
    return $result;
}

function getCompareStatics($compareRs) {
    $result = array();
    $total = 0;
    $group1 = 0;
    $group2 = 0;
    $both = 0;
    foreach ($compareRs as $row) {
        $total++;
        switch ($row["group"]) {
            case 1:
                $group1++;
                break;
            case 2:
                $group2++;
                break;
            case 3:
                $both++;
                break;
        }
    }
    $overlapRatio = number_format($both / $total * 100, 2) . "%";
    $result["total"] = $total;
    $result["group1"] = $group1;
    $result["group2"] = $group2;
    $result["both"] = $both;
    $result["overlapRatio"] = $overlapRatio;
    return $result;
}
