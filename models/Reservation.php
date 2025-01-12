<?php
include_once 'Model.php';
class Reservation extends Model
{
    protected $table_name = 'reservation';

    public $reservation_id;
    public $user_id;
    public $reservation_date;
    public $expiry_date;
    public $fulfilled_date;
    public $status;
    public $notes;
    public $created_at;
    public $updated_at;

    public function __construct(){
        parent::__construct();
    }

    public function create()
    {
        return parent::create();
    }

    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function read() {
        $query = "SELECT r.*, b.book_id, GROUP_CONCAT(DISTINCT b.title SEPARATOR ', ') AS titles, u.user_id, u.username, u.full_name 
                  FROM {$this->table_name} r 
                  JOIN user u ON u.user_id = r.user_id
                  JOIN reservation_detail rd ON rd.reservation_id = r.reservation_id
                  JOIN book b ON b.book_id = rd.book_id
                  GROUP BY r.reservation_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT r.*, u.user_id, u.username, u.full_name, u.email, u.phone, u.address, u.date_of_birth, u.gender
                  FROM {$this->table_name} r 
                  JOIN user u ON u.user_id = r.user_id
                  HAVING r.reservation_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        return parent::update($id);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE `" . $this->table_name . "` SET status = ? WHERE reservation_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $status);
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }

    public function readByUserId($user_id) {
        $query = "SELECT * FROM {$this->table_name} r 
                  JOIN user u ON u.user_id = r.user_id
                  JOIN book b ON b.book_id = rd.book_id 
                  JOIN reservation_detail rd ON rd.reservation_id = r.reservation_id
                  WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkBookReserved($book_id) {
        $query = "SELECT COUNT(*) FROM {$this->table_name}
                  JOIN reservation_detail rd ON rd.reservation_id = r.reservation_id
                  WHERE rd.book_id = ? AND r.status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $book_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
?>