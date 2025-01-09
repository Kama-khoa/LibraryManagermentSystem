<?php
foreach ($data_tmp as $book) { ?>
	<div class='product-container' onclick="Display_BookDetail('<?php echo $book['book_id'] ?>')">
		<a data-toggle='modal' href='index.php?model=default&action=show&id=<?php echo $book['book_id'] ?>' data-target='#modal-id'>
			<div style="text-align: center;" class='product-img'>
				<img src='uploads/covers/<?php echo $book['cover_image'] ?>'>
			</div>
			<div class='product-info'>
				<h3 style="color: white;"><b><?php echo $book['title'] ?></b></h3>
				<h5 style="color: white;">Tác giả: <?php echo $book['authors'] ?></h5>
				<div class='buy'>
					<a class='btn btn-primary btn-md cart-container <?php
					if(isset($_SESSION['cart'])){
						if(array_search($book['book_id'], $_SESSION['cart']) !== false){
							echo 'cart-ordered';
						}
					} ?>' data-masp='<?php echo $book['book_id'] ?>' >
					<i title='Thêm vào giỏ hàng' class='glyphicon glyphicon-shopping-cart cart-item'></i>
				</a>
				<a href="client/buynow/<?php echo $book['book_id'] ?>" class="snip0050"><span>Mua ngay</span><i class="glyphicon glyphicon-ok"></i>
				</a>
			</div>
		</div>
	</a>
	</div>
<?php } ?>