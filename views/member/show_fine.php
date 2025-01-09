<style>
    .container-body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
            width: 1690px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
        }
        .user-info, .book-list, .appointment-card {
            margin-bottom: 20px;
        }
        .border {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
            color: #333;
        }
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .custom-alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .custom-alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .close-btn {
        background: none;
        border: none;
        color: inherit;
        font-size: 20px;
        cursor: pointer;
        position: absolute;
        top: 5px;
        right: 10px;
    }
</style>
<div class="container-body">
    <div class="row justify-content-center mt-4" style="margin: 0 15px 15px 15px;">
     <?php if (isset($_SESSION['message']) || isset($_SESSION['alert'])): ?>
        <div id="alert-message" 
            class="custom-alert custom-alert-<?= isset($_SESSION['alert']) ? 'danger' : htmlspecialchars($_SESSION['message_type']); ?>">
            <?= htmlspecialchars($_SESSION['message'] ?? $_SESSION['alert']); ?>
            <button type="button" class="close-btn" onclick="closeAlert()">&times;</button>
        </div>
        <?php
        // Xóa thông báo sau khi hiển thị
        unset($_SESSION['message'], $_SESSION['message_type'], $_SESSION['alert']);
        ?>
        <script>
            // Tự động ẩn thông báo sau 2 giây
            setTimeout(function() {
                var alert = document.getElementById('alert-message');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 300);
                }
            }, 2000);

            // Đóng thông báo khi người dùng nhấn vào nút đóng
            function closeAlert() {
                var alert = document.getElementById('alert-message');
                alert.style.display = 'none';
            }
        </script>
    <?php endif; ?>
     <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Chi tiết phiếu phạt</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['message']; ?>
                        </div>
                        <?php   
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>
                    <form action="index.php?model=fine&action=pay" method="POST">
                        <input type="hidden" name="fine_id" value="<?= htmlspecialchars($fine['fine_id']); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="loan_id" class="form-label">Phiếu mượn:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($fine['loan_id']); ?>" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tên người dùng:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($fine['user_name']); ?>" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <?php if ($fine['returned_date'] != null): ?>
                                <div class="col-md-4">
                                    <label class="form-label">Ngày tạo phiếu:</label>
                                    <input class="form-control" type="date" value="<?= htmlspecialchars($fine['issued_date']); ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Hạn trả:</label>
                                    <input class="form-control" type="date" value="<?= htmlspecialchars($fine['due_date']); ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ngày thanh toán:</label>
                                    <input class="form-control" type="date" value="<?= htmlspecialchars($fine['returned_date']); ?>" readonly>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày tạo phiếu:</label>
                                    <input class="form-control" type="date" value="<?= htmlspecialchars($fine['issued_date']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hạn trả:</label>
                                    <input class="form-control" type="date" value="<?= htmlspecialchars($fine['due_date']); ?>" readonly>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div  class=" mb-3" style="margin-top: 15px;">
                            <label class="form-label">Ghi chú:</label>
                            <textarea class="form-control" rows="3" readonly><?= htmlspecialchars($fine['notes']); ?></textarea>
                        </div>

                        <div class="mb-3" style="margin-top: 15px;">
                            <label class="form-label">Trạng thái:</label>
                            <?php 
                                if ($fine['status'] == 'paid') {
                                    $statusText = 'Đã thanh toán';
                                    $statusClass = 'text-success';
                                } elseif ($fine['status'] == 'pending') {
                                    $statusText = 'Đang chờ xử lý';
                                    $statusClass = 'text-warning';
                                } else {
                                    $statusText = 'Chưa thanh toán';
                                    $statusClass = 'text-danger';
                                }
                            ?>
                            <input type="text" class="form-control <?= $statusClass ?>" value="<?= $statusText ?>" readonly>
                        </div>

                        <div class="card-footer" style="margin-top: 15px; width: 100%; ">
                            <a href="index.php?model=fine&action=fines&id=<?php echo $_SESSION['user_id'] ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại
                            </a>
                            <?php if($fine['status'] == 'unpaid'): ?>
                                
                                <button type="submit" class="btn-primary" style="margin-left: 1335px; border-radius: 5px; width: 150px; height: 40px;">
                                    <i class="fas fa-money-bill"></i> Thanh toán phiếu
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>