<?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $list_products = $ProductModel->select_list_products($page, 9);
    $list_catgories = $CategoryModel->select_all_categories();


?>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="index.php"><i class="fa fa-home"></i> Trang chủ</a>
                        <span>Sản Phẩm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="shop__sidebar">
                        <div class="sidebar__categories">
                            <div class="section-title">
                                <h4>DANH MỤC</h4>
                            </div>
                            <div class="categories__accordion">
                                <div class="accordion" id="accordionExample">
                                    <?php foreach ($list_catgories as $value) {
                                        extract($value);
                                    ?>
                                    <div class="card">
                                        <div class="card-heading active">
                                            <a href="index.php?url=danh-muc-san-pham&id=<?=$category_id?>" ><?=$name?></a>
                                        </div>
                                        
                                    </div>
                                    <?php 
                                    }
                                    ?>
                                
                                    
                                </div>
                            </div>
                        </div>
                        <div class="sidebar__filter">
                            <div class="section-title">
                                <h4>TÌM THEO GIÁ</h4>
                            </div>
                            <div class="filter-range-wrap">
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="100000" data-max="5000000"></div>
                                <div class="range-slider">
                                    <form action="index.html" method="post">
                                        
                                        <div class="price-input">
                                            <p>Giá từ:</p> <br>
                                            <input type="text" id="minamount" > <p>đến</p>
                                            <input type="text" id="maxamount" > <br>

                                            <!-- Sử Dụng Thẻ a hoặc input de loc gia -->
                                            <input type="submit" class="filter-price" name="" value="LỌC GIÁ">
                                        </div>
                                    </form>
        
                                </div>
                            </div>
                            <!-- <a href="#">LỌC GIÁ</a> -->
                        </div>
                        
                        
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">
                    <div class="row">
                        <?php foreach ($list_products as $value) {
                            extract($value);
                            $discount_percentage = $ProductModel->discount_percentage($price, $sale_price);
                        ?>
                        <div class="col-lg-4 col-md-6 col-6-rp-mobile">
                            <div class="product__item sale">
                                <div class="product__item__pic set-bg" data-setbg="upload/<?=$image?>">
                                    <!-- <div class="label sale">New</div> -->
                                    <div class="label_right sale">-<?=$discount_percentage?></div>
                                    <ul class="product__hover">
                                        <li><a href="upload/<?=$image?>" class="image-popup"><span class="arrow_expand"></span></a></li>
                                        <li>
                                            <a href="index.php?url=chitietsanpham&id_sp=<?=$product_id?>&id_dm=<?=$category_id?>"><span class="icon_search_alt"></span></a>
                                        </li>
                                        
                                        
                                        <li>
                                        <?php if(isset($_SESSION['user'])) {?>
                                            <form action="index.php?url=gio-hang" method="post">
                                                <input value="<?=$product_id?>" type="hidden" name="product_id">
                                                <input value="<?=$_SESSION['user']['id']?>" type="hidden" name="user_id">
                                                <input value="<?=$name?>" type="hidden" name="name">
                                                <input value="<?=$image?>"type="hidden" name="image">
                                                <input value="<?=$sale_price?>" type="hidden" name="price">
                                                <input value="1" type="hidden" name="product_quantity">
                                                <input value="<?=$image?>" type="hidden" name="image">

                                                <button type="submit" name="add_to_cart" id="toastr-success-top-right">
                                                    <a href="#" ><span class="icon_bag_alt"></span></a>
                                                </button>
                                            </form>
                                        <?php }else{?>
                                            <button type="submit" onclick="alert('Vui lòng dăng nhập để thực hiện chức năng');" name="add_to_cart" id="toastr-success-top-right">
                                                <a href="dang-nhap" ><span class="icon_bag_alt"></span></a>
                                            </button>
                                        <?php }?>
                                        </li>
                                        
                                    </ul>
                                    
                                </div>
                                <div class="product__item__text">
                                    <h6 class="text-truncate-1"><a href="product-details.html"><?=$name?></a></h6>
                                    <div class="rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="product__price"><?=number_format($sale_price)."₫"?> <span><?=number_format($price)."đ"?> </span></div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        }
                        ?>

                        <?php
                            // Phân trang
                            $qty_product = $ProductModel->count_products();
                            $totalProducts = count($qty_product); // Tổng số sản phẩm
                            $productsPerPage = 9; // sản phẩm trên 1 trang

                            // Tính số trang
                            $totalProducts = intval($totalProducts);
                            $productsPerPage = intval($productsPerPage);
                            $numberOfPages = ceil($totalProducts / $productsPerPage);

                            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

                            $html_pagination = '';
                            $pagination_next = '';
                            $pagination_prev = '';
                            for ($i = 1; $i <= $numberOfPages; $i++) {
                                if ($i === $currentPage) {
                                    $active = 'active';
                                } else {
                                    $active = '';
                                }

                                $html_pagination .= '
                                    <a class="' . $active . '" href="index.php?url=cua-hang&page=' . $i . '">' . $i . '</a>
                                ';

                                //  Next
                                if ($currentPage < $numberOfPages) {
                                    $pagination_next = '
                                        <a href="index.php?url=cua-hang&page=' . ($currentPage + 1) . '"><i class="fa fa-angle-right"></i></a>
                                    ';
                                }

                                //  Prev
                                if ($currentPage > 1) {
                                    $pagination_prev = '
                                        <a href="index.php?url=cua-hang&page=' . ($currentPage - 1) . '"><i class="fa fa-angle-left"></i></a>
                                    ';
                                }
                            }
                        ?>
                        
                        <div class="col-lg-12 text-center">
                            <div class="pagination__option">
                                <?=$pagination_prev?>
                                <?=$html_pagination?>
                                <?=$pagination_next?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->