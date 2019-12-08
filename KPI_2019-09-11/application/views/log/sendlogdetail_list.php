<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$total_count?>" >
<table class="search_t">
    <tr>
        <th>번호</th>
        <th>전송시각</th>
        <th>URL</th>
        <th>수신번호</th>
        <th>조회(Yes/No)</th>
        <th>전송결과</th>

    </tr>
    <?php
    $n=1;
    foreach ($success_result as $success):

        ?>
        <Tr>
            <td><?=$n?></td>
            <td><?=$success['send_time']?></td>
            <?php
                $url = "";
                $index = stripos($success['text'],"http");
                if($index !==false) {
                    $url = substr($success['text'],$index,strlen($success['text']));
                }
            ?>
            <td style='color:#ff0000'><a href="<?=$url?>" target="_blank"><?=$url?></a></td>

            <td><?=$success['dstaddr']?></td>
            <?php
            if($success['reply_count'] > 0) {
                echo "<td style='color:#ff0000'>Yes</td>";
            } else {
                echo "<td style='color:#ff0000'>No</td>";
            }

            ?>
                <?php
                if($success['stat'] < 3) {
                    echo "<td>송신중</td>";
                } else if($success['result']==='100') {
                    echo "<td>전송성공</td>";
                } else if($success['result']==='201') {
                    echo "<td>착신가입자없음</td>";
                } else if($success['result']==='208') {
                    echo "<td>사용정지된번호</td>";
                } else if($success['result']==='304') {
                    echo "<td>핸드폰꺼짐</td>";
                } else if($success['result']==='507') {
                    echo "<td>전송실패(중복에러507)</td>";
                } else {
                    echo "<td>실패</td>";
                }
                ?>


        </tr>
        <?php $n++; endforeach; ?>

</table>