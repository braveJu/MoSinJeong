<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>레트로 신발가게</title>
</head>

<body>
    <link rel="stylesheet" href="base.css">
    <h1>모 신 정</h1>
    <h2>-- 모든 신발 정보 --</h2>
    <embed class="wc" src="wordcloud.svg" type="">
    <p>
        신발 워드 클라우드를 보고 트렌드를 경험하세요.<br>
        - Shoes Wordcloud -
    </p>
    <?php
    $conn = mysqli_connect('localhost', 'root', 'ehdwnWkd123@', 'dongju');
    if (mysqli_connect_error($conn)) {
        echo ('Connection Error');
        exit();
    }


    $sql = "select * from shoes;";
    $result = mysqli_query($conn, $sql);


    $data_num = mysqli_num_rows($result);
    $page_num = ceil($data_num / 10);

    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }


    ?>
    <div class="container">
        <p class='explain'>여러가지 신발을 보세요. 약 2000 가지의 인기있는 신발을 준비했어요. 마음에 드는 신발을 클릭해주세요. <br>
            신발을 클릭하시면 신발의 다양한 정보를 알 수 있어요. 발매가격, 발매일자, 관련된 영상, 중고정보 등을 알아가세요.<br>
            인기순으로 정렬되어있으니 차근차근 둘러보시기 바랍니다.<br><br>
            - By Creator -
        </p>

        <div class="cards">
            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo ('
                <a href = "shoesview.php?id=' . $row['shoes_id'] . '">
                    <div class="card">
                        <img class = "thumb_image" src=' . $row['thumb_image'] . '>
                        <h4>'. $row['name'] . '</h4>
                    </div>
                </a>'
                );
            }
            ?>
        </div>
    </div>
</body>