<?php
$sub_menu = "300900";
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], "d");

check_token();

$msg = "";
for ($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];


        // 회원자료 삭제
        survey_delete($k);

}

if ($msg)
    echo "<script type='text/javascript'> alert('$msg'); </script>";

goto_url("./survey_list.php?$qstr");
?>
