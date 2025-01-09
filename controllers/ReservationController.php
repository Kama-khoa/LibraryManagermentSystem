<?php

use App\Services\ExcelExportService;

include_once 'models/Reservation.php';
include_once 'models/Reservation_Detail.php';
include_once 'models/Book.php';

class ReservationController extends Controller
{
    private $reservation;
    private $reservationDetail;
    private $book;
    private $user;

    public function __construct()
    {
        $this->reservation = new Reservation();
        $this->reservationDetail = new Reservation_Detail();
        $this->book = new Book();
        $this->user = new User();
    }

    public function index()
    {
        $reservations = $this->reservation->read();
        $content = 'views/reservations/index.php';
        include('views/layouts/base.php');
    }

    public function create() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lưu dữ liệu form vào session trước khi xử lý
                $_SESSION['form_data'] = $_POST;

                $bookIds = $_POST['book_id'];
                $expiryDate = $_POST['expiry_date'];
                $userId = $_POST['user_id'];
                $reservationDate = $_POST['reservation_date'];
                $notes = $_POST['notes'];
                $status = 'pending';

                // Kiểm tra dữ liệu đầu vào
                if (empty($bookIds)) {
                    throw new Exception('Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin.');
                }

                // Tạo một reservation chính
                $this->reservation->user_id = strip_tags(trim($userId));
                $this->reservation->reservation_date = strip_tags(trim($reservationDate));
                $this->reservation->notes = strip_tags(trim($notes));
                $this->reservation->status = $status;
                $this->reservation->expiry_date = strip_tags(trim($expiryDate));
                // Lưu reservation chính
                if (!$this->reservation->create()) {
                    throw new Exception("Không thể tạo phiếu đặt sách");
                }
                
                // Lấy ID của reservation vừa tạo
                $reservationId = $this->reservation->getLastInsertId();
                
                // Lặp qua từng sách để tạo reservation_detail
                foreach ($bookIds as $index => $bookId) {
                    $this->reservationDetail->reservation_id = $reservationId;
                    $this->reservationDetail->book_id = strip_tags(trim($bookId));
    
                    // Lưu từng reservation_detail
                    if (!$this->reservationDetail->create()) {
                        throw new Exception("Không thể tạo chi tiết phiếu đặt cho sách ID: $bookId");
                    }
                }

                $_SESSION['message'] = 'Tạo phiếu đặt sách thành công!';
                $_SESSION['message_type'] = 'success';
                unset($_SESSION['form_data']); // Xóa dữ liệu form sau khi thành công
                header("Location: index.php?model=reservation&action=index");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        // Lấy danh sách người dùng và sách
        $users = $this->user->read();
        $books = $this->book->readWithExpectedDate();
        $content = 'views/reservations/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id) {
        // Đọc thông tin phiếu đặt sách và chi tiết
        $reservation = $this->reservation->readById($id);
        $reservationDetails = $this->reservationDetail->readById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $status = $_POST['status'];
    
            // Danh sách trạng thái hợp lệ
            $validStatuses = ['confirmed', 'fulfilled', 'canceled'];
    
            // Kiểm tra tính hợp lệ của trạng thái
            if (!in_array($status, $validStatuses)) {
                $_SESSION['message'] = 'Trạng thái không hợp lệ.';
                $_SESSION['message_type'] = 'danger';
                header('Location: index.php?model=reservation&action=edit&id=' . $id);
                exit;
            }
    
            try {
                if (!$this->reservation->updateStatus( $id,$status)) {
                    throw new Exception('Không thể cập nhật trạng thái phiếu đặt.');
                }
    
                // Đặt thông báo thành công
                $_SESSION['message'] = 'Cập nhật trạng thái thành công.';
                $_SESSION['message_type'] = 'success';
            } catch (Exception $e) {
                // Đặt thông báo lỗi
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
    
            // Chuyển hướng sau khi xử lý
            header('Location: index.php?model=reservation&action=index');
            exit;
        }
    
        // Truyền dữ liệu cho view
        $data = [
            'reservation' => $reservation,
            'reservationDetails' => $reservationDetails
        ];
    
