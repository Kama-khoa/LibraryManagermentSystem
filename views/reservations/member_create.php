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
        justify-content: stretch;
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
    .btn-add {
        display: inline-block;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        text-align: center;
    }
    .btn-danger {
        display: inline-block;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        background-color:rgb(255, 0, 0);
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
    .search-bar {
        margin-bottom: 10px;
        justify-content: flex-end;
    }
    .search-bar input {
        padding: 8px;
        width: 300px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>

<div class="container-body">
    <div class="header">
        <h2>Đặt Hẹn Sách Thư Viện</h2>
    </div>
    <!-- Thông tin người dùng -->
    <div class="user-info border">
        <h4>Thông Tin Người Dùng</h4>
        <p><strong>Trường:</strong> Trường Đại học Công nghệ Giao thông vận tải</p>
        <p><strong>Họ và Tên:</strong><?= $user['full_name'] ?></p>
        <p><strong>Mã tài khoản:</strong> <?= $user['user_id'] ?> </p>
    </div>

    <!-- Danh sách sách -->
    <div class="book-list border">
        <h4>Danh Sách Sách</h4>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Tìm kiếm sách...">
        </div>
        <table id="bookTable">
            <thead>
                <tr>
                    <th>Mã sách</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($booksWithExpectedDate as $book): ?>
                <tr>
                    <td class="book-select"><?= $book['book_id'] ?></td>
                    <td><?= $book['title'] ?></td>
                    <td><?= $book['authors'] ?></td>
                    <td><button class="btn-add add-btn" data-id="<?= $book['book_id'] ?>" data-title="<?= $book['title'] ?>" data-author="<?= $book['authors'] ?>" data-date="<?= $book['expected_date'] ?>">Thêm</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form action="index.php?model=reservation&action=member_create" method="POST">
    <!-- Phiếu hẹn -->
    <div id="hiddenInputs"></div>
    <div class="appointment-card border">
        <h4>Phiếu Hẹn Sách</h4>
        <table id="appointmentTable">
            <thead>
                <tr>
                    <th>Mã sách</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Ngày dự kiến</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <label for="notes" class="form-label">Ghi Chú:</label>
                <textarea name="notes" id="notes" class="form-control" required
                    placeholder="Vui lòng điền đầy đủ: Họ và Tên, Lớp, Mã SV (Bắt buộc)"></textarea>
            </tbody>
        </table>
    </div>

    <!-- Nút gửi phiếu hẹn -->
    <div style="text-align: center;">
        <button type="submit" class="btn-add">Gửi Phiếu Hẹn</button>
    </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const bookTable = document.getElementById("bookTable");
    const bookTableBody = bookTable.querySelector("tbody");
    const appointmentTable = document.querySelector("#appointmentTable tbody");
    const addButtons = document.querySelectorAll(".add-btn");
    const hiddenInputs = document.getElementById('hiddenInputs');
    
    bookTableBody.style.display = "none";

    // Tìm kiếm sách
    searchInput.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        const rows = bookTableBody.querySelectorAll("tr");

        if (searchTerm.trim() === "") {
            bookTableBody.style.display = "none";
        } else {
            bookTableBody.style.display = "";
            rows.forEach(row => {
                const title = row.children[1].textContent.toLowerCase();
                const author = row.children[2].textContent.toLowerCase();
                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    });

    // Thêm sách vào phiếu hẹn
    addButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const title = this.dataset.title;
            const author = this.dataset.author;
            const date = this.dataset.date;

            // Kiểm tra nếu sách đã tồn tại trong phiếu hẹn
            const existingRows = Array.from(appointmentTable.children);
            const exists = existingRows.some(row => row.children[0].textContent === id);
            if (exists) {
                alert("Sách này đã có trong phiếu hẹn!");
                return;
            }

            // Tạo hidden input cho book_id
            const bookIdInput = document.createElement('input');
            bookIdInput.type = 'hidden';
            bookIdInput.name = 'book_id[]';
            bookIdInput.value = id;
            hiddenInputs.appendChild(bookIdInput);

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${id}</td>
                <td>${title}</td>
                <td>${author}</td>
                <td>${date}</td>
                <td><button type="button" class="btn-danger remove-btn">Gỡ</button></td>
            `;
            appointmentTable.appendChild(row);

            // Cập nhật expected_date lớn nhất
            updateMaxExpectedDate();

            // Gỡ sách khỏi phiếu hẹn
            row.querySelector(".remove-btn").addEventListener("click", function () {
                // Remove book_id input
                const bookInput = hiddenInputs.querySelector(input[name="book_id[]"][value="${id}"]);
                if (bookInput) bookInput.remove();
                row.remove();
                
                // Cập nhật lại expected_date sau khi xóa
                updateMaxExpectedDate();
            });
        });
    });

    // Hàm cập nhật expected_date lớn nhất
    function updateMaxExpectedDate() {
        // Xóa expected_date cũ nếu có
        const oldExpectedDate = hiddenInputs.querySelector('input[name="expected_date"]');
        if (oldExpectedDate) oldExpectedDate.remove();

        // Lấy tất cả các ngày từ bảng
        const rows = Array.from(appointmentTable.children);
        let maxDate = '';
        
        rows.forEach(row => {
            const date = row.children[3].textContent;
            if (date > maxDate) {
                maxDate = date;
            }
        });

        // Chỉ tạo expected_date mới nếu có ít nhất một sách
        if (maxDate) {
            const expectedDateInput = document.createElement('input');
            expectedDateInput.type = 'hidden';
            expectedDateInput.name = 'expected_date';
            expectedDateInput.value = maxDate;
            hiddenInputs.appendChild(expectedDateInput);
        }
    }

    // Xử lý form submission
    document.querySelector("form").addEventListener("submit", function (e) {
        const rows = Array.from(appointmentTable.children);
        if (rows.length === 0) {
            e.preventDefault();
            alert("Vui lòng chọn ít nhất một sách để đặt hẹn!");
            return false;
        }
        return true;
    });
});
</script> 