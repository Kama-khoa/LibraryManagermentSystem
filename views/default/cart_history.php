<div class="container-fluid form" style="padding: 20px;">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h4>Lịch sử mượn sách</h4>
            <?php if (isset($histories) && count($histories) > 0): ?>
                <table class="table table-bordered text-center">
                    <thead style="background-color: #ced4da;">
                        <tr>
                            <th>Tên sách</th>
                            <th>Tác giả</th>
                            <th>Số lượng</th>
                            <th>Ngày mượn</th>
                            <th>Tình trạng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($histories as $history): ?>
                            <tr>
                                <td><?= htmlspecialchars($history['title']) ?></td>
                                <td><?= htmlspecialchars($history['authors']) ?></td>
                                <td><?= htmlspecialchars($history['quantity']) ?></td>
                                <td><?= htmlspecialchars($history['created_at']) ?></td>
                                <td>
                                        <?php
                                            echo $history['status'] === 'lost' ? 'Mất' : 
                                                ($history['status'] === 'damaged' ? 'Hỏng' : 
                                                ($history['status'] === 'returned' ? 'Đã trả' : 
                                                ($history['status'] === 'overdue' ? 'Quá hạn' : 'Chờ xử lý')));
                                        ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Bạn chưa mượn sách nào.</p>
            <?php endif; ?>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12 text-center">
                <button class="btn btn-secondary" onclick="goBack()">Quay lại</button>
            </div>
        </div>
    </div>
</div>

<script>
function goBack() {
    window.history.back();
}
</script>
