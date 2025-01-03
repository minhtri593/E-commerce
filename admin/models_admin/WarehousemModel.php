<?php
    class WarehousemModel {
        

        public function select_all_warehouse() {
            $sql = "SELECT * FROM warehouse ORDER BY id DESC";

            return pdo_query($sql);
        }


        public function insert_warehouse($name, $price, $quantity) {
            $sql = "INSERT INTO warehouse 
           (name, price, quantity)
            VALUES (?,?,?)";

            pdo_execute($sql, $name, $price, $quantity);
        }

        

        public function delete_warehouse($id) {
            $sql = "DELETE FROM warehouse WHERE id = ?";
            pdo_execute($sql, $id);
        }
    }

    $WarehousemModel = new WarehousemModel();
?>