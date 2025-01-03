<?php
    class ProductModel {
        public function insert_product($category_id, $name, $image, $quantity, $price, $sale_price, $details, $short_description) {
           
           $sql = "INSERT INTO products 
           (category_id, name, image, quantity, price, sale_price, details, short_description)
            VALUES (?,?,?,?,?,?,?,?)";

            pdo_execute($sql, $category_id, $name, $image, $quantity, $price, $sale_price, $details, $short_description);
        }

        public function select_products() {
            $sql = "SELECT name FROM products WHERE status = 1";

            return pdo_query($sql);
        }

        public function update_product_not_active($product_id) {
            $sql = "UPDATE products SET status = 0 WHERE product_id = ?";

            pdo_execute($sql, $product_id);
        }

        public function update_product_active($product_id) {
            $sql = "UPDATE products SET status = 1 WHERE product_id = ?";

            pdo_execute($sql, $product_id);
        }

        function select_list_products($keyword, $id_danhmuc, $page, $perPage) {
            // Tính toán vị trí bắt đầu của kết quả trên trang hiện tại
            $start = ($page - 1) * $perPage;
        
            // Bắt đầu câu truy vấn SQL
            $sql = "SELECT * FROM products WHERE 1";
            
            // Thêm điều kiện tìm kiếm theo keyword
            if($keyword != '') {
                $sql .= " AND name LIKE '%" . $keyword . "%'";
            }
        
            // Thêm điều kiện tìm kiếm theo id_danhmuc
            if($id_danhmuc > 0) {
                $sql .= " AND category_id ='" . $id_danhmuc . "'";
            }
        
            // Sắp xếp theo id giảm dần
            $sql .= " AND status = 1 ORDER BY product_id DESC";
        
            // Thêm phần phân trang
            $sql .= " LIMIT " . $start . ", " . $perPage;
        
            return pdo_query($sql);
        }

        public function select_recycle_products() {
            $sql = "SELECT * FROM products WHERE status = 0 ORDER BY product_id DESC";

            return pdo_query($sql);
        }

        public function select_product_by_id($product_id) {
            $sql = "SELECT * FROM products WHERE product_id =?";

            return pdo_query_one($sql, $product_id);
        }

        public function discount_percentage($price, $sale_price) {
            $discount_percentage = ($price - $sale_price) / $price * 100;

            $round__percentage = round($discount_percentage, 0)."%";
            return $round__percentage;
        }

        public function formatted_price($price) {
            $format = number_format($price, 0, ',', '.') . 'đ';
            return $format;
        }

        // Delete
        public function delete_product($product_id) {
            $sql = "DELETE FROM products WHERE product_id = ?";
            pdo_execute($sql, $product_id);
        }

        public function update_product($category_id, $name, $image, $quantity, $price, $sale_price, $details, $short_description, $product_id) {
            $sql = "UPDATE products SET 
            category_id = '".$category_id."', 
            name = '".$name."',";
    
            if ($image != '') {
                $sql .= " image = '".$image."',";
            }

            $sql .= " quantity = '".$quantity."', 
                    price = '".$price."', 
                    sale_price = '".$sale_price."', 
                    details = '".$details."', 
                    short_description = '".$short_description."' 
                    WHERE product_id = ".$product_id;
            
            
            pdo_execute($sql);
        }
    }

    $ProductModel = new ProductModel();
?>