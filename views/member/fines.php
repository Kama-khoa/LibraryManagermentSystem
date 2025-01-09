<style>
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
</style>
<div class="container-body ">
    <!-- Thông báo lỗi -->
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
    <!-- Phần content -->
    <div class="card shadow mb-4 ">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Phiếu phạt</h3>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <input style="margin: 20px 0 20px 0" type="search" id="searchInput" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                    </a>
                </div>
            </div>
        </div>
        <?php if (empty($fines)): ?>
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center" style="height: 200px">
                    <h3 class="text-center">Bạn không có phiếu phạt nào! <3</h3>
                </div>
            </div>
        <?php else: ?>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Mã phiếu mượn</th>
                            <th class="text-center">Tên người dùng</th>
                            <th class="text-center">Tên người phụ trách</th>
                            <th class="text-center">Ngày tạo phiếu</th>
                            <th class="text-center">Hạn trả phạt</th>
                            <th class="text-center">Trạng thái </th>
                            <th class="text-center align-middle"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $counter = 1;
                            foreach ($fines as $fine): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $counter++ ?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($fine['loan_id']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($fine['user_name']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($fine['returned_to']) ?></td>
                                <td class="align-middle text-center"><?= htmlspecialchars($fine['issued_date']) ?></td>
                                <td class="align-middle text-center"><?= htmlspecialchars($fine['due_date']) ?></td>
                                <td class="align-middle text-center">
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
                                    <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="text-center align-middle"  style="height: 50px">
                                    <div class="d-flex justify-content-center">
                                        <a href="index.php?model=fine&action=show&id=<?= $fine['fine_id'] ?>" class="btn btn-sm btn-outline-primary me-3" title="Chi tiết">
                                            <i class="fas fa-money-bill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>


<script>    

    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
        dom: 'rtp',
        language: {
            processing: "Đang xử lý...",
            search:'<i class="fas fa-search"></i>',
            lengthMenu: "Hiển thị _MENU_ dòng",
            info: "Đang hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
            infoEmpty: "Không có dữ liệu",
            infoFiltered: "(Được lọc từ _MAX_ bản ghi)",
            infoPostFix: "",
            loadingRecords: "Đang tải...",
            zeroRecords: "Không tìm thấy bản ghi nào",
            emptyTable: "Không có dữ liệu trong bảng",
            paginate: {
                first: "Đầu",
                previous: "Trước",
                next: "Tiếp",
                last: "Cuối"
            },
            aria: {
                sortAscending: ": Sắp xếp tăng dần",
                sortDescending: ": Sắp xếp giảm dần"
            }
        },
        columnDefs: [
            {
                targets: -1, 
                orderable: false, 
                searchable: false 
            }
        ]
    });

    $('#dataTable tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('button').length || $(e.target).closest('form').length) {
            return;
        }
        
        var fine_id = $(this).find('a').attr('href').split('id=')[1];
        
        window.location.href = 'index.php?model=fine&action=show&id=' + fine_id;
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>