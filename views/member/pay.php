<style>
    p{
        font-size: 15px;
        padding-left: 15px;
    }
    h4{
        font-size: 20px;
    }
    .container-body {
            font-family: Arial, sans-serif;
            margin: 0 20px;
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
    .payment{
        margin: 10px;
    }
    /* Các kiểu cho thông báo */
    .custom-alert {
        padding: 10px 20px;
        margin: 10px 0;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: opacity 0.3s ease;
    }

    /* Đỏ cho thông báo lỗi */
    .custom-alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Nút đóng */
    .close-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: inherit;
        cursor: pointer;
    }
</style>
<div class="container-body">
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
    <div class="header" style="text-align: center;">
        <h2>Thanh toán phiếu phạt</h2>
    </div>
    <div class="row ">
        <div class="col-md-12">
            
            <div class="card-body" style="margin: 20px;">
                <div class="row">
                <!-- Thông tin người dùng -->
                    <div class="border" style="margin-bottom: 20px;">
                        <h4>Thông tin người dùng</h4>
                        <p><strong>Trường:</strong> Trường Đại học Công nghệ Giao thông vận tải</p>
                        <p><strong>Họ và tên:</strong> <?= htmlspecialchars($user['full_name']); ?></p>
                        <p><strong>Mã sinh viên:</strong> <?= htmlspecialchars($user['user_id']); ?></p>
                        <p><strong>Tổng tiền cần thanh toán:</strong> <span id="totalAmount">0</span> VNĐ</p>
                    </div>
                    
                    <!-- Danh sách phiếu phạt -->
                    <form action="index.php?model=fine&action=pay" method="POST" enctype="multipart/form-data">
                    <div class=" border">
                        <h4>Chi tiết phiếu phạt</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Chọn</th>
                                    <th class="text-center">Mã phiếu phạt</th>
                                    <th class="text-center">Số tiền (VNĐ)</th>
                                    <th class="text-center">Hạn đóng</th>
                                    <th class="text-center">Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fines as $index => $fine):
                                    if($fine['status'] == 'unpaid'): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="selected_fines[]" value="<?= $fine['fine_id']; ?>" 
                                            data-amount="<?= $fine['amount']; ?>" class="fine-checkbox" <?= ($fine['fine_id'] == $fineId) ? 'checked' : ''; ?>>
                                        </td>
                                        <td class="text-center"><?= htmlspecialchars($fine['fine_id']); ?></td>
                                        <td class="text-center"><?= number_format($fine['amount'], 0, ',', '.'); ?></td>
                                        <td class="text-center"><?= htmlspecialchars($fine['due_date']); ?></td>
                                        <td ><?= htmlspecialchars($fine['notes']); ?></td>
                                    </tr>
                                <?php endif ;
                                    endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                        <!-- Hướng dẫn thanh toán -->
                        <div class="row payment">
                            <div class="col-md-6">
                                <h4>Hình thức thanh toán</h4>
                                <h5><strong>Cách 1:</strong> Thanh toán chuyển khoản ngân hàng</h5>
                                <p><strong>Số tài khoản thanh toán (VA):</strong> <span class="text-danger">1903 5508 9090 11</span></p>
                                <p><strong>Chủ tài khoản:</strong> Trường Đại học Công nghệ Giao thông vận tải</p>
                                <p><strong>Ngân hàng:</strong> Techcombank</p>
                                
                                <p><strong>Hướng dẫn:</strong></p>
                                <ol style="padding-left: 30px;">
                                    <li>Mở ứng dụng ngân hàng và chọn chức năng chuyển khoản (Hoặc quét mã QR).</li>
                                    <li>Nhập số tài khoản <span class="text-danger">1903 5508 9090 11</span> và số tiền cần thanh toán.</li>
                                    <li>Chụp lại giao dịch chuyển khoản và thêm vào ghi chú bên dưới.</li>
                                </ol>
                                <div id="uploadedPreview" class="hidden">
                                    <p><strong>Ảnh đã tải lên:</strong></p>
                                    <img style="padding: 10px 15px; width: 250; height: 300px" id="uploadedImage" src="#" alt="Preview ảnh thanh toán">
                                </div>
                                <label style="padding: 10px 15px;" for="paymentProof">Tải ảnh biên lai:</label>
                                <input style="padding: 10px 15px; width: 300px;" type="file" id="payment_image" name="payment_image" class="form-control mb-3" >
                                <p id="errorMessage" class="error-message hidden">Vui lòng tải lên ảnh thanh toán!</p>
                                <br>
                                <h5><strong>Cách 2:</strong> Thanh toán trực tiếp</h5>
                                <p><strong>Hướng dẫn:</strong></p>
                                <ol style="padding-left: 30px;">
                                    <li>Đến quầy thông tin của thư viện và cung cấp mã thẻ thư viện hoặc thông tin cá nhân.</li>
                                    <li>Nhân viên sẽ kiểm tra số tiền phạt dựa trên số ngày quá hạn và thông báo cho bạn.</li>
                                    <li>Thanh toán trực tiếp tại quầy thu ngân của thư viện bằng tiền mặt và nhận biên lai xác nhận sau khi hoàn tất thanh toán.</li>
                                    <li>Sau khi thanh toán, đảm bảo nhân viên cập nhật trạng thái tài khoản thư viện của bạn.</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <p style="font-size:20px; font-weight: bold; text-align: center;"> QR Code</p>
                                <div style="text-align: center;">
                                    <img src="uploads/payments/qr_code.jpg" alt="QR Code" class="img-fluid">
                                </div>
                                
                            </div>
                        </div>

                        <!-- Nút thanh toán -->
                        <div class="mt-4 d-flex justify-content-end" style="text-align: center;">
                            <button type="submit" class="btn-primary rounded-pill" style="border-radius: 8px; width: 180px; height: 35px;">
                                <i class="fas fa-money-bill"></i> Hoàn tất thanh toán
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll(".fine-checkbox");
        const totalAmountElement = document.getElementById("totalAmount");
        const bankTransferDetails = document.getElementById("bankTransferDetails");
        const paymentProof = document.getElementById("payment_image");
        const uploadedPreview = document.getElementById("uploadedPreview");
        const uploadedImage = document.getElementById("uploadedImage");
        const submitButton = document.getElementById("button[type='submit']");
        const errorMessage = document.getElementById("alert-message");

        // Hàm tính tổng tiền
        function calculateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.getAttribute("data-amount"));
                }
            });
            totalAmountElement.textContent = total.toLocaleString("vi-VN");
        }

        // Cập nhật tổng tiền khi trang tải
        calculateTotal();

        // Lắng nghe sự thay đổi checkbox để cập nhật tổng tiền
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", calculateTotal);
        });

        // Hiển thị ảnh sau khi tải lên và kích hoạt nút thanh toán
        paymentProof.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    uploadedImage.src = e.target.result;
                    uploadedPreview.classList.remove("hidden");
                    errorMessage.classList.add("hidden");
                    submitButton.disabled = false;
                };
                reader.readAsDataURL(file);
            } else {
                uploadedPreview.classList.add("hidden");
                errorMessage.classList.remove("hidden");
                submitButton.disabled = true;
            }
        });

        // Kiểm tra checkbox trước khi submit
        const form = document.querySelector("form");
        form.addEventListener("submit", function (event) {
            const isAnyCheckboxChecked = Array.from(checkboxes).some(cb => cb.checked);

            if (!isAnyCheckboxChecked) {
                alert("Vui lòng chọn ít nhất một phiếu phạt để thanh toán!");
                event.preventDefault(); // Ngăn gửi form
            }
            else
                if (!paymentProof.value) {
                    alert("Vui lòng tải lên ảnh thanh toán!");
                    e.preventDefault();
                }
        });
    });  
</script>