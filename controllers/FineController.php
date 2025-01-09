<?php
include_once 'models/Fine.php';
include_once 'models/Loan.php';
include_once 'models/User.php';

class FineController extends Controller
{
    private $fine;
    private $loan;
    private $user;

    public function __construct()
    {
        $this->fine = new Fine();
        $this->loan = new Loan();
        $this->user = new User();
    }

    public function index()
    {
        $fines = $this->fine->read();
        $loans = $this->loan->read();
        $users = $this->user->read();
        $content = 'views/fines/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->loan, $key)) {
                        $this->loan->$key = strip_tags(trim($value));
                    }
                }

                if (empty($_POST['issued_date'])) {
                    throw new Exception('Ngày tạo phiếu không được để trống.');
                }

                $this->fine->issued_date = $_POST['issued_date'];
                $this->fine->due_date = $_POST['due_date'];
                $this->fine->notes = $_POST['notes'];
                $this->fine->status = $_POST['status'];
                $this->fine->assessed_by = $_POST['assessed_by'];
                $this->fine->loan_id = $_POST['loan_id'];
                $this->fine->user_id = $_POST['user_id'];   
                $this->fine->returned_to = $_POST['returned_to'] ?? NULL;

                if ($this->fine->create()) {
                    $_SESSION['message'] = 'Thêm phiếu phạt thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=fine&action=index");
                    exit();
                } else {
                    throw new Exception('Thêm phiếu phạt không thành công.');
                }

            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $loans = $this->fine->getLoaned();
        $content = 'views/fines/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
{
    // Lấy dữ liệu phiếu phạt theo ID
    $fineData = $this->fine->readById($id);
    
    // Kiểm tra nếu form được gửi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Duyệt qua tất cả các trường dữ liệu trong form
            foreach ($_POST as $key => $value) {
                if (property_exists($this->fine, $key)) {
                    // Loại bỏ các thẻ HTML và trim dữ liệu
                    $this->fine->$key = strip_tags(trim($value));
                }
            }
            
            // Kiểm tra xem có thay đổi trạng thái hay không
            if (isset($_POST['status']) && $_POST['status'] !== $fineData['status']) {
                // Nếu có thay đổi trạng thái, gọi hàm editStatus
                $this->editStatus($id);
            } else {
                // Nếu không có thay đổi trạng thái, gọi hàm update thông thường
                if ($this->fine->update($id)) {
                    $_SESSION['message'] = 'Cập nhật phiếu thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=fine&action=index");
                    exit();
                } else {
                    throw new Exception('Cập nhật phiếu không thành công.');
                }
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    // Lấy dữ liệu các khoản vay (loans)
    $loans = $this->fine->getLoaned();
    
    // Gán dữ liệu phiếu phạt vào biến để truyền ra view
    $fine = $fineData;
    $content = 'views/fines/edit.php';
    include('views/layouts/base.php');
}

    public function delete($id)
    {
        try {
            if ($this->fine->delete($id) ) {
                $_SESSION['message'] = 'Xóa phiếu phạt thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Xóa phiếu phạt không thành công.');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
        header("Location: index.php?model=fine&action=index");
        exit();
    }

    public function editStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'status' => 'paid',
                    'returned_date' => date('Y-m-d')
                ];

                $this->fine->updateFineStatus($id, $data);

                $_SESSION['message'] = 'Phiếu phạt đã được xét duyệt!';
                $_SESSION['message_type'] = 'success';
                header("Location: index.php?model=fine&action=index");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = 'Lỗi xét duyệt: ' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                header("Location: index.php?model=fine&action=index");
                exit();
            }
        }
    }

    public function fines($id) {
        $fines = $this->fine->getFinesByMemberId($id);

        $content = 'views/member/fines.php';
        include('views/layouts/application.php');
    }
    public function show($id) {
        $fine = $this->fine->readById($id);

        $content = 'views/member/show_fine.php';
        include('views/layouts/application.php');
    }

    public function pay() {
        $fineId = isset($_POST['fine_id']) ? htmlspecialchars($_POST['fine_id']) : null;
        // Kiểm tra xem người dùng đã gửi form thanh toán chưa
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_fines'])) {
            $selectedFines = $_POST['selected_fines']; // Các ID của hóa đơn được chọn
            
            
            if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['payment_image'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileType = $file['type'];
    
                // Kiểm tra định dạng và kích thước file
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB
    
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['alert'] = 'Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG!';
                    header('Location: index.php?model=fine&action=pay');
                    exit;
                }
    
                if ($fileSize > $maxSize) {
                    $_SESSION['alert'] = 'Kích thước file không được vượt quá 2MB!';
                    header('Location: index.php?model=fine&action=pay');
                    exit;
                }
    
                // Tạo tên file mới và đường dẫn upload
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadDir = 'uploads/payments/';
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadFilePath = $uploadDir . $newFileName;
    
                if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                    // Lặp qua từng hóa đơn được chọn và cập nhật trạng thái
                    foreach ($selectedFines as $fineId) {
                        $this->fine->updateFineStatus($fineId, [
                            'status' => 'pending', // Cập nhật trạng thái thành 'chờ thanh toán'
                            'payment_method' => 'Chuyển khoản', // Cập nhật phương thức thành 'chuyển khoản'
                            'notes' => $newFileName, // Lưu đường dẫn ảnh vào notes
                            'returned_date' => date('Y-m-d') // Lưu ngày hiện tại vào returned_date
                        ]);
                    }
    
                    // Chuyển hướng người dùng sau khi thanh toán thành công
                    $_SESSION['message'] = 'Thanh toán đang chờ xử lý!';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?model=fine&action=fines&id=' . $_SESSION['user_id']);
                    exit;
                } else {
                    $_SESSION['alert'] = 'Không thể tải ảnh lên. Vui lòng thử lại!';
                    header('Location: index.php?model=fine&action=pay');
                    exit;
                }
            } else {
                $_SESSION['alert'] = 'Vui lòng tải ảnh xác nhận thanh toán!';
                header('Location: index.php?model=fine&action=pay');
                exit;
            }
        }
    
        // Lấy danh sách phiếu phạt và thông tin người dùng để hiển thị
        $fines = $this->fine->getFinesByMemberId($_SESSION['user_id']);
        $user = $this->user->readById($_SESSION['user_id']);
        
        $content = 'views/member/pay.php';
        include('views/layouts/application.php');
    }
}
