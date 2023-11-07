<?php
$conn = mysqli_connect('localhost', 'root', 'ehdwnWkd123@', 'dongju');
if (mysqli_connect_error($conn)) {
    echo 'Connection Error';
    exit();
}
//날짜마다의 평균가격
$mean_sql = 'SELECT post_date, AVG(goods_price) as mean
    FROM usedgoods
    WHERE shoes_id = ' . $_GET['id'] .'
    GROUP BY post_date;';

//7일동안의 최고 최저 가격
$minmax_sql = 'SELECT MAX(goods_price) AS maximum, MIN(goods_price) AS minimum
    FROM usedgoods
    WHERE shoes_id1 = ' . $_GET['id'] . ' and DATEDIFF(CURDATE(), post_date) <=7;';

//현재 신발가격과 중고 신발의 평균의 차이
$diff_with_cur_used_sql = 'SELECT current_price - AVG(goods_price) AS diff
    FROM usedgoods JOIN shoes ON usedgoods.shoes_id1 = shoes.shoes_id
    WHERE shoes_id = ' . $_GET['id'] . ';';

//중고물품의 개수
$cnt_goods_sql = 'SELECT post_date, COUNT(*) AS "cnt"
    FROM usedgoods
    WHERE shoes_id1 = ' . $_GET['id'] . ' 
    GROUP BY post_date;';


$mean_result = mysqli_query($conn, $mean_sql);
$minmax_result = mysqli_query($conn, $minmax_sql);
$diff_result = mysqli_query($conn, $diff_with_cur_used_sql);
$cnt_result = mysqli_query($conn, $cnt_goods_sql);

$mean_arr = array();
$date_arr = array();
$goods_cnt_arr = array();




while($row = mysqli_fetch_array($mean_result)){
    array_push($mean_arr, $row['mean']);
    array_push($date_arr, $row['post_date']);
}
// while($row = mysqli_fetch_array($cnt_result)){
//     array_push($goods_cnt_arr, $row['cnt']);
// }
?>

