<div class="modal-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="col-md-6">
                <h4 style="font-size: 30px; ">Chi tiết sách</h4>
            </div>
            <div class="col-md-6">
                <button type="button" class="close" style="padding-top: 15px;" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid form" style="margin-top: 10px; padding: 20px">
    <div class="row">
        <div class="col-sm-12">
            <div class="main-prd text-center">
                <img src="uploads/covers/<?php echo $book_detail['cover_image'] ?>" class="main-prd-img">
            </div>
            <br>
            <div class="text-center">
                <form method="POST" action="index.php?model=cart&action=create">
                    <input type="hidden" name="book_id" value="<?php echo $book_detail['book_id']; ?>">
                    <input type="hidden" name="quantity" value="1"> 
                    <button type="submit" class="btn btn-primary">
                           <i class="fa-solid fa-basket-shopping"></i> Thêm vào giỏ sách
                    </button>
                </form>
            </div>

            <div class="introduce-prd">
                <h3>Thông tin sách</h3>
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
                            <td><?php $status = $book_detail['status'];
                                echo ($status == 'available') ? 'Còn sách' : 'Hết sách';  ?></td>
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