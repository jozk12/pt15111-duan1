<?php
session_start();
require_once "./config/utils.php";
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : "";
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : "";
$adults = isset($_GET['adults']) ? $_GET['adults'] : "";
$children = isset($_GET['children']) ? $_GET['children'] : "";

$getWebSettingQuery = "select * from web_settings where status = 1";
$webSetting = queryExecute($getWebSettingQuery, false);

$getRoomQuery = "select r.* from room_types r";
if ($adults !== "" && $children !== "") {
    $getRoomQuery .= " where (r.adults like'%$adults%'
                                    or r.children like '%$children%') ORDER BY r.id DESC";
} else {
    $getRoomQuery .= " where status = 1 ORDER BY r.id DESC";
}
$rooms = queryExecute($getRoomQuery, true);

for ($i = 0; $i < count($rooms); $i++) {
    $getServiceQuery = "select s.id, 
                        s.name
                        from room_service_xref sxr
                        join room_services s
                        on sxr.service_id = s.id
                        where sxr.room_id = " . $rooms[$i]['id'];
    $services = queryExecute($getServiceQuery, true);
    $rooms[$i]['room_sv'] = $services;
}

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<?php include_once './_share/styles.php' ?>
</head>

<body>

    <!-- Site Back Top -->
    <?php include_once './_share/back_top.php' ?>
    <!-- Site Back Top End -->

    <!-- Site Header -->
    <?php include_once './_share/header.php' ?>
    <!-- Site Header End -->

    <!-- Site Main -->
    <div class="site-main">
        <!-- Section Rooms -->
        <div class="section">
            <div class="wrapper-inner">
                <!-- Rooms List -->
                <div class="widget-rooms-list">
                    <?php foreach ($rooms as $room) : ?>
                        <div class="rooms-item">
                            <div class="item-photo">
                                <a href="rooms-detail.php?id=<?= $room['id'] ?>&&checkin=<?=$checkin?>&&checkout=<?=$checkout?>" data-background="<?= BASE_URL . $room['image'] ?>"></a>
                            </div>
                            <div class="item-desc">
                                <h2><a href="rooms-detail.php?id=<?= $room['id'] ?>&&checkin=<?=$checkin?>&&checkout=<?=$checkout?>"><?= $room['name'] ?></a><small></small></h2>
                                <p><?= $room['short_desc'] ?></p>
                                <div class="desc-features">
                                    <ul>
                                        <?php foreach ($room['room_sv'] as $rService) : ?>
                                            <li><i class="fa fa-check"></i><?= $rService['name'] ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price-inner">
                                    <h3><?= $room['price'] ?> VNĐ</h3>
                                    <h5>PER NIGHT</h5>
                                    <h5><?= $room['adults']?> Người lớn & <?= $room['children']?> Trẻ em</h5>
                                    <a href="rooms-detail.php?id=<?= $room['id'] ?>&&checkin=<?=$checkin?>&&checkout=<?=$checkout?>" class="btn">ROOM DETAIL</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <!-- Rooms List End -->
                <!-- Pager -->
                <!-- <div class="widget-pager">
                        <ul>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                        </ul>
                    </div> -->
                <!-- Pager End -->
            </div>
        </div>
        <!-- Section Rooms End -->
    </div>
    <!-- Site Main End -->

    <!-- Site Footer -->
    <?php include_once './_share/footer.php' ?>
    <!-- Site Footer End -->

    <!-- Scripts -->
    <?php include_once './_share/scripts.php' ?>
    <!-- Map Scripts -->
    <?php include_once './_share/map_scripts.php' ?>

    <!-- Custom Scripts -->
    <?php include_once './_share/custom_scripts.php' ?>
    
    <!-- Message Scripts -->
    <?php include_once './_share/msg_info.php'?>
</body>

<!-- Mirrored from preview.locotheme.com/grandium-html/rooms.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 21 Mar 2020 07:28:17 GMT -->

</html>