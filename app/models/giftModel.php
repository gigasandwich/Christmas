<?php

namespace App\Models;

use PDO;

class GiftModel
{
  private $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getAllGifts()
  {
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

  public function getGiftSuggestions($boys, $girls, $balance)
  {
    $suggestions = [];
    $remainingBalance = $balance;

    // Helper function to fetch a gift
    $fetchGift = function ($categories, $maxPrice) use (&$remainingBalance) {
      $categoryCondition = implode(' OR ', array_map(fn($id) => "category_id = $id", $categories));
      $query = "
              SELECT * FROM christmas_gift
              WHERE ($categoryCondition) AND stock_quantity > 0 AND price <= $maxPrice
              ORDER BY RAND()
              LIMIT 1
          ";

      $result = $this->db->query($query);
      return $result->fetch_assoc();
    };

    // Generate gifts for boys and girls
    for ($i = 0; $i < $boys; $i++) {
      $gift = $fetchGift([2, 3], $remainingBalance);
      if ($gift) {
        $suggestions[] = $gift;
        $remainingBalance -= $gift['price'];
      }
    }
    for ($i = 0; $i < $girls; $i++) {
      $gift = $fetchGift([1, 3], $remainingBalance);
      if ($gift) {
        $suggestions[] = $gift;
        $remainingBalance -= $gift['price'];
      }
    }

    // Store suggestions in session
    $_SESSION['gift_suggestions'] = $suggestions;

    return ['gifts' => $suggestions, 'remaining_balance' => $remainingBalance];
  }

  public function replaceGift($index, $remainingBalance)
  {
    $suggestions = $_SESSION['gift_suggestions'] ?? null;

    if (!$suggestions || !isset($suggestions[$index])) {
      return ['error' => 'Gift not found in current suggestions'];
    }

    $oldGift = $suggestions[$index];
    $categoryId = $oldGift['category_id'];

    // Fetch a new gift in the same category
    $query = "
        SELECT * FROM christmas_gift
        WHERE category_id = $categoryId
          AND stock_quantity > 0
          AND price <= $remainingBalance
          AND gift_id != {$oldGift['gift_id']}
        ORDER BY RAND()
        LIMIT 1
    ";
    $newGift = $this->db->query($query)->fetch_assoc();

    if (!$newGift) {
      return ['error' => 'No suitable replacement gift found'];
    }

    // Replace the old gift in the session array
    $suggestions[$index] = $newGift;
    $_SESSION['gift_suggestions'] = $suggestions;

    return ['new_gift' => $newGift];
  }

  public function finalizeSelections($userId) {
    $suggestions = $_SESSION['gift_suggestions'] ?? null;

    if (!$suggestions) {
        return ['error' => 'No suggestions to finalize'];
    }

    foreach ($suggestions as $gift) {
        $this->db->query("
            INSERT INTO christmas_selected_gifts (user_id, gift_id)
            VALUES ($userId, {$gift['gift_id']})
        ");
    }

    // Clear the session after finalizing
    unset($_SESSION['gift_suggestions']);

    return ['success' => 'Gifts finalized successfully'];
}

}
