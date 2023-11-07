<!DOCTYPE html>
<html lang="ko-kr">

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="views.css">
    <script src="main.js"></script>
    <link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(function() {
            $('.videoSlide').slick({
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 3,
                responsive: [{
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 3
                        }
                    }
                ]
            });
        });
        $(function() {
            $('.goodsSlide').slick({
                slidesToShow: 3,
                slidesToScroll: 3
            });

            var filtered = true;

            $('.js-filter').on('click', function() {
                if (filtered === false) {
                    $('.filtering').slick('slickFilter', ':even');
                    $(this).text('Unfilter Slides');
                    filtered = true;
                } else {
                    $('.filtering').slick('slickUnfilter');
                    $(this).text('Filter Slides');
                    filtered = false;
                }
            });
        });
    </script>



    <title>신발 상세정보</title>
</head>
<?php

$conn = mysqli_connect('localhost', 'root', 'ehdwnWkd123@', 'dongju');
if (mysqli_connect_error($conn)) {
    echo 'Connection Error';
    exit();
}
//날짜마다의 평균가격
$mean_sql = 'SELECT post_date, AVG(goods_price) as "mean"
    FROM usedgoods
    WHERE shoes_id1 = ' . $_GET["id"] . '
    GROUP BY post_date;';

//7일동안의 최고 최저 가격
$minmax_sql = 'SELECT MAX(goods_price) AS maximum, MIN(goods_price) AS minimum
    FROM usedgoods
    WHERE shoes_id1 = ' . $_GET['id'] . ' and DATEDIFF(CURDATE(), post_date) <= 7;';

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




while ($row = mysqli_fetch_array($mean_result)) {
    array_push($mean_arr, $row['mean']);
    array_push($date_arr, $row['post_date']);
}
while ($row = mysqli_fetch_array($cnt_result)) {
    array_push($goods_cnt_arr, $row['cnt']);
}
?>

