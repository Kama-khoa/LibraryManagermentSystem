<?php
include_once 'Model.php';
class Cart extends Model
{
    protected $table_name = 'cart';

    public $cart_id;
    public $user_id;
    public $created_at;
    public $updated_at;

    public function __construct(){
        parent::__construct();
    }

    public function create() {
        $query = "INSERT INTO cart(user_id, created_at) VALUES (:user_id, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
    public function getCartByUserId($user_id)
    {
        $query = "SELECT * FROM cart WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createCart($user_id)
{
    $query = "INSERT INTO cart (user_id, created_at) VALUES (:user_id, NOW())";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $this->conn->lastInsertId();  // Trả về ID của cart vừa được tạo
}

public function getCartItem($cart_id, $book_id)
{
    $query = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);  // Trả về một bản ghi
}

public function addCartItem($cart_id, $book_id, $quantity)
{
    $query = "INSERT INTO cart_items (cart_id, book_id, quantity) VALUES (:cart_id, :book_id, :quantity)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();
}

    public function updateCartItem($cart_id, $book_id, $quantity)
{
    $query = "UPDATE cart_items SET quantity = :quantity WHERE cart_id = :cart_id AND book_id = :book_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->execute();
}

public function show($user_id) {

    $query = "
        SELECT b.book_id, b.title, c.quantity  as borrow_quantity, c.cart_item_id, b.cover_image
        FROM cart_items c
        JOIN book b ON c.book_id = b.book_id
        WHERE c.cart_id = (
            SELECT cart_id 
            FROM cart 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC 
            LIMIT 1
        )";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function removeCartItem($user_id, $book_id)
{
    $query = "
        DELETE c 
        FROM cart_items c 
        JOIN cart ca ON c.cart_id = ca.cart_id 
        WHERE ca.user_id = :user_id AND c.book_id = :book_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->execute();
}

public function getBookAvailability($book_id)
    {
        $query = "SELECT b.book_id, b.title, b.available_quantity , GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors
                 FROM book b
                LEFT JOIN 
                book_author ba ON b.book_id = ba.book_id
                LEFT JOIN 
                author a ON ba.author_id = a.author_id
                WHERE b.book_id = :book_id
                GROUP BY b.book_id, b.title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
}