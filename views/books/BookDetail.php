<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="container-fluid form" style="margin-top: -23px; padding: 20px">
    <div class="row">
        <div class="col-sm-12">
            <div class="main-prd">
                <img src="uploads/covers/<?php echo $book_detail['cover_image'] ?>" class="main-prd-img">
                <div class="basic-info">
                    <h2><?php echo $book_detail['title'] ?></h2>
                    <h4><b>Thông tin cơ bản</b></h4>
                    <ul>
                        <li>Thể loại: <?php echo $book_detail['categories'] ?></li>
                        <li>Phiên bản: <?php echo $book_detail['edition'] ?></li>
                        <li>Số trang: <?php echo $book_detail['pages'] ?></li>
                        <li>Ngôn ngữ: <?php echo $book_detail['language'] ?></li>
                        <li>Tác giả: <?php echo $book_detail['authors'] ?></li>
                        <li>Mô tả: <?php echo $book_detail['description'] ?></li>
                        <li>Số lượng: <?php echo $book_detail['quantity'] ?></li>
                        <li>Số lượng còn lại: <?php echo $book_detail['available_quantity'] ?></li>
                        <li>Tình trạng: <?php echo $book_detail['status'] ?></li>
                        <br>
                        <form method="POST" action="index.php?model=cart&action=create">
                            <input type="hidden" name="book_id" value="<?php echo $book_detail['book_id']; ?>">
                            <input type="hidden" name="quantity" value="1"> 
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-basket-shopping"></i> Thêm vào giỏ sách
                            </button>
                        </form>
                    </ul>
                </div>
            </div>

            <div style="clear: both;"></div>

            <div class="introduce-prd">
                <h3>Thông số kỹ thuật</h3>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Đặc điểm</th>
                            <th>Giá trị</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nhà xuất bản</td>
                            <td><?php echo $book_detail['publisher_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Năm xuất bản</td>
                            <td><?php echo $book_detail['publication_year'] ?></td>
                        </tr>
                        <tr>
                            <td>Phiên bản</td>
                            <td><?php echo $book_detail['edition'] ?></td>
                        </tr>
                        <tr>
                            <td>Số trang</td>
                            <td><?php echo $book_detail['pages'] ?></td>
                        </tr>
                        <tr>
                            <td>Ngôn ngữ</td>
                            <td><?php echo $book_detail['language'] ?></td>
                        </tr>
                        <tr>
                            <td>Tác giả</td>
                            <td><?php echo $book_detail['authors'] ?></td>
                        </tr>
                        <tr>
                            <td>Mô tả</td>
                            <td><?php echo $book_detail['description'] ?></td>
                        </tr>
                        <tr>
                            <td>Số lượng</td>
                            <td><?php echo $book_detail['quantity'] ?></td>
                        </tr>
                        <tr>
                            <td>Số lượng còn lại</td>
                            <td><?php echo $book_detail['available_quantity'] ?></td>
                        </tr>
                        <tr>
                            <td>Tình trạng</td>
                            <td><?php echo $book_detail['status'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>