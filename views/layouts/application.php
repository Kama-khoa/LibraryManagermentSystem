<html lang="en">

<head>
	<link rel="icon" href="./assets/images/header_ic.png" type="image/png">
	<title> E-library</title>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="public/css/style.css">
	<link rel="stylesheet" href="public/bootstrap/css/bootstrap.css">
	<script src="public/jquery/jquery-latest.js"></script>
	<script type="text/javascript" src="public/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="public/script/script.js"></script>
	<link rel="stylesheet" type="text/css" href="public/animate.css">
	<style>
		.scroll-to-top {
			position: fixed;
			bottom: 40px;
			right: 40px;
			background: linear-gradient(145deg, #ffffff, #f0f0f0);
			width: 50px;
			height: 50px;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			text-decoration: none;
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			opacity: 0.9;
			backdrop-filter: blur(8px);
			box-shadow:
				0 10px 15px -3px rgba(0, 0, 0, 0.1),
				0 4px 6px -2px rgba(0, 0, 0, 0.05),
				inset 0 -2px 5px rgba(255, 255, 255, 0.2);
		}

		.scroll-to-top:hover {
			transform: translateY(-5px) scale(1.05);
			opacity: 1;
			box-shadow:
				0 20px 25px -5px rgba(0, 0, 0, 0.1),
				0 10px 10px -5px rgba(0, 0, 0, 0.04),
				inset 0 -2px 5px rgba(255, 255, 255, 0.2);
		}

		.scroll-to-top i {
			font-size: 24px;
			transform: translateY(2px);
			transition: transform 0.3s ease;
			color: #4f46e5;
		}

		.scroll-to-top:hover i {
			transform: translateY(-2px);
		}

		/* Giữ màu icon khi click */
		.scroll-to-top:active i,
		.scroll-to-top:visited i,
		.scroll-to-top:link i {
			color: #4f46e5;
		}

		/* Add smooth scrolling */
		html {
			scroll-behavior: smooth;
		}

		/* Demo content */
		body {
			margin: 0;
			background: #f5f5f5;
		}

		.content {
			padding: 20px;
			height: 2000px;
			background: linear-gradient(180deg, #f5f5f5, #e5e5e5);
		}


		footer {
			background: linear-gradient(145deg, #1a1c23, #2d2f36);
			color: #ffffff;
			padding: 60px 0 20px 0;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		.map-container {
			position: relative;
			overflow: hidden;
			border-radius: 12px;
			box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
		}

		.map-container iframe {
			width: 100%;
			max-width: 600px;
			height: 300px;
			border-radius: 12px;
		}

		.contact-info {
			background: rgba(255, 255, 255, 0.05);
			padding: 30px;
			border-radius: 12px;
			backdrop-filter: blur(10px);
		}

		.contact-info h3 {
			color: #fff;
			font-weight: 600;
			margin-bottom: 25px;
			font-size: 24px;
		}

		.contact-info i {
			color: #4f46e5;
			margin-right: 10px;
			font-size: 18px;
			width: 24px;
		}

		.contact-info span {
			color: #e2e2e2;
			font-size: 15px;
			line-height: 35px;
		}

		.newsletter {
			margin-top: 30px;
		}

		.newsletter h4 {
			color: #e2e2e2;
			font-size: 16px;
			margin-bottom: 20px;
		}

		.newsletter-form {
			display: flex;
			gap: 10px;
		}

		.newsletter-form input {
			background: rgba(255, 255, 255, 0.1);
			border: 1px solid rgba(255, 255, 255, 0.1);
			color: white;
			border-radius: 8px;
			padding: 10px 15px;
			flex-grow: 1;
		}

		.newsletter-form input::placeholder {
			color: rgba(255, 255, 255, 0.5);
		}

		.newsletter-form button {
			background: #4f46e5;
			color: white;
			border: none;
			padding: 10px 25px;
			border-radius: 8px;
			transition: all 0.3s ease;
		}

		.newsletter-form button:hover {
			background: #4338ca;
			transform: translateY(-2px);
		}

		.copyright {
			text-align: center;
			padding-top: 40px;
			margin-top: 40px;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
			color: #a0a0a0;
			font-size: 14px;
		}

		@media (max-width: 768px) {
			.map-container {
				margin-bottom: 30px;
			}

			.newsletter-form {
				flex-direction: column;
			}

			.newsletter-form button {
				width: 100%;
			}
		}

		/* Căn chỉnh container */
		.searchbox-desktop {
			display: flex;
			align-items: center;
			gap: 8px;
			padding: 0px;
		}

		/* Tùy chỉnh ô input */
		.searchbox-desktop .form-control {
			border: 1px solid #e0e0e0;
			border-radius: 20px;
			padding: 8px 16px;
			font-size: 14px;
			width: 250px;
			transition: all 0.3s ease;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
		}

		.searchbox-desktop .form-control:focus {
			outline: none;
			border-color: #007bff;
			box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
		}

		.searchbox-desktop .form-control::placeholder {
			color: #999;
		}

		/* Tùy chỉnh nút search */
		.searchbox-desktop #searchBtn {
			background: none;
			border: none;
			padding: 8px 12px;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.searchbox-desktop #searchBtn i {
			color: #007bff;
			font-size: 16px;
		}

		.searchbox-desktop #searchBtn:hover i {
			color: #0056b3;
		}

		/* Loại bỏ outline khi focus vào nút */
		.searchbox-desktop #searchBtn:focus {
			outline: none;
			box-shadow: none;
		}

		/* Đảm bảo responsive */
		@media (max-width: 768px) {
			.searchbox-desktop .form-control {
				width: 200px;
			}
		}

		#user-setting {
			position: absolute;
			top: 100%;
			right: 0;
			background: white;
			border-radius: 8px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
			min-width: 160px;
			z-index: 1000;
			margin-top: 5px;
			border: 1px solid rgba(0, 0, 0, 0.1);
			overflow: hidden;
		}

		#user-setting ul {
			list-style: none;
			padding: 0;
			margin: 0;
		}

		#user-setting ul a {
			text-decoration: none;
			color: #333 !important;
			display: block;
			padding: 10px 15px;
			transition: all 0.2s ease;
		}

		#user-setting ul a:hover {
			background-color: #f0f0f0;
			/* Màu xám khi hover */
			color: #333 !important;
			/* Giữ nguyên màu chữ */
		}

		#user-setting ul li {
			border-bottom: 1px solid #eee;
			margin: 0;
			color: #333;
			font-size: 14px;
		}

		#user-setting ul li:last-child {
			border-bottom: none;
		}

		#user-setting ul li i {
			color: #666;
			margin-right: 8px;
			width: 16px;
			text-align: center;
		}

		#user-setting::before {
			content: '';
			position: absolute;
			top: -8px;
			right: 20px;
			width: 12px;
			height: 12px;
			background: white;
			transform: rotate(45deg);
			border-left: 1px solid rgba(0, 0, 0, 0.1);
			border-top: 1px solid rgba(0, 0, 0, 0.1);
		}

		/* Ẩn ban đầu */
		#user-setting {
			display: none;
		}

		.avatar-circle {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid #fff;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}
	</style>
</head>

<body>
	<header id='header'>
		<a class="mx-3" href="index.php"><img src="assets/images/header_ic.png">
			<h2 class="logo">E-LIBRARY</h2>
		</a>
		<ul class="header-menu">
			<?php
			if ((!isset($_SESSION['user_id']))) { ?>
				<!-- if(($_SESSION['user']) == ""){ ?> -->
				<li><a href="index.php?model=auth&action=login" id="s-s" data-stt='nosignin'>Đăng nhập</a>
					<div class='mn-ef'></div>
				</li>
				<li><a href="index.php?model=auth&action=register">Đăng ký</a>
					<div class='mn-ef'></div>
				</li>
			<?php } else { ?>
				<?php
				// Kiểm tra và lấy đường dẫn avatar
				$avatar = isset($_SESSION['avatar_url'])
					? 'uploads/avatars/' . $_SESSION['avatar_url']
					: 'assets/images/default-avatar.png';
				?>

				<img class="avatar-circle" src="<?php echo htmlspecialchars($avatar); ?>" alt="Profile Avatar">
				<li style="position: relative;"><a class="" onclick="$('#user-setting').toggle()" id="s-s">Chào
						<?php echo $_SESSION['full_name'] ?></a>
					<div class='mn-ef'></div>
				</li>
				<div id='user-setting'>
					<ul>
						
						<?php if (($_SESSION['role_id'] != 3)) { ?>
							<a href="index.php?model=default&action=admin_dashboard">
								<li>
									<i class="fas fa-user-shield"></i>
									Trang Admin
								</li>
							</a>
						<?php } else { ?>
							<a href="index.php?model=member&action=edit&id=<?php echo $_SESSION['user_id'] ?>">
								<li>
									<i class="fas fa-user"></i>
									Thông tin tài khoản
								</li>
							</a>
							<a href="index.php?model=fine&action=fines&id=<?php echo $_SESSION['user_id'] ?>">
								<li>
									<i class="fa-solid fa-file"></i>
									Kiểm tra phiếu phạt
								</li>
							</a>
							<a href="index.php?model=member&action=change_password&id=<?php echo $_SESSION['user_id'] ?>">
								<li>
									<i class="fas fa-key"></i>
									Đổi mật khẩu
								</li>
							</a>
						<?php } ?>
						<a href="index.php?model=auth&action=logout">
							<li>
								<i class="fas fa-sign-out-alt"></i>
								Đăng xuất
							</li>
						</a>
					</ul>
				</div>
			<?php }
			?>
			<li><a href="index.php?model=cart&action=index"><i class="fa-solid fa-basket-shopping"></i> Giỏ sách</a>
				<div class="mn-ef"></div>
			</li>
		</ul>
		<div class="header-detail">
			<p>54 P. Triều Khúc, Thanh Xuân Nam, Thanh Xuân, Hà Nội<br>
				<i>8h - 18h Hằng ngày</i>
			</p>
		</div>
	</header>

	<nav class="navbar navbar-default" role="navigation" id="nav">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand logo" href="index.php">E-library</a>
				<div id="custom-search-input">
					<div class="input-group col-md-12" style="background-color: white;">
						<input type="text" class="form-control input-lg" placeholder="Bạn tìm gì?" id='src-v' />
						<span class="input-group-btn">
							<button class="btn btn-info btn-lg" type="button">
								<i class="fa-solid fa-magnifying-glass"></i>
							</button>
						</span>
					</div>
				</div>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li class="dropdown menu-name">
						<a class="dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">Danh mục sách <b
								class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="index.php?model=book&action=list&id=All">Tất cả danh mục</a></li>
							<?php 
							include_once 'models/Category.php';
							$data = new Category();
							$categories = $data->read();
							foreach ($categories as $category): ?>
								<li>
									<a href="index.php?model=book&action=list&id=<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
					<li class="menu-name" id="spm"><a href="index.php?model=book&action=list&id=Newest">Sách mới</a></li>
					<li class="menu-name" id="mntq"><a href="index.php?model=book&action=list&id=TopWeek">Top sách trong tuần</a></li>

				</ul>
				<div class="navbar-right" style="cursor: pointer; padding-top: 5px; display: inline-block;">
					<a href="index.php?model=cart&action=index" style="color: #007bff">
						<i class="fa-solid fa-basket-shopping navbar-right btn-lg" id="cart_count">
							<?php if (isset($_SESSION['cart'])) {
								echo count($_SESSION['cart']);
							} else
								echo "0"; ?>
						</i>
					</a>
				</div>
				<div class="navbar-right"
					style="display: inline-block; border-right: 2px solid #ccc; height: 30px; margin: 7px 0px; vertical-align: middle;">
				</div>
				<div class="navbar-form navbar-right searchbox-desktop">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Bạn tìm gì?" id='srch-val'>
					</div>
					<span class="btn btn-primary" id="searchBtn"><i class="fa-solid fa-magnifying-glass"></i></span>
				</div>

			</div><!-- /.navbar-collapse -->
		</div>
	</nav>

	<div id="bodyContainer">
		<?php if (isset($content)) {
			include($content);
		} ?>
	</div>


	<div class="modal fade" id="modal-id" data-remote="" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content" id="modal-book">


				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-7 mb-4 mb-lg-0">
					<div class="map-container">
						<iframe
							src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.218994773127!2d105.7954915115683!3d20.983856880573445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135acc6bdc7f95f%3A0x58ffc66343a45247!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgR2lhbyB0aMO0bmcgVuG6rW4gdOG6o2k!5e0!3m2!1svi!2s!4v1734702937379!5m2!1svi!2s"
							width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="contact-info">
						<h3>Liên Hệ</h3>
						<p>
							<i class="fas fa-map-marker-alt"></i>
							<span>54 P. Triều Khúc, Thanh Xuân Nam, Thanh Xuân, Hà Nội, Việt Nam</span>
						</p>
						<p>
							<i class="fas fa-phone"></i>
							<span>0865 371 378</span>
						</p>
						<p>
							<i class="fas fa-envelope"></i>
							<span>hamanhhung20012004@gmail.com</span>
						</p>

						<div class="newsletter">
							<h4>Đăng ký nhận thông báo</h4>
							<div class="newsletter-form">
								<input type="email" placeholder="Email của bạn" class="form-control">
								<button class="btn" onclick="alert('Cảm ơn bạn đã đăng ký!')">
									Đăng ký
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="copyright">
				<p>© 2024 . Tất cả quyền được bảo lưu.</p>
			</div>
		</div>
	</footer>
	<a href="#top" class="scroll-to-top">
		<i class="fas fa-arrow-up"></i>
	</a>
</body>
<!-- Bootstrap core JavaScript-->
<script src="./vendor/jquery/jquery.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="./vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="./assets/js/myscript.js"></script>

<!-- Page level plugins -->
<script src="./vendor/datatables/jquery.dataTables.min.js"></script>
<script src="./vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
    
<script src="https://kit.fontawesome.com/1b233c9fdd.js" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b233c9fdd.js" crossorigin="anonymous"></script>
<script>
	$(document).ready(function () {
		// Store references to elements
		const userSetting = $('#user-setting');
		const userMenuButton = $('[onclick="$(\'#user-setting\').toggle()"]');

		// Handle clicks on the document
		$(document).on('click', function (event) {
			// Check if click target is outside menu and menu button
			if (!userSetting.is(event.target) &&
				!userSetting.has(event.target).length &&
				!userMenuButton.is(event.target) &&
				!userMenuButton.has(event.target).length) {

				// Hide menu if it's visible
				if (userSetting.is(':visible')) {
					userSetting.hide();
				}
			}
		});

		// Remove the inline onclick attribute and handle click with jQuery
		userMenuButton.removeAttr('onclick').on('click', function (event) {
			event.stopPropagation(); // Prevent document click from immediately closing menu
			userSetting.toggle();
		});
	});
	function search(){
	var q = $('#srch-val').val();
	$('#bodyContainer').empty();
	$.ajax({
		url : "index.php?model=book&action=search",
		type : "get",
		dataType:"text",
		data : {
		q
		},
		success : function (result){
		$('#bodyContainer').html(result);
		}
	});
	}
</script>

</html>