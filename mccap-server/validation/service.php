<?php
$validation_dir = str_replace('\\', '/', dirname(__FILE__)).'/';
$serv_dir = $validation_dir.'../service/';
require_once $serv_dir.'cegRecordServ.php';
require_once $serv_dir.'organismServ.php';
require_once $serv_dir.'util.php';

/**
 * Find clusters of essential gene with a custom number.
 * @param type $idArr
 * @return int
 */
function findCegSetWithCustomNum($idArr, $num) {
    $totalRecord = getCegRecByIDArr($idArr); //get all of the CEG record by an array of custom organism ID.
    
    $countOg = count($idArr, COUNT_NORMAL);
    $halfCountOg = $countOg * $num; //half num of organisms.
    
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
//            $cluster_og_arr[$access_num][] = $organism_id;
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
function getPureCegArrWithCustomNum($idArr, $num) {
    $tmp = findCegSetWithCustomNum($idArr, $num);
    $result = $tmp['cluster_arr'];
    return $result;
}

/**
 * Get CEG base record, each cluster with a description.
 * @param type $idArr
 * @return type
 */
function getCegBaseByIdArrWithCustomNum($idArr, $num) {
    $result = array();
    $clusterArr = getPureCegArrWithCustomNum($idArr, $num);
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
function getCegDescCateWithCustomNum($idArr, $num) {
    $result = array();
    $tmp = getCegBaseByIdArrWithCustomNum($idArr, $num);
    foreach ($tmp as $row) {
        $ceg_desc_cate['cluster'] = $row['access_num'];
        $ceg_desc_cate['description'] = $row['description'];
        $ceg_desc_cate['category'] = deterCegBaseCate($row);
        array_push($result, $ceg_desc_cate);
    }
    return $result;
}

function compareTwoGroupsWithCustomNum($idArr1, $idArr2, $num) {
    $result = array();
    $data1 = getCegDescCateWithCustomNum($idArr1, $num);
    $data2 = getCegDescCateWithCustomNum($idArr2, $num);
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

function getCompareResultWithCustomNum($idArr1, $idArr2, $num) {
    $result = array();
    $tmp = compareTwoGroupsWithCustomNum($idArr1, $idArr2, $num);
    foreach ($tmp as $row) {
        array_push($result, $row);
    }
    return $result;
}

function getValidateStatics($compareRs) {
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
    $overlapRatio = number_format($both / ($both + $group2) * 100, 2) . "%";
    $result["total"] = $total;
    $result["group1"] = $group1;
    $result["group2"] = $group2;
    $result["both"] = $both;
    $result["overlapRatio"] = $overlapRatio;
    return $result;
}

function getStaticsForHalfCmpFull($compareRs) {
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
    $result["total"] = $total;
    $result["group1_only"] = $group1;
    $result["group2_only"] = $group2;
    $result["both"] = $both;
    $result["group1"] = $group1 + $both;
    $result["group2"] = $group2 + $both;
    return $result;
}