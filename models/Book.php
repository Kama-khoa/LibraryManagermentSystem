<?php
include_once 'Model.php';
class Book extends Model
{
    protected $table_name = 'book';

    public $book_id;
    public $publisher_id;
    public $title;
    public $publication_year;
    public $edition;
    public $pages;
    public $language;
    public $description;
    public $quantity;
    public $available_quantity;
    public $status;
    public $cover_image;
    public $created_at;
    public $updated_at;

    public function __construct() {
        parent::__construct();
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name} 
        (publisher_id, title, publication_year, edition, pages, language, description, quantity, available_quantity, status, cover_image) 
        VALUES (:publisher_id, :title, :publication_year, :edition, :pages, :language, :description, :quantity, :available_quantity, :status, :cover_image)";

        $stmt = $this->conn->prepare($query);

        if (empty($this->status)) {
            $this->status = 'available';
        }

        // Binding các tham số
        $stmt->bindParam(':publisher_id', $this->publisher_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':publication_year', $this->publication_year);
        $stmt->bindParam(':edition', $this->edition);
        $stmt->bindParam(':pages', $this->pages);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':available_quantity', $this->available_quantity);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':cover_image', $this->cover_image);

        return $stmt->execute();
    }

    public function read() {
        $query = "
            SELECT 
                b.*, 
                p.name AS publisher_name, 
                GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
                GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories
            FROM 
                {$this->table_name} b
            LEFT JOIN 
                publisher p ON b.publisher_id = p.publisher_id
            LEFT JOIN 
                book_author ba ON b.book_id = ba.book_id
            LEFT JOIN 
                author a ON ba.author_id = a.author_id
            LEFT JOIN 
                book_category bc ON b.book_id = bc.book_id
            LEFT JOIN 
                category c ON bc.category_id = c.category_id
            GROUP BY 
                b.book_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "
        SELECT 
            b.*, 
            MIN(loan.due_date) + INTERVAL 1 DAY AS expected_date,
            p.name AS publisher_name, 
            GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
            GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories
        FROM 
            {$this->table_name} b
        LEFT JOIN 
            publisher p ON b.publisher_id = p.publisher_id
        LEFT JOIN 
            book_author ba ON b.book_id = ba.book_id
        LEFT JOIN 
            author a ON ba.author_id = a.author_id
        LEFT JOIN 
            book_category bc ON b.book_id = bc.book_id
        LEFT JOIN 
            category c ON bc.category_id = c.category_id
        LEFT JOIN 
            loan_detail ON b.book_id = loan_detail.book_id
        LEFT JOIN 
            loan ON loan.loan_id = loan_detail.loan_id
        WHERE b.book_id = :id
        GROUP BY 
            b.book_id
    ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readWithExpectedDate()
    {
        $query = "
            SELECT b.*, 
                MIN(loan.due_date) + INTERVAL 1 DAY AS expected_date,
                GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors
            FROM book b
            LEFT JOIN 
                book_author ba ON b.book_id = ba.book_id
            LEFT JOIN 
                author a ON ba.author_id = a.author_id
            LEFT JOIN loan_detail ON b.book_id = loan_detail.book_id
            LEFT JOIN loan ON loan.loan_id = loan_detail.loan_id
            GROUP BY b.book_id, b.title
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id) {
        return parent::update($id);
    }

    public function delete($id) {
        return parent::delete($id);
    }

    public function getPublishers() {
        $query = "SELECT publisher_id, name FROM publisher ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAuthors() {
        $query = "SELECT author_id, name FROM author"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAuthorsByBookId($id) {
        $query = "
            SELECT a.author_id, a.name 
            FROM book_author ba
            JOIN author a ON ba.author_id = a.author_id
            WHERE ba.book_id = :book_id
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':book_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function getCategories() {
        $query = "SELECT category_id, name FROM category";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLastInsertedId() {
        return $this->conn->lastInsertId(); // PDO method để lấy ID của bản ghi vừa được thêm
    }

    public function getCategoriesByBookId($id) {
        $query = "
            SELECT c.category_id, c.name
            FROM book_category bc
            JOIN category c ON bc.category_id = c.category_id
            WHERE bc.book_id = :book_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':book_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBookAuthor($id, $author_ids) {
        // Xóa tất cả các tác giả hiện tại chỉ một lần
        $sqlDelete = "DELETE FROM book_author WHERE book_id = :book_id";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':book_id', $id, PDO::PARAM_INT);
        $stmtDelete->execute();
    
        // Thêm mới các tác giả
        $sqlInsert = "INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':book_id', $id, PDO::PARAM_INT);
        
        foreach ($author_ids as $author_id) {
            $stmtInsert->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmtInsert->execute();
        }
    }

    public function updateBookCategory($id, $category_ids) {
        // Xóa tất cả các danh mục hiện tại chỉ một lần
        $sqlDelete = "DELETE FROM book_category WHERE book_id = :book_id";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':book_id', $id, PDO::PARAM_INT);
        $stmtDelete->execute();
    
        // Thêm mới các thể loại
        $sqlInsert = "INSERT INTO book_category (book_id, category_id) VALUES (:book_id, :category_id)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':book_id', $id, PDO::PARAM_INT);
    
        foreach ($category_ids as $category_id) {
            $stmtInsert->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmtInsert->execute();
        }
    }

    public function getUnassessedBooks() {
        $query = "
            SELECT 
                b.book_id, 
                b.title AS book_title
            FROM 
                loan l
            INNER JOIN 
                book b ON l.book_id = b.book_id
            LEFT JOIN 
                book_condition bc ON l.loan_id = bc.loan_id
            WHERE 
                bc.loan_id IS NULL
            AND 
                l.status = 'returned'
            GROUP BY 
                b.book_id, b.title
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalCount() 
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getCategoryStats() 
    {
        $query = "SELECT c.name, COUNT(bc.book_id) as book_count
                FROM category c
                LEFT JOIN book_category bc ON c.category_id = bc.category_id
                GROUP BY c.category_id, c.name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMostPopularBooks() 
    {
        $query = "SELECT b.title, 
                        GROUP_CONCAT(a.name) as authors,
                        COUNT(l.loan_id) as borrow_count
                FROM book b
                LEFT JOIN book_author ba ON b.book_id = ba.book_id
                LEFT JOIN author a ON ba.author_id = a.author_id
                LEFT JOIN loan_detail ld ON b.book_id = ld.book_id
                LEFT JOIN loan l ON ld.loan_id = l.loan_id
                GROUP BY b.book_id, b.title
                ORDER BY borrow_count DESC
                LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getBooksByCategoryId($id) {
        $query = 
            "SELECT b.*
            FROM book_category bc
            JOIN ".$this->table_name." b 
            ON bc.book_id = b.book_id
            WHERE bc.category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':category_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getBook($orderBy, $start, $last, $where = null){
		if($where == null){
			$sql = "SELECT DISTINCT b.*,
                    p.name AS publisher_name, 
                    GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
                    GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories
                    FROM book_category bc
                    JOIN ".$this->table_name." b 
                    LEFT JOIN 
                        publisher p ON b.publisher_id = p.publisher_id
                    LEFT JOIN 
                        book_author ba ON b.book_id = ba.book_id
                    LEFT JOIN 
                        author a ON ba.author_id = a.author_id
                    LEFT JOIN 
                        category c ON bc.category_id = c.category_id  
                    GROUP BY 
                        b.book_id
                    ORDER BY ".$orderBy." desc LIMIT ".$start.",".$last;
		} else {
			$sql = "SELECT DISTINCT b.*,
                    a.name AS authors
                    FROM book_category bc
                    JOIN ".$this->table_name." b 
                    ON bc.book_id = b.book_id
                    LEFT JOIN 
                        book_author ba ON b.book_id = ba.book_id
                    LEFT JOIN 
                        author a ON ba.author_id = a.author_id
                    WHERE ".$where." 
                    ORDER BY ".$orderBy." asc LIMIT ".$start.",".$last;
		}
		$stmt = $this->conn->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    function getTopBook($start, $last){
		$sql = "SELECT b.*, ld.created_at, COUNT(ld.book_id) AS borrow_count,
                p.name AS publisher_name, 
                GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
                GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories
                FROM book_category bc
                JOIN ".$this->table_name." b 
                ON bc.book_id = b.book_id
                LEFT JOIN 
                    publisher p ON b.publisher_id = p.publisher_id
                LEFT JOIN 
                    book_author ba ON b.book_id = ba.book_id
                LEFT JOIN 
                    author a ON ba.author_id = a.author_id
                LEFT JOIN 
                    category c ON bc.category_id = c.category_id
                LEFT JOIN loan_detail ld ON b.book_id = ld.book_id  
                GROUP BY 
                    b.book_id
                ORDER BY borrow_count DESC
                LIMIT :start, :last";
	    $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':last', $last, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
