<?php

// Include pagination library file 
include_once 'pagination.class.php';

// Include database configuration file 
require_once 'config.php';

// Set some useful configuration 
$limit = 2;

// Count of all records 
$query   = $db->query("SELECT COUNT(*) as rowNum FROM users");
$result  = $query->fetch_assoc();
$rowCount = $result['rowNum'];

// Initialize pagination class 
$pagConfig = array(
    'totalRows' => $rowCount,
    'perPage' => $limit,
    'contentDiv' => 'dataContainer', //id do container
    'link_func' => 'columnSorting' //função js
);
$pagination =  new Pagination($pagConfig);

// Fetch records based on the limit 
$query = $db->query("SELECT * FROM users LIMIT $limit");

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        html,
        body {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background-color: #FAFAFA;
        }

        .review-pagination {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .review-pagination .current {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 30px;
            min-width: 30px;
            border: 1px solid #cccccc;
            border-radius: 3px;
            margin: 0;
            white-space: nowrap;
            background: #FFF;
            color: #EC1F24;
            font-weight: 400;
        }

        .review-pagination .pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 30px;
            min-width: 30px;
            width: auto;
            border-radius: 3px;
            border: 1px solid #cccccc;
            font-size: 14px;
            text-decoration: none;
            margin: 0;
            white-space: nowrap;
            background: #FFF;
            color: #777;
        }

        .review-pagination .first-last {
            padding: 0 5px;
            color: #777;
        }
    </style>

    <style>
        #rating-mobile {
            display: none;
        }

        #dataContainer {
            padding: 0 130px;
        }

        #dataContainer h1 {
            position: relative;
        }

        #anchor-name {
            position: absolute;
            content: "";
            top: -200px;
            height: 1px;
            width: 1px;
            visibility: hidden;
        }

        #rating-mobile-tab {
            width: 100%;
            max-width: 310px;
            overflow: auto;
            background-color: #fff;
            position: fixed;
            z-index: 2000;
            top: 0;
            bottom: 0;
            right: 0;
            transform: translateX(380px);
            transition: transform 0.3s;
        }

        #rating-mobile-tab.open {
            transform: translateX(0);
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .stars-rating {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 20px;
            width: 80%;
            margin: 0 auto;
        }

        .stars-rating .average {
            display: flex;
            flex-direction: row;
            gap: 5px;
            align-items: center;
            justify-content: flex-start;
        }

        .stars-rating .five-stars {
            display: flex;
            flex-direction: column;
        }

        .stars-rating .five-stars .star-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: baseline;
            gap: 5px;
            padding: 8px 0;
        }

        .stars-rating .five-stars .star-container .bar {
            width: 200px;
            height: 5px;
        }

        .reviews {
            display: flex;
            flex-direction: column;
            width: 80%;
            height: auto;
            background-color: #fafafa;
            margin: 0 auto;
        }

        .review {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
            padding: 20px;
        }

        .review .user-review-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
        }

        .review .user-review-info .user-info-box {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: 100%;
            flex-direction: row;
            gap: 5px;
        }

        .review .user-review-info .user-info-box .profile-picture {
            width: 30px;
            height: 30px;
            background-color: grey;
            border-radius: 50%;
        }

        .review .user-review-info .user-info-box .name {
            text-align: start;
        }

        .product-review-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            gap: 10px;
        }

        .content {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .content .review-content {
            overflow: hidden;
            text-align: justify;
            line-height: 16px;
        }

        .review-images {
            display: flex;
            align-items: center;
            flex-direction: row;
            justify-content: flex-start;
            width: 100%;
            gap: 10px;
        }

        .review-image-container {
            display: flex;
            align-items: center;

            justify-content: center;
            max-width: 80px;
            max-height: 80px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }

        .review-images .review-image {
            object-fit: contain;
            width: 100%;
        }

        .ratings {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }

        .date {
            font-weight: 200;
            font-size: 12px;
        }

        .row-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .ratings .review-box {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }

        .close-rating-mobile {
            width: auto;
            height: 30px;
            background-color: transparent;
            position: absolute;
            z-index: 2001;
            top: 0;
            left: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-review-info .read-more {
            cursor: pointer;
            color: rgba(0, 0, 255, 0.39);
            width: auto;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-right: 5px;
            position: relative;
        }

        .user-review-info .read-more.exists::before {
            content: "";
            position: absolute;
            height: 16px;
            width: 100%;
            top: -17px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), #fafafa);
        }

        .verified-order {
            font-size: 12px;
            text-align: end;
            font-weight: 500;
            color: rgba(24, 140, 0, 0.57);
        }

        @media (max-width: 768px) {
            #dataContainer {
                display: none;
            }

            .average {
                display: flex;
                flex-direction: row;
                gap: 5px;
                align-items: center;
                justify-content: space-between;
            }

            .average-info {
                display: flex;
                flex-direction: row;
                gap: 5px;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
            }

            .reviews .review {
                margin-top: 0;
                padding: 20px 0px;
            }

            #rating-mobile-tab {
                display: flex;
            }

            #rating-mobile {
                display: flex;
                flex-direction: column;
            }

            .reviews {
                padding: 0 20px;
                width: 100%;
            }

            .review {
                flex-direction: column-reverse;
            }

            .product-review-info {
                align-items: flex-start;
            }

            .product-review-info .content {
                text-align: left;
            }

            .product-review-info .content .review-content {
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 5;
                -webkit-box-orient: vertical;
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .user-review-info .read-more.exists::before {
                content: "";
                position: absolute;
                height: 16px;
                width: 100%;
                top: -14px;
                background: linear-gradient(to bottom, rgba(255, 255, 255, 0), #fafafa);
            }

            .stars-rating .average {
                display: flex;
                flex-direction: row;
                gap: 5px;
                align-items: center;
                justify-content: flex-start;
                height: 20px;
            }

            .ratings .review-box {
                display: none;
            }

            .stars-rating {
                width: 100%;
            }
        }
    </style>

    <div class="datalist-wrapper" style="display: flex; align-items: center; justify-content: center; height: 100%;">
        <!-- Loading overlay -->


        <!-- Data list container -->
        <div id="dataContainer">
            <div class="table table-striped maça">
                <?php
                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                ?>
                        <div class="review">
                            <div class="user-review-info">
                                <div class="user-info-box">
                                    <span class="profile-picture"></span>
                                    <span class="name"><?php echo $row["name"]; ?></span>
                                </div>
                                <div class="row-container">
                                    <div class="rating-box">
                                        <div class="rating" style="width: 80%"></div>
                                    </div>
                                    <span class="verified-order">compra verificada</span>
                                </div>
                                <span class="date"><?php echo $row["created"]; ?></span>
                                <div class="content">
                                    <span class="review-content"><?php echo $row["body"]; ?></span>
                                    <span class="read-more"></span>
                                </div>
                            </div>

                            <div class="review-images">
                                <div class="review-image-container">
                                    <img class="review-image" src="https://picsum.photos/200" alt="" />
                                </div>
                                <div class="review-image-container">
                                    <img class="review-image" src="https://picsum.photos/200" alt="" />
                                </div>
                                <div class="review-image-container">
                                    <img class="review-image" src="https://picsum.photos/200" alt="" />
                                </div>
                                <div class="review-image-container">
                                    <img class="review-image" src="https://picsum.photos/200" alt="" />
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6">No records found...</td></tr>';
                }
                ?>

            </div>
            <!-- Display pagination links -->
            <?php echo $pagination->createLinks(); ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function columnSorting(page_num) {
            page_num = page_num ? page_num : 0;

            $.ajax({
                type: 'POST',
                url: 'getData.php',
                data: 'page=' + page_num,
                beforeSend: function() {
                    $('.loading-overlay').show();
                },
                success: function(html) {
                    $('#dataContainer').html(html);
                    $('.loading-overlay').fadeOut("slow");
                }
            });
        }
    </script>
</body>

</html>