<body>
    <h1>모 신 정</h1>
    <h2 class='header2'>-- 모든 신발 정보 --</h2>
    <div class="container">
        <div class="shoes_detail">
            <?php
            $conn = mysqli_connect('localhost', 'root', 'ehdwnWkd123@', 'dongju');
            if (mysqli_connect_error($conn)) {
                echo 'Connection Error';
                exit();
            }
            $shoes_sql = 'SELECT * FROM shoes WHERE shoes_id = ' . $_GET['id'] . ';';
            $goods_sql = 'SELECT DISTINCT shoes_id, goods_url, goods_image, size, goods_title, goods_price, post_date
                FROM shoes, usedgoods
                WHERE shoes_id = ' . $_GET['id'] . ' and shoes.shoes_id = usedgoods.shoes_id1;';


            $video_sql = 'SELECT DISTINCT video_id, video_url, video_name, video_thumb, views, youtuber
                    FROM shoes, youtube
                    WHERE shoes_id = ' . $_GET['id'] . ' and shoes.shoes_id = youtube.shoes_id2;';

            $price_sql = 'SELECT MAX(goods_price) AS maximum, MIN(goods_price) AS minimum
                            FROM usedgoods
                            WHERE shoes_id1 = '. $_GET['id'] .' and DATEDIFF(CURDATE(), post_date) <=7;';

            $shoes_result = mysqli_query($conn, $shoes_sql);
            $goods_result = mysqli_query($conn, $goods_sql);
            $video_result = mysqli_query($conn, $video_sql);
            $price_sql = mysqli_query($conn, $price_sql);

            $shoes_id = 0;
            $brand = "";
            $thumbnail = "";
            $english_name = "";
            $name = "";
            $current_price = 0;
            $model_number = "";
            $color = "";
            $release_date = "";
            $release_price = "";
            

            $min_price = 0;
            while ($row = mysqli_fetch_array($price_sql)) {
                $min_price = $row['minimum'];
            }


            while ($row = mysqli_fetch_array($shoes_result)) {
                $shoes_id = $row['shoes_id'];
                $brand = $row['brand'];
                $thumbnail = $row['thumb_image'];
                $english_name = $row['english_name'];
                $name = $row['name'];
                $current_price = $row['current_price'];
                $model_number = $row['model_number'];
                $color = $row['color'];
                $release_date = $row['release date'];
                $release_price = $row['release_price'];
            }
            $fluc_rate = round(($current_price - $release_price) / $release_price * 100, 2);

            if (!$goods_result) {
                printf("Error: %s\n", mysqli_error($conn));
                exit();
            }

            echo "<img src = $thumbnail class = 'thumbnail'/>";
            echo "<div class = 'details'>
                    <ul class='detail_list'>
                        <li class='brnad to-right-underline'>브랜드 : $brand</li>
                        <li class='english_name to-right-underline'>상품명(영문) : $english_name</li>
                        <li class='name to-right-underline'>상품명 : $name</li>
                        <li class='name to-right-underline'>모델번호 : $model_number</li>
                        <li class='release_price to-right-underline'>발매가격 : $release_price 원</li>
                        <li class='current_price to-right-underline'>현재가격 : $current_price 원</li>
                        <li class='fluc_rate to-right-underline'>등락률 : <span style='font-weight:bold;'>$fluc_rate%</span></li>
                        <li class='release_date to-right-underline'>발매일자 : $release_date</li>
                    </ul>
                  </div>";
            if ($fluc_rate > 0) {
                echo "<style>.fluc_rate{color:blue;}</style>";
            } else if ($fluc_rate < 0) {
                echo "<style>.fluc_rate{color:red;}</style>";
            }
            ?>
        </div>
        <h2 class="divider line one-line">관련된 유튜브 영상</h2>
        <p class="explain" style="padding-top:27px;">
            지금 보고계신 신발과 관련있는 영상을 볼 수 있습니다. <br>
            <span style="text-decoration:underline">영상 보러가기 버튼</span>을 클릭하여 다양한 영상을 시청하세요.
        </p>
        <div class="youtube_data">
            <div class='videoSlide'>
                <?php
                while ($row = mysqli_fetch_array($video_result)) {
                    echo '
                            <div class="videoCard">
                                <img src="' . $row["video_thumb"] . '">
                                <ul>
                                    <li>영상제목 : ' . $row["video_name"] . '</li>
                                    <li>유튜버 : ' . $row["youtuber"] . '</li>
                                    <li>조회수 : ' . $row["views"] . '회</li>
                                </ul>
                                <a href="' . $row["video_url"] . '">
                                <div class="videoButton">!영상보러가기!</div></a>


                            </div>

                            
                        ';
                }
                ?>
            </div>
        </div>

        <h2 class="divider line one-line">중고상품들</h2>
        <p class="explain">
            추이를 보시면 색깔별로 가격이 비싼지, 적당한지, 싼지, 사기에 위험이 있는지에 대해 알 수 있습니다.<br>
            비쌈 : <span style="color: green;">초록색</span>, 적당 : <span style="color:skyblue;">하늘색</span> , 쌈 : <span style="color:blue ;">파랑색</span> , 가격이상 : <span style="color:red;">빨강</span><br>
            중고거래 사기가 걱정된다면 <a href="https://thecheat.co.kr/rb/?mod=_search">더치트</a>에 판매자의 정보를 검색해보세요.
        </p>
        <div class="goods_data">
            <p class="min_price">일주일 내 상품의 최저가 <span class="min"><?php echo "$min_price" ?></span> 원</p>
            <div class='goodsSlide'>

                <?php
                while ($row = mysqli_fetch_array($goods_result)) {
                    $diff_rate = round($row['goods_price'] / $current_price * 100, 2);
                    $color = 'green';
                    if ($diff_rate < 50) {
                        $color = "red";
                    } else if ($diff_rate < 70) {
                        $color = "blue";
                    } else if ($diff_rate < 85) {
                        $color = "skyblue";
                    }
                    echo '
                            <div class="goodsCard">
                                <img src="' . $row["goods_image"] . '">
                                <ul>
                                    <li>상품명 : ' . $row["goods_title"] . '</li>
                                    <li>상품 가격 : ' . $row["goods_price"] . '</li>
                                    <li>현재가격과 중고가격의 추이 : <span class = "diff" style="color:' . $color . '">' . $diff_rate . '%</span></li>
                                    <li>게시일 : ' . $row["post_date"] . '</li>
                                </ul>
                                <a href="' . $row["goods_url"] . '" class="agood">
                                <div class="goodsButton">!상품 보러가기!</div></a>
                            </div>     
                        ';
                }
                ?>
            </div>
        </div>
        <h2 class="divider line one-line">그 외 정보(그래프)</h2>
        <div class="graph_container">
            <div class="meanChart chart" style="width: 650px;height: 400px;">
                <canvas id="meanChart"></canvas>
            </div>
            <div class='countChart chart' style="width: 650px; height: 400px;">
                <canvas id="countChart"></canvas>
            </div>
        </div>

        <!-- 하루하루 평균가격 차트 js -->
        <script>
            const ctx1 = document.getElementById('meanChart');

            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($date_arr) ?>,
                    datasets: [{
                        label: '평균가격',
                        data: <?php echo json_encode($mean_arr) ?>,
                        borderWidth: 4,
                        backgroundColor: [
                            'rgba(255, 26, 104, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                        ],
                    }]
                },


                scales: {
                    xAxes: [{
                        display: true,
                        type: 'time',
                        time: {
                            parser: 'YYYY-MM-DD',
                            unit: 'day',
                            unitStepSize: 1,
                            displayFormats: {
                                'day': 'MM/DD/YYYY'
                            }
                        }
                    }],
                    y: {
                        beginAtZero: true
                    }
                }

            });
        </script>


        <!-- 물품의 개수 차트 js -->
        <script>
            const ctx2 = document.getElementById('countChart');

            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($date_arr) ?>,
                    datasets: [{
                        label: '게시물 수',
                        data: <?php echo json_encode($goods_cnt_arr) ?>,
                        borderWidth: 0,
                        backgroundColor: [
                            'rgba(255, 26, 104, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(255, 159, 64, 0.5)',
                        ],
                    }]
                },


                scales: {
                    xAxes: [{
                        display: true,
                        type: 'time',
                        time: {
                            parser: 'YYYY-MM-DD',
                            unit: 'day',
                            unitStepSize: 1,
                            displayFormats: {
                                'day': 'MM/DD/YYYY'
                            }
                        }
                    }],
                    y: {
                        beginAtZero: true
                    }
                }
            });
        </script>

    </div>
</body>

</html>