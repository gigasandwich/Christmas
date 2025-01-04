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

    /**
     * ---------------------------
     * SELECT statement
     * ---------------------------
     */
    public function getAvailableGifts()
    {
        $query = "  SELECT 
                        * -- g.gift_id, g.gift_name, g.price, g.description, g.stock_quantity, g.pic, c.category_name 
                    FROM 
                        christmas_gift g
                    LEFT JOIN 
                        christmas_category c 
                    ON 
                        g.category_id = c.category_id
                    WHERE 
                        g.stock_quantity > 0    
                  ";
        $STH = $this->db->prepare($query);
        $STH->execute();
        return $STH->fetchAll(PDO::FETCH_ASSOC);
    }

    // Purchased gifts by an user
    public function getPurchasedGifts($userId)
    {
        $query = "  SELECT 
                        g.gift_id, g.gift_name, g.price, g.description, g.pic, t.quantity
                    FROM
                        christmas_gift_transaction t
                    JOIN
                        christmas_gift g ON t.gift_id = g.gift_id
                    WHERE
                        t.user_id = :userId
    ";

        $STH = Flight::db()->prepare($query);
        $STH->execute(['userId' => $userId]);
        return $STH->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------
     * Used by the DashboardController
     * ---------------------------
     */
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

        // Add column INDEX and store with sesssion to make it replacable after
        $i = 0;
        $suggestions = array_map(function ($suggestion) use (&$i) {
            $suggestion['index'] = $i++;
            return $suggestion;
        }, $suggestions);

        return $suggestions;
    }

    // Helper method for getGiftSuggestions
    function getGift($categories, $maxPrice)
    {
        $categoryCondition = implode(' OR ', array_map(function ($id) {
            return "category_id = $id";
        }, $categories));
        $query = "  SELECT  
                        * 
                    FROM
                        christmas_gift
                    WHERE 
                        ($categoryCondition) AND stock_quantity > 0 AND price <= $maxPrice
                    ORDER BY 
                        RAND()
                    LIMIT 1
      ";

        $STH = $this->db->query($query);
        return $STH->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------
     * Proper operations
     * ---------------------------
     */

    public function replaceGift($index, &$suggestions)// Adress so that the session can also change
    {
        $oldGift = $suggestions[$index];
        $categoryId = $oldGift['category_id'];

        // Calculate the remaining balance and total amount
        $balanceData = $this->getRemainingBalanceAndTotal($suggestions);
        $remainingBalance = $balanceData['remaining_balance'];

        // Fetch a new gift in the same category and within the remaining balance
        $query = "  SELECT 
                        * 
                    FROM 
                        christmas_gift
                    WHERE 
                        category_id = $categoryId AND stock_quantity > 0 AND price <= $remainingBalance AND gift_id != {$oldGift['gift_id']}
                    ORDER BY 
                        RAND()
                    LIMIT 1
    ";
        $newGift = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);

        if (!$newGift) 
            return ['error' => 'No suitable replacement gift found'];

        $suggestions[$index] = $newGift;
        $newGift['index'] = $index; // Add the column index to the new gift

        return $newGift;
    }

    public function getRemainingBalanceAndTotal($suggestions)
    {
        $totalAmount = 0;

        foreach ($suggestions as $gift) {
            $totalAmount += $gift['price'];
        }

        $remainingBalance = Flight::userModel()->getActualUserBalance();

        return [
            'total_amount' => $totalAmount,
            'remaining_balance' => $remainingBalance - $totalAmount
        ];
    }

    // The final thing to do
    public function finalizeSelections($userId, $suggestions)
    {

        // Too lazy to do prepare and exec
        foreach ($suggestions as $gift) {
            $gift_id = $gift['gift_id'];
            $stock_quantity = $gift['stock_quantity']--;
            // Add gifts to user
            $this->db->query("INSERT INTO christmas_gift_transaction (user_id, gift_id) VALUES ($userId, $gift_id)");
            // Decrease the amount of gift 
            $this->db->query("UPDATE christmas_gift SET stock_quantity = $stock_quantity WHERE gift_id = $gift_id");
        }
        // Retrieve money from the user
        $totalPrice = $this->calculateTotalPrice($suggestions);
        $this->db->query("INSERT INTO christmas_move (user_id, amount, description, is_accepted) VALUES ($userId, -$totalPrice, 'Payment', 1)");

        return true;
    }

    public function calculateTotalPrice($gifts)
    {
        $totalPrice = 0;

        foreach ($gifts as $gift) {
            $totalPrice += $gift['price'];
        }

        return number_format($totalPrice, 2);
    }


    /**
     * ---------------------------
     * Check methods operations
     * ---------------------------
     */
    public function canBuyGift($balance)
    {
        $query = "  SELECT
                         MIN(price) as min_price
                    FROM
                         christmas_gift
                    WHERE
                         stock_quantity > 0
    ";

        $STH = $this->db->query($query);
        $result = $STH->fetch(PDO::FETCH_ASSOC);

        $minPrice = $result['min_price'] ?? null;

        if (is_null($minPrice) || $balance < $minPrice) {
            return false;
        }

        return true;
    }


}
