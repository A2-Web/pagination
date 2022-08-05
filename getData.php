<?php
if (isset($_POST['page'])) {
    // Include pagination library file 
    include_once 'pagination.class.php';

    // Include database configuration file 
    require_once 'config.php';

    // Set some useful configuration 
    $offset = !empty($_POST['page']) ? $_POST['page'] : 0;
    $limit = 2;


    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM users");
    $result  = $query->fetch_assoc();
    $rowCount = $result['rowNum'];

    // Initialize pagination class 
    $pagConfig = array(
        'totalRows' => $rowCount,
        'perPage' => $limit,
        'currentPage' => $offset,
        'contentDiv' => 'dataContainer',
        'link_func' => 'columnSorting'
    );

    $pagination =  new Pagination($pagConfig);

    // Fetch records based on the offset and limit 
    $query = $db->query("SELECT * FROM users LIMIT $offset,$limit");
?>
    <!-- Data list container -->
    <div class="table table-striped banana" style="position:relative;">
        <div class="loading-overlay" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;z-index: 100;">
            <div class="overlay-content" style="width: 100%;height: 100%;display: flex;align-items: center;justify-content: center;">Loading...</div>
        </div>
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
<?php
}
?>