        $content = 'views/reservations/edit.php';
        include('views/layouts/base.php');
    }    

    public function delete($id)
{
    try {
        // Xóa các chi tiết phiếu đặt trong bảng reservation_detail
        if (!$this->reservationDetail->delete($id)) {
            throw new Exception('Không thể xóa chi tiết phiếu đặt sách.');
        }

        // Xóa phiếu đặt trong bảng reservation
        if (!$this->reservation->delete($id)) {
            throw new Exception('Không thể xóa phiếu đặt sách.');
        }

        // Commit giao dịch nếu tất cả đều thành công
        $_SESSION['message'] = 'Xóa phiếu đặt sách và chi tiết thành công!';
        $_SESSION['message_type'] = 'success';
    } catch (Exception $e) {
        // Rollback giao dịch nếu có lỗi xảy ra
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }

    // Chuyển hướng về trang danh sách
    header("Location: index.php?model=reservation&action=index");
    exit();
}


    public function statistics()
    {
        // Ví dụ logic thống kê: Lấy tổng số đặt chỗ
        try {
            $reservations = $this->reservation->read();
            $totalReservations = count($reservations);
            $content = 'views/reservations/statistics.php';
            include('views/layouts/base.php');
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=reservation&action=index");
        }
    }
    public function member_create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Save form data to session before processing
                $_SESSION['form_data'] = $_POST;

                if(isset($_POST['notes'])){
                    $bookIds = $_POST['book_id'];
                    $userId = $_SESSION['user_id'];
                    $notes = $_POST['notes'];
                    $status = 'pending';
        
                    // Validate input data
                    if (empty($bookIds)) {
                        throw new Exception('Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin.');
                    }
        
                    // Lấy expected_date lớn nhất từ bảng books
                    $maxExpectedDate = null;
                    foreach ($bookIds as $bookId) {
                        $book = $this->book->readById($bookId);
                        if ($book && (!$maxExpectedDate || $book['expected_date'] > $maxExpectedDate)) {
                            $maxExpectedDate = $book['expected_date'];
                        }
                    }
        
                    if (!$maxExpectedDate) {
                        throw new Exception('Không thể xác định ngày dự kiến.');
                    }
        
                    $expiryDate = date('Y-m-d', strtotime($maxExpectedDate . ' +3 days'));
        
                    // Create main reservation
                    $this->reservation->user_id = strip_tags(trim($userId));
                    $this->reservation->reservation_date = date('Y-m-d'); // Current date
                    $this->reservation->notes = strip_tags(trim($notes));
                    $this->reservation->status = $status;
                    $this->reservation->expiry_date = $expiryDate;
        
                    // Save main reservation
                    if (!$this->reservation->create()) {
                        throw new Exception("Không thể tạo phiếu đặt sách");
                    }
        
                    // Get ID of the newly created reservation
                    $reservationId = $this->reservation->getLastInsertId();
        
                    // Loop through each book to create reservation_detail
                    foreach ($bookIds as $bookId) {
                        $this->reservationDetail->reservation_id = $reservationId;
                        $this->reservationDetail->book_id = strip_tags(trim($bookId));
        
                        // Save each reservation_detail
                        if (!$this->reservationDetail->create()) {
                            throw new Exception("Không thể tạo chi tiết phiếu đặt cho sách ID: $bookId");
                        }
                    }
        
                    $_SESSION['message'] = 'Tạo phiếu đặt sách thành công!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']); // Clear form data after success
                    header("Location: index.php?model=default&action=index");
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
    
        $user = $this->user->readById($_SESSION['user_id']);
        $booksWithExpectedDate = $this->book->readWithExpectedDate();
    
        $content = 'views/reservations/member_create.php';
        include('views/layouts/application.php');
    }
    public function export()
    {
        // Khởi tạo service
        $excelService = new ExcelExportService();

        // Lấy danh sách reservations
        $reservations = $this->reservation->read();

        // Định nghĩa headers và key mapping
        $headers = [
            'reservation_id' => 'Mã phiếu',
            'full_name' => 'Họ và tên người mượn',
            'reservation_date' => 'Ngày đặt hẹn',
            'expiry_date' => 'Ngày hết hạn',
            'fulfilled_date' => 'Ngày hoàn thành',
            'status' => 'Trạng thái',
            'notes' => 'Ghi chú',
            'titles' => 'Tựa sách',
        ];

        // Dịch trạng thái
        $translations = [
            'fulfilled' => 'Hoàn thành',
            'pending' => 'Đang chờ',
            'cancelled' => 'Đã hủy',
            'expired' => 'Hết hạn',
        ];

        // Xử lý và sắp xếp lại data theo thứ tự của headers
        $processedData = [];
        foreach ($reservations as $reservation) {
            $row = [];
            foreach (array_keys($headers) as $key) {
                if ($key == 'status') {
                    $row[$key] = $translations[$reservation[$key]] ?? $reservation[$key];
                } else {
                    $row[$key] = $reservation[$key] ?? '';
                }
            }
            $processedData[] = $row;
        }

        // Lấy ngày hiện tại
        $currentDate = date('d-m-Y');
        $filename = "danh_sach_phieu_dat_hen_ngay_{$currentDate}.xlsx";

        // Tiêu đề lớn
        $title = "Danh sách phiếu đặt hẹn ngày {$currentDate}";
        // Cấu hình cho việc export
        $config = [
            'title' => $title,
            'headers' => $headers,
            'data' => $processedData,  // Sử dụng processed data đã sắp xếp
            'filename' => $filename,
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
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ],
            'titleStyle' => [  // Cấu hình style cho tiêu đề lớn
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];

        // Thực hiện export
        $excelService->exportWithConfig($config);
    }
}
?>
