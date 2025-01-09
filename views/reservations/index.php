<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row my-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 py-2" style="background-color: #f8f9fc;">
                    <li class="breadcrumb-item"><a href="index.php?">Trang chủ</a></li>
                    <li class="breadcrumb-item">Quản lý đặt sách</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Thông báo lỗi -->
    <?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('alert-message');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 150);
                }
            }, 2000);
        </script>
    <?php endif; ?>

    <!-- Nội dung chính -->
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Quản lý đặt sách</h5>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <input type="search" id="searchInput" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                    <a href="index.php?model=reservation&action=create" class="btn btn-primary ml-3">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a href="index.php?model=reservation&action=export" class="btn btn-primary ml-3">
                        <i class="fa-solid fa-file-excel"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Tên người đặt</th>
                            <th>Tên sách</th>
                            <th>Ngày đặt</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th class="text-center"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $reservation['reservation_id'] ?></td>
                                <td class="align-middle"><?= htmlspecialchars($reservation['full_name']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($reservation['titles']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars((new DateTime($reservation['reservation_date']))->format('d/m/Y')) ?></td>
                                <td class="align-middle"><?= htmlspecialchars((new DateTime($reservation['expiry_date']))->format('d/m/Y')) ?></td>
                                <td class="align-middle">
                                <?php 
                                    $statusLabels = [
                                        'pending' => 'Đang xử lý',
                                        'confirmed' => 'Đã xác nhận',
                                        'fulfilled' => 'Hoàn thành',
                                        'expired' => 'Hết hạn',
                                        'cancelled' => 'Bị hủy',
                                    ];
                                    echo htmlspecialchars($statusLabels[$reservation['status']] ?? 'Không xác định');
                                    ?>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center">
                                        <a href="index.php?model=reservation&action=edit&id=<?= $reservation['reservation_id'] ?>" class="btn btn-sm btn-outline-primary me-3" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="index.php?model=reservation&action=delete&id=<?= $reservation['reservation_id'] ?>" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                                            <button type="submit" class="btn btn-sm btn-outline-danger mx-2" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#dataTable').DataTable({
        dom: 'rtp',
        language: {
            processing: "Đang xử lý...",
            search: '<i class="fas fa-search"></i>',
            lengthMenu: "Hiển thị _MENU_ dòng",
            info: "Đang hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
            infoEmpty: "Không có dữ liệu",
            infoFiltered: "(Được lọc từ _MAX_ bản ghi)",
            loadingRecords: "Đang tải...",
            zeroRecords: "Không tìm thấy bản ghi nào",
            emptyTable: "Không có dữ liệu trong bảng",
            paginate: {
                first: "Đầu",
                previous: "Trước",
                next: "Tiếp",
                last: "Cuối"
            }
        },
        columnDefs: [
            { targets: -1, orderable: false, searchable: false }
        ]
    });

    $('#dataTable tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('button').length || $(e.target).closest('form').length) {
            return;
        }
        
        var reservationId = $(this).find('td:first').text().trim();
        
        window.location.href = 'index.php?model=reservation&action=edit&id=' + reservationId;
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});

function confirmDelete() {
    return confirm('Bạn có chắc muốn xóa đặt chỗ này?');
}
</script>
