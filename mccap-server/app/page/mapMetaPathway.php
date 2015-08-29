<?php
$page_dir = str_replace('\\', '/', dirname(__FILE__)).'/';
$serv_dir = $page_dir . "../../service/";
require_once $serv_dir.'KeggServ.php';

function processUrl($url) {
    return "http://www.kegg.jp" . $url;
}

function processTitle($title) {
    return str_replace(array("</a>&nbsp;", "\">"), "", $title);
}

if (isset($_GET['id'])) {
    $param = trim($_GET['id']);
    $id = str_split($param, 2);
    foreach ($id as $k => $v) {
        $id_arr[] = intval($v);
    }
    
    $ko_arr = getMetaKOByIdArr($id_arr);
    $url = getPathwayUrl($ko_arr);
    $content = file_get_contents($url);
    
    $flag = 0;
    $preg = "\/kegg-bin\/(.*)\.args/i";
    $preg_url = "/\/kegg-bin\/(.*)\.args/i";
    $preg_title = "/\">ko(.*)<\/a>&nbsp;/i";
    if(!preg_match_all($preg_url, $content, $url_list)) {
        $flag = 1;
    }
    if(!preg_match_all($preg_title, $content, $title_list)) {
        $flag = 2;
    }
}  else {
    die();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="../css/page.css" type="text/css" rel="stylesheet" />
    <title>Result List</title>
</head>	
<body >
    <div>
        <ul>
            <?php
            for($i = 0;$i < count($url_list[0]);$i++) {
            ?>
            <li><a href="<?php echo processUrl($url_list[0][$i]); ?>"><?php echo processTitle($title_list[0][$i]); ?></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
</body>
</html>

