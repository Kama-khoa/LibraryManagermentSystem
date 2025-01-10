<?php
include_once 'Model.php';

class Loan extends Model
{
    protected $table_name = 'loan';

    public $loan_id;
    public $book_id;
    public $member_id;
    public $issued_date;
    public $due_date;
    public $notes;
    public $status;
    public $issued_by;
    public $created_at;
    public $updated_at;
    public $books = [];
    public function __construct()
    {
        parent::__construct();
    }

    // Tạo phiếu mượn
    public function create()
    {
        return parent::create();
    }

    // Đọc tất cả phiếu mượn
    public function read()
    {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin phiếu mượn theo ID
    public function readById($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE loan_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllLoans($user_id = null, $role_id = null) {
        
        if ($role_id == 2) {
            $sql = "SELECT l.*, u.username as user_name
                    FROM loan l
                    JOIN user u ON l.issued_by = u.user_id
                    ORDER BY l.created_at DESC";
            $stmt = $this->conn->prepare($sql);
        } 
        else {
            $sql = "SELECT l.*, u.username as user_name
                    FROM loan l
                    JOIN user u ON l.issued_by = u.user_id
                    WHERE l.issued_by = :user_id
                    ORDER BY l.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllandUserName()
    {
        $query = "
            SELECT loan.*, user.username AS user_name
            FROM {$this->table_name} AS loan
            JOIN user ON loan.issued_by = user.user_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT 
                    l.loan_id,
                    l.issued_by,
                    l.returned_to,
                    l.issued_date,
                    l.due_date,
                    l.returned_date,
                    l.status,
                    l.notes,
                    l.created_at,
                    l.updated_at,
                    u.username AS borrower_name
                FROM loan l
                LEFT JOIN user u ON l.issued_by = u.user_id
                WHERE l.loan_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBooksByLoanId($loanId)
    {
        $query = "
        SELECT ld.book_id, b.title AS book_title, b.available_quantity as book_quantity, ld.status, ld.quantity as loan_detail_quantity, ld.notes
        FROM loan_detail ld
        JOIN book b ON ld.book_id = b.book_id
        WHERE ld.loan_id = :loan_id
    ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkBookAvailability($bookId, $requestedQuantity) {
        $query = "SELECT quantity FROM book WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return [
                'available' => $result['quantity'] >= $requestedQuantity,
                'remaining' => $result['quantity']
            ];
        }
        return false;
    }

    public function updateStatus($loanId, $status, $returnDate = null)
    {
        $validStatuses = ['issued', 'returned', 'overdue'];
        if (!in_array($status, $validStatuses)) {
            return false; 
        }

        $query = "UPDATE {$this->table_name} 
              SET status = :status, 
                  updated_at = NOW()";

        if ($status === 'returned' && $returnDate) {
            $query .= ", returned_date = :returned_date";
        }

        $query .= " WHERE loan_id = :loan_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);

        if ($status === 'returned' && $returnDate) {
            $stmt->bindParam(':returned_date', $returnDate, PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    public function updateBookStatusInLoanDetail($loanId, $bookId, $status)
    {
        $sql = "UPDATE loan_detail SET status = :status WHERE loan_id = :loanId AND book_id = :bookId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':loanId', $loanId, PDO::PARAM_INT);
        $stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function reserveBook( $bookId)
    {
    
        $user_id = $_SESSION['user_id'];

        $query = "INSERT INTO reservation (
            book_id, 
            user_id, 
            reservation_date, 
            status
        ) VALUES (
            :book_id, 
            :user_id, 
            NOW(), 
            'pending'
        )";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteBookFromLoan($loanId, $bookId)
    {
        $query = "DELETE FROM loan_detail WHERE loan_id = :loan_id AND book_id = :book_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateBookAvailability($bookId, $status, $quantity)
    {
        $query = "";
        switch ($status) {
            case 'returned':
                $query = "UPDATE book SET status = 'returned', available_quantity = available_quantity + $quantity WHERE book_id = :book_id";
                break;
            case 'lost':
                $query = "UPDATE book SET status = 'lost' WHERE book_id = :book_id";
                break;
            case 'damaged':
                $query = "UPDATE book SET status = 'damaged', available_quantity = available_quantity + $quantity WHERE book_id = :book_id";
                break;
        }

        if (!empty($query)) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
            return $stmt->execute();
        }

    return false;
}

public function createLoan($books)
    {
        try {
            $this->conn->beginTransaction();

            $firstBook = $books[0];
            $query = "INSERT INTO loan (book_id, issued_by, issued_date, due_date, status, notes) 
                     VALUES (:book_id, :issued_by, :issued_date, :due_date, :status, :notes)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':book_id', $firstBook['book_id']);
            $stmt->bindParam(':issued_by', $this->issued_by);
            $stmt->bindParam(':issued_date', $this->issued_date);
            $stmt->bindParam(':due_date', $this->due_date);
            $stmt->bindValue(':status', NULL);
            $stmt->bindParam(':notes', $this->notes);
            
            $stmt->execute();
            $loan_id = $this->conn->lastInsertId();
            
            // Thêm chi tiết cho tất cả các cuốn sách
            foreach ($books as $book) {
                $query = "INSERT INTO loan_detail (loan_id, book_id, quantity, status, notes) 
                         VALUES (:loan_id, :book_id, :quantity, :status, :notes)";
                         
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':loan_id', $loan_id);
                $stmt->bindParam(':book_id', $book['book_id']);
                $stmt->bindParam(':quantity', $book['quantity']);
                $stmt->bindValue(':status', NULL);
                $stmt->bindValue(':notes', '');
                
                $stmt->execute();
                
                // Cập nhật số lượng sách có sẵn
                $this->updateBookQuantityBorrow($book['book_id'], $book['quantity']);
            }
            
            $this->conn->commit();
            return $loan_id;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
    

public function getAllandUserNameByUser($userId)
{
    $query = "
        SELECT loan.*, user.username AS user_name
        FROM {$this->table_name} AS loan
        JOIN user ON loan.issued_by = user.user_id
        WHERE loan.issued_by = :user_id
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getAvailableBooks() {
    $sql = "SELECT book_id, title, quantity 
            FROM book
            WHERE quantity > 0";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getActiveLoanCount()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table_name} WHERE status = 'issued'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getOverdueCount()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table_name} WHERE status = 'overdue'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getMonthlyStats()
    {
        $query = "SELECT DATE_FORMAT(issued_date, '%Y-%m') as month,
                    COUNT(*) as loan_count
            FROM {$this->table_name}
            GROUP BY DATE_FORMAT(issued_date, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBookQuantity($bookId, $quantityChange)
{
    try {
        $query = "UPDATE book SET available_quantity = available_quantity + :quantity_change WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity_change', $quantityChange, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (Exception $e) {
        // Handle exception (log error, rethrow, etc.)
        return false;
    }
}
public function updateBookQuantityBorrow($bookId, $quantityChange)
{
    try {
        $query = "UPDATE book SET available_quantity = available_quantity - :quantity_change WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity_change', $quantityChange, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (Exception $e) {
        // Handle exception (log error, rethrow, etc.)
        return false;
    }
}

public function getCustomer_Borrow($user_id)
{
    $query = "
        SELECT b.title, GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors, loan.created_at, loan_detail.quantity, loan_detail.status
            FROM book b
            JOIN 
                book_author ba ON b.book_id = ba.book_id
            JOIN 
                author a ON ba.author_id = a.author_id
            JOIN loan_detail ON b.book_id = loan_detail.book_id
            JOIN loan ON loan.loan_id = loan_detail.loan_id
            WHERE loan.issued_by = :issued_by
            GROUP BY b.book_id, b.title
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':issued_by', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function read_to_export()
{
    $query = "
    SELECT 
        l.loan_id AS MaPhieu,
        b.title AS TenSach,
        a.name AS TacGia,
        ld.quantity AS SoLuongMuon,
        u.full_name AS TenNguoiMuon
    FROM 
        loan l
    JOIN 
        loan_detail ld ON l.loan_id = ld.loan_id
    JOIN 
        book b ON ld.book_id = b.book_id
    JOIN 
        book_author ba ON b.book_id = ba.book_id
    JOIN 
        author a ON ba.author_id = a.author_id
    JOIN 
        user u ON l.issued_by = u.user_id;
";
$stmt = $this->conn->prepare($query);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}