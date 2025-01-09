<style>
.chart-pie {
    height: 350px !important;  /* Tăng chiều cao */
}
</style>
<div class="container-fluid">
    <div class="row my-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 py-2" style="background-color: #f8f9fc;">
                    <li class="breadcrumb-item"><a href="index.php?">Trang chủ</a></li>
                    <li class="breadcrumb-item">Thống kê</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng số sách</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBooks ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đang mượn</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $activeLoans ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Quá hạn</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $overdueLoans ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row">
        <!-- Biểu đồ mượn sách theo tháng -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê mượn sách theo tháng</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyLoansChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ phân bố theo danh mục -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố sách theo danh mục</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng thống kê -->
    <div class="row">
        <!-- Sách mượn nhiều nhất -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sách mượn nhiều nhất</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Số lần mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($popularBooks as $book): ?>
                                <tr>
                                    <td><?= htmlspecialchars($book['title']) ?></td>
                                    <td><?= htmlspecialchars($book['authors']) ?></td>
                                    <td><?= $book['borrow_count'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
$(function() {
    // Biểu đồ mượn sách theo tháng
    var monthlyData = <?= json_encode($monthlyStats) ?>;
    var ctx = document.getElementById('monthlyLoansChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Số lượt mượn',
                    data: monthlyData.map(item => item.loan_count),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Biểu đồ phân bố theo danh mục
    var categoryData = <?= json_encode($categoryStats) ?>;
    var ctx2 = document.getElementById('categoryChart');
if (ctx2) {
    new Chart(ctx2.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.name),
            datasets: [{
                data: categoryData.map(item => item.book_count),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false  // Ẩn chú thích
                }
            },
            cutout: '0%'  // Làm cho vòng tròn mỏng hơn
        }
    });
}
});
</script>
