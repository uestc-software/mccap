<?php
$seq = "";
$result = array();
if (isset($_POST["sequence"])) {
    $flag = 1;
    $seq = $_POST["sequence"];
    $initCodenArr = isset($_POST["initCodenArr"]) ? $_POST["initCodenArr"] : array("ATG","GTG", "TTG");
    $termCodenArr = isset($_POST["termCodenArr"]) ? $_POST["termCodenArr"] : array("TAG", "TAA", "TGA");
    $filterLength = isset($_POST["filterLength"]) ? $_POST["filterLength"] : 0;
    $result = execute($seq, $initCodenArr, $termCodenArr, $filterLength);
} else {
    $flag = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SD Finder</title>

        <!-- Bootstrap -->
        <link href="../css/sdFinder.css" type="text/css" rel="stylesheet">
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="../bootstrap/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <!-- head & title -->
            <div class="row" id="head">
                <h1 class="center-block">SD Finder</h1>
            </div>

            <!-- left content -->
            <div class="row" id="body">
                <div class="col-md-8" id="mainApp">
                    <form target="" method="post" name="sdform">
                        <div class="form-group">
                            <label >Sequence : </label>
                            <textarea class="form-control" rows="10" name="sequence"><?php if($flag){ echo $seq; } ?></textarea>
                        </div>
                        <div class="form-group">
                            <label >Initiation codon : </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="initCodenArr[]" value="ATG" checked="checked"> ATG
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="initCodenArr[]" value="GTG"> GTG
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="initCodenArr[]" value="TTG"> TTG
                            </label>
                        </div>
                        <div class="form-group">
                            <label >Termination codon : </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="termCodenArr[]" value="TAG" checked="checked"> TAG
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="termCodenArr[]" value="TAA" checked="checked"> GTG
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="termCodenArr[]" value="TGA"> TTG
                            </label>
                        </div>
                        <div class="form-group">
                            <label >Filter out(ORF) : </label>
                            <input type="number" name="filterLength" value="12"> bp
                        </div>
                        <a href="javascript:document.sdform.submit();" target="_self" type="submit" class="btn btn-success"> Execute </a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-info" data-target="#resultModal" data-toggle="modal"> Show result </a>
                    </form>
                </div>

                <!-- right bar -->
                <div class="col-md-4">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Introduction
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    We cooperated with SCU_China iGEM team to study how to find out SD sequence in DNA sequence and analyze the strength of the SD sequence that affects translation issues. Then we developed an online software based on browser, which is used in helping user predict the ORF and SD sequence of DNA sequence. User only needs to type sequence that needs to be predicted, select the initiation codons and termination codons. Then comes the prediction.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Help
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <p>Filter out: abandon the results of ORF whose length less than this number.</p>
                                    <p>Red tag: these sequences could be SD sequence.</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        About
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <p>Version: beta 1.0</p>
                                    <p>Team: UESTC_software</p>
                                    <p>Contact: ycduan_uestc@yeah.net</p>
                                    <p>Sponsor: UESTC National Science Park</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result modal -->
                <div class="modal fade bs-example-modal-lg" id="resultModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title text-center"><strong>Predicted SD sequence result</strong></h4>
                            </div>
                            <div class="modal-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Start</th>
                                            <th>Initiation Coden</th>
                                            <th>Stop</th>
                                            <th>Termination Coden</th>
                                            <th>Length</th>
                                            <th>SD-Seq(-14bp~-2bp)</th>
                                            <th>SD Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i = 0;$i < count($result);$i++) { ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td><?php echo $result[$i]["init"]; ?></td>
                                            <td><?php echo $result[$i]["initCoden"]; ?></td>
                                            <td><?php echo $result[$i]["term"]; ?></td>
                                            <td><?php echo $result[$i]["termCoden"]; ?></td>
                                            <td><?php echo $result[$i]["length"]; ?></td>
                                            <td><?php echo $result[$i]["sdSeq"]; ?></td>
                                            <td><?php echo $result[$i]["sdType"]; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal"> OK </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of container -->
        <script>
            <?php if ($flag) { ?>
                $('#resultModal').modal('show');
            <?php } ?>
        </script>
    </body>
</html>

<?php
//initiation codon: ATG GTG TTG
// termination codon: TAG TAA TGA
//16S rRNA 3'anti-SD: 3'-AUUCCUCCACUAG-5'
function isInitCoden($coden, $initCodenArr = array("ATG","GTG", "TTG")) {
    if(in_array($coden, $initCodenArr)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isTermCoden($coden, $termCodenArr = array("TAG", "TAA", "TGA")) {
    if(in_array($coden, $termCodenArr)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isInitCdCoord($seq, $coordinate, $initCodenArr = array("ATG","GTG", "TTG")) {
    $coden = getCoden($seq, $coordinate);
    return isInitCoden($coden, $initCodenArr);
}

function isTermCdCoord($seq, $coordinate, $termCodenArr = array("TAG", "TAA", "TGA")) {
    $coden = getCoden($seq, $coordinate);
    return isTermCoden($coden, $termCodenArr);
}

function getCoden($seq, $coordinate) {
    return substr($seq, $coordinate, 3);
}

function getSd($seq, $init) {
    $result = array();
    $start = $init - 15 - 1 > 0 ? $init - 15 - 1 : 0;
    $stop = $init - 2 -1 > 0 ? $init -2 -1 : 0;
    $sdSeq = substr($seq, $start, 13);
    $tmp = markSdSeq($sdSeq);
    $scope = "(" . (String)($start + 1) ."~".(String)($stop + 1) . ")";
    $result["sdSeq"] = $tmp["sdSeq"] . $scope;
    $result["sdType"] = $tmp["type"];
    return $result;
}

function markSdSeq($sdSeq) {
    $result = array();
    $strongSDArr = array("AGGAGG", "TAAGGA", "AAGGAG", "AAGGAA", "AGGAGT", "TTAAGG", "AGGAGA", "AAGGA", "UAAGG", "AGGAG", "AGGAA", "AUAAC", "GGAGA", "AGGA", "AAGG", "UAAG", "GGAG", "AGG", "UAA", "AAG");
    $mediumSDArr = array("CAGGAG", "AGGAAA", "AGGAGA", "ACAGGA", "GAGGAA", "AGGAGU", "AGGAG", "CAGGA", "GAGGA", "AGGAA", "GAGGA", "AAGGA", "AGGA", "GGAG", "GAGG", "GGA", "GAG");
    $weakSDArr = array("GAGGAG", "GAGAGA", "GGGGGC", "AGAGAG", "UGGGGG", "CUGGGG", "GGAGG", "GAGAG", "GGGGG", "UGGGG", "AGAGA", "GGGGA", "GGGG", "GAGA", "AGAG", "GGG", "AGA", "AGG");
    $flag = 0;
    $type = "null";
    $prefix = "<span id = \"redMark\">";
    $suffix = "</span>";
    foreach ($strongSDArr as $s) {
        if(strstr($sdSeq, $s)) {
            $flag = 1;
            $type = "Strong";
            $sdSeq = str_replace($s, $prefix . $s . $suffix, $sdSeq);
            break;
        }
    }
    if (!$flag) {
        foreach ($mediumSDArr as $s) {
            if (strstr($sdSeq, $s)) {
                $flag = 1;
                $type = "Medium";
                $sdSeq = str_replace($s, $prefix . $s . $suffix, $sdSeq);
                break;
            }
        }
    }
    if (!$flag) {
        foreach ($weakSDArr as $s) {
            if (strstr($sdSeq, $s)) {
                $flag = 1;
                $type = "Weak";
                $sdSeq = str_replace($s, $prefix . $s . $suffix, $sdSeq);
                break;
            }
        }
    }
    $result["sdSeq"] = $sdSeq;
    $result["type"] = $type;
    return $result;
}

function execute($seq, $initCodenArr = array("ATG","GTG", "TTG"), $termCodenArr = array("TAG", "TAA", "TGA"), $filterLength = 0) {
    $result = array();
    $length = strlen($seq);
    for($site = 0;$site < $length;) {
        if(isInitCdCoord($seq, $site, $initCodenArr)) {
            $orf = array();
            $init = $site + 1;
            $initCoden = getCoden($seq, $site);
            $term = 0;
            $termCoden = "---";
            for($site = $site + 3;$site < $length;) {
                if(isTermCdCoord($seq, $site, $termCodenArr)) {
                    $term = $site + 1;
                    $termCoden = getCoden($seq, $site);
                    break;
                }
                $site = $site + 3;
                if($site >= $length) {
                    $term = "null";
                    $termCoden = "null";
                }
            }
            $orf["init"] = $init;
            $orf["initCoden"] = $initCoden;
            $orf["term"] = $term;
            $orf["termCoden"] = $termCoden;
            $orf["length"] = $term - $init > 0 ? $term - $init : 0;
            $tmp = getSd($seq, $init);
            $orf["sdSeq"] = $tmp["sdSeq"];
            $orf["sdType"] = $tmp["sdType"];
            if($orf["length"] > $filterLength) {
                array_push($result, $orf);
            }
        }else {
            $site++;
        }
    }
    return $result;
}
?>