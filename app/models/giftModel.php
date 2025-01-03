<?php

namespace App\Models;

use PDO;
use Flight;

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
                * -- g.gift_id, g.gift_name, g.price, g.description, g.stock_quantity, g.pic, c.category_name 
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

  public function getGiftSuggestions($boys, $girls)
  {
    $balance = Flight::userModel()->getActualUserBalance();
    $suggestions = [];
    $remainingBalance = $balance;
    $totalChildren = $boys + $girls;
    if ($totalChildren === 0 || $remainingBalance <= 0)
      return [];

    // Boys
    for ($i = 0; $i < $boys; $i++) {
      $randomBalance = rand(1, floor($remainingBalance)); // No float problem
      $gift = $this->getGift([2, 3], $randomBalance);
      if ($gift) {
        $suggestions[] = $gift;
        $remainingBalance -= $gift['price'];
      }
    }

    // Girs
    for ($i = 0; $i < $girls; $i++) {
      $randomBalance = rand(1, floor($remainingBalance));
      $gift = $this->getGift([1, 3], $randomBalance);
      if ($gift) {
        $suggestions[] = $gift;
        $remainingBalance -= $gift['price'];
      }
    }

    // In case of rest 
    $leftoverChildren = $boys + $girls - count($suggestions);
    while ($remainingBalance > 0 && $leftoverChildren > 0) {
      $maxPrice = floor($remainingBalance / $leftoverChildren);

      // Attempt to allocate additional gifts
      $additionalGift = $this->getGift([1, 2, 3], $maxPrice);
      if ($additionalGift) {
        $suggestions[] = $additionalGift;
        $remainingBalance -= $additionalGift['price'];
        $leftoverChildren--;
      } else {
        // Break if no suitable gift is found 
        break;
      }
    }

    // Add an index column and store with sesssion to make it replacable after
    $i = 0;
    $suggestions = array_map(function ($suggestion) use (&$i) {
      $suggestion['index'] = $i++;
      return $suggestion;
    }, $suggestions);

    $_SESSION['gift_suggestions'] = $suggestions;

    return $suggestions;
  }

  // Helper method for getGiftSuggestions
  function getGift($categories, $maxPrice)
  {
    $categoryCondition = implode(' OR ', array_map(function ($id) {
      return "category_id = $id";
    }, $categories));
    $query = "
          SELECT * FROM christmas_gift
          WHERE ($categoryCondition) AND stock_quantity > 0 AND price <= $maxPrice
          ORDER BY RAND()
          LIMIT 1
      ";

    $STH = $this->db->query($query);
    return $STH->fetch(PDO::FETCH_ASSOC);
  }

  public function replaceGift($index)
  {
    $suggestions = $_SESSION['gift_suggestions'];
    $oldGift = $suggestions[$index];
    $categoryId = $oldGift['category_id'];

    // Calculate the remaining balance and total amount
    $balanceData = $this->getRemainingBalanceAndTotal();
    $remainingBalance = $balanceData['remaining_balance'];

    // Fetch a new gift in the same category and within the remaining balance
    $query = "
        SELECT * FROM christmas_gift
        WHERE category_id = $categoryId
          AND stock_quantity > 0
          AND price <= $remainingBalance
          AND gift_id != {$oldGift['gift_id']}
        ORDER BY RAND()
        LIMIT 1
    ";
    $newGift = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);

    if (!$newGift) {
      return ['error' => 'No suitable replacement gift found'];
    }

    // Replace the old gift
    $suggestions[$index] = $newGift;
    $_SESSION['gift_suggestions'] = $suggestions;

    return $newGift;
  }

  public function getRemainingBalanceAndTotal()
  {
    $suggestions = $_SESSION['gift_suggestions'] ?? null;

    if (!$suggestions) {
      return ['error' => 'No gift suggestions available'];
    }

    $totalAmount = 0;

    foreach ($suggestions as $gift) {
      $totalAmount += $gift['price'];
    }

    $remainingBalance = Flight::userModel()->getActualUserBalance() ?? 0;

    return [
      'total_amount' => $totalAmount,
      'remaining_balance' => $remainingBalance - $totalAmount
    ];
  }

  public function finalizeSelections($userId)
  {
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

    unset($_SESSION['gift_suggestions']);

    return ['success' => 'Gifts finalized successfully'];
  }

}
