<?php
include_once 'models/Loan.php';
include_once 'services/ExcelExportService.php';
use App\Services\ExcelExportService;

class LoanController extends Controller
{
    private $loan;
    private $authModel;

    public function __construct()
    {
        $this->loan = new Loan();
        $this->authModel = new User();
    }

    // Hiển thị danh sách phiếu mượn
    public function index()
    {
        // $loans = $this->loan->getAllLoans(
        //     $_SESSION['user']['user_id'], 
        //     $_SESSION['user']['role_id']
        // );
        $userData = $this->authModel->readById($_SESSION['user_id']);
        $loans = $this->loan->getAllandUserName();
        $content = 'views/loans/index.php';
        include('views/layouts/base.php');
    }

    // Hiển thị chi tiết phiếu mượn
    public function show($id)
    {
        if (!isset($_GET['id'])) {
            $_SESSION['message'] = 'Không tìm thấy mã phiếu!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=index');
            exit;
        }

        $loanId = $_GET['id'];
        $loan = $this->loan->getById($loanId);
        $books = $this->loan->getBooksByLoanId($loanId);

        if (!$loan) {
            $_SESSION['message'] = 'Phiếu mượn không tồn tại!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=index');
            exit;
        }


        $userData = $this->authModel->readById($_SESSION['user_id']);
        $content = 'views/loans/show.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Xử lý dữ liệu từ form
                $this->loan->issued_by = null;
                $this->loan->issued_date = $_POST['issued_date'];
                $this->loan->due_date = $_POST['due_date'];
                $this->loan->status = 'pending';
                $this->loan->notes = $_POST['notes'] ?? '';
                $this->loan->books = [];
    
                // Xử lý các sách được chọn
                if (isset($_POST['selected_books']) && is_array($_POST['selected_books'])) {
                    foreach ($_POST['selected_books'] as $bookId) {
                        $this->loan->books[] = [
                            'book_id' => $bookId,
                            'quantity' => $_POST['book_quantity'][$bookId],
                            'status' => 'pending',
                            'notes' => $_POST['book_notes'][$bookId] ?? ''
                        ];
                    }
                }
    
                // Gọi phương thức tạo phiếu mượn
                $loanId = $this->loan->createLoan($this->loan->books);
    
                if ($loanId) {
                    $_SESSION['message'] = 'Tạo phiếu mượn thành công!';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?model=loan&action=show&id=' . $loanId);
                } else {
                    throw new Exception('Tạo phiếu mượn thất bại');
                }
                exit;
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                header('Location: index.php?model=loan&action=create');
                exit;
            }
        }

        $availableBooks = $this->loan->getAvailableBooks();
        $userData = $this->authModel->readById($_SESSION['user_id']);
        $content = 'views/loans/create.php';
        include('views/layouts/base.php');
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function update_status($id)
{
    // var_dump($_POST);
    // exit();
    if (!isset($_POST)) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Không tìm thấy mã phiếu!'
        ]);
        return;
    }

    $loanId = $_POST['loan_id'];
    $status = $_POST['action_status'] ?? null;

    if ($status === null) {
        $this->jsonResponse([
            'success' => false,
            'message' => 'Trạng thái không hợp lệ!'
        ]);
        return;
    }

    if ($status == 'issued') {
        $stmt = $this->loan->getBooksByLoanId($loanId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($stmt as $book) {
                    $this->loan->updateBookStatusInLoanDetail($loanId, $book['book_id'], 'issued');
                }
                $this->loan->updateStatus($loanId, 'issued');
                $_SESSION['message'] = 'Phê duyệt phiếu mượn thành công!';
                $_SESSION['message_type'] = 'success';
            } catch (Exception $e) {
                $_SESSION['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
            header('Location: index.php?model=loan&action=show&id=' . $loanId);
            exit;
        }
    }

    else if ($status === 'overdue') {
        try {
            $result = $this->loan->updateStatus($loanId, $status);
            if ($result) {
                $_SESSION['message'] = 'Đã cập nhật trạng thái quá hạn!';
                $_SESSION['message_type'] = 'warning';
            }
            header('Location: index.php?model=loan&action=show&id=' . $loanId);
            exit;
        } catch (Exception $e) {
            $_SESSION['message'] = 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=show&id=' . $loanId);
            exit;
        }
    }

    else if($status === 'returned') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booksInLoan = $this->loan->getBooksByLoanId($loanId);
            $returnDate = date('Y-m-d H:i:s');
    
            try {
                foreach ($booksInLoan as $book) {
                    $bookId = $book['book_id'];
                    $loanDetailQuantity = $book['loan_detail_quantity'];
                    $bookStatus = $_POST['book_status'][$bookId] ?? null;
    
                    if ($bookStatus !== null) {
                        $this->loan->updateBookAvailability($bookId, $bookStatus, $loanDetailQuantity);
                        $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, $bookStatus);
                    }
                }
                $this->loan->updateStatus($loanId, $status, $returnDate);
    
                $_SESSION['message'] = 'Cập nhật trạng thái trả sách thành công!';
                $_SESSION['message_type'] = 'success';
            } catch (Exception $e) {
                $_SESSION['message'] = 'Lỗi khi trả sách: ' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
    
            header('Location: index.php?model=loan&action=show&id=' . $loanId);
            exit;
        }
    }
   header('Location: index.php?model=loan&action=index');
    exit;
}

    public function updateBookStatus($loanId, $bookId, $status)
    {
        $result = $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, $status);
        if ($result) {
            echo "Trạng thái sách đã được cập nhật thành công!";
        } else {
            echo "Cập nhật trạng thái sách thất bại!";
        }
    }

    public function export()
    {
        // Khởi tạo service
        $excelService = new ExcelExportService();

        // Lấy danh sách users
        $loans = $this->loan->read_to_export();

        // Định nghĩa headers và key mapping
        $headers = [
            'MaPhieu' => 'Mã Phiếu',
            'TenSach' => 'Tên Sách',
            'TacGia' => 'Tác Giả',
            'SoLuongMuon' => 'Số Lượng Mượn',
            'TenNguoiMuon' => 'Tên Người Mượn'
        ];
    
    

        // Xử lý và sắp xếp lại data theo thứ tự của headers
        $processedData = [];
        foreach ($loans as $user) {
            $row = [];
            foreach (array_keys($headers) as $key) {
                $row[$key] = $user[$key] ?? '';
            }
            $processedData[] = $row;
        }

        // Cấu hình cho việc export
        $config = [
            'headers' => $headers,
            'data' => $processedData,  // Sử dụng processed data đã sắp xếp
            'filename' => 'danh_sach_muon.xlsx',
            'headerStyle' => [
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E2E8F0'
                    ]
                ],
                'alignment' => [  // Căn giữa tiêu đề
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ],
            'dataStyle' => [ // Style cho nội dung bảng
                'alignment' => [  // Căn giữa nội dung
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [ // Đường viền xung quanh các ô
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]
        ];

        // Thực hiện export
        $excelService->exportWithConfig($config);
    }
    

}
?>