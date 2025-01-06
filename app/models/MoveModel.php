<?php

namespace app\Models;

use PDO;

class MoveModel {
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * ---------------------------
     * SELECTING interaction
     * ---------------------------
     */
    public function getAll()
      {
        $query = "SELECT  m.move_id, m.user_id, m.amount, m.description, m.move_date, username
                  FROM 
                      christmas_move m
                  JOIN 
                      christmas_user u 
                  ON 
                      m.user_id = u.user_id";
        $STH = $this->db->prepare($query);
        $STH->execute();
        return $STH->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDeposits() {
        $query = "SELECT  * FROM christmas_user_deposits_view";
        $STH = $this->db->prepare($query);
        $STH->execute();
        return $STH->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getNonAcceptedDeposits() {
        $query = "SELECT  * FROM christmas_non_accepted_deposits_view";
        $STH = $this->db->prepare($query);
        $STH->execute();
        return $STH->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------
     * ADD/REMOVE/UPDATE 
     * ---------------------------
     */
    public function addDeposit($userId, $amount) {
        $query = "INSERT INTO christmas_move (user_id, amount, description) VALUES (?, ?, ?)";
        $STH = $this->db->prepare($query);
        if ($STH->execute([$userId, $amount, 'Deposit']))
            return true;
        return false;
    }

    public function acceptDeposit($deposit_id) {
        $query = "UPDATE christmas_move SET is_accepted = 1 WHERE move_id = ?";
        $STH = $this->db->prepare($query);
        
        if ($STH->execute([$deposit_id]))
            return true;
        return false;
    }

    public function rejectDeposit($deposit_id) {
      $query = "DELETE FROM christmas_move WHERE move_id = ?";
      $STH = $this->db->prepare($query);
      
      if ($STH->execute([$deposit_id]))
          return true;
      return false;
  }
}
