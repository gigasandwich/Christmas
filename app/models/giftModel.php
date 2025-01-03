<?php

namespace App\Models;

use PDO;

class GiftModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllGifts() {
        $query = "SELECT 
                    g.gift_id, 
                    g.gift_name, 
                    g.price, 
                    g.description, 
                    g.stock_quantity, 
                    g.pic, 
                    c.category_name 
                  FROM 
                    christmas_gift g
                  LEFT JOIN 
                    christmas_category c 
                  ON 
                    g.category_id = c.category_id";
        $STH = $this->db->prepare($query);
        $STH->execute();
        return $STH->fetchAll(PDO::FETCH_ASSOC);
    }
}
