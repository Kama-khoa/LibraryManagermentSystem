
-- Insert roles
INSERT INTO Role (role_id, name, description, created_at, updated_at) 
VALUES 
(1, 'Admin', 'Quản trị viên hệ thống với quyền truy cập đầy đủ', NOW(), NOW()),
(2, 'Librarian', 'Thủ thư phụ trách quản lý sách và xử lý yêu cầu của người dùng', NOW(), NOW()),
(3, 'Member', 'Người dùng thông thường với quyền mượn sách', NOW(), NOW());

-- Insert all permissions
INSERT INTO Permission (permission_id, name, description, created_at, updated_at) 
VALUES 
(1, 'manage_users', 'Quyền quản lý tài khoản người dùng', NOW(), NOW()),
(2, 'manage_authors', 'Quyền quản lý thông tin tác giả', NOW(), NOW()),
(3, 'manage_books', 'Quyền quản lý sách', NOW(), NOW()),
(4, 'manage_loans', 'Quyền quản lý mượn trả sách', NOW(), NOW()),
(5, 'manage_categories', 'Quyền quản lý danh mục sách', NOW(), NOW()),
(6, 'manage_publishers', 'Quyền quản lý nhà xuất bản', NOW(), NOW()),
(7, 'manage_reservations', 'Quyền quản lý đặt sách', NOW(), NOW()),
(8, 'manage_bookConditions', 'Quyền quản lý tình trạng sách', NOW(), NOW()),
(9, 'manage_members', 'Quyền quản lý thành viên', NOW(), NOW()),
(10, 'manage_fines', 'Quyền quản lý phiếu phạt', NOW(), NOW());

-- Assign permissions to roles
INSERT INTO Role_Permission (role_id, permission_id, created_at) 
VALUES 
-- Admin gets all permissions
(1, 1, NOW()), -- manage_users
(1, 2, NOW()), -- manage_authors
(1, 3, NOW()), -- manage_books
(1, 4, NOW()), -- manage_loans
(1, 5, NOW()), -- manage_categories
(1, 6, NOW()), -- manage_publishers
(1, 7, NOW()), -- manage_reservations
(1, 8, NOW()), -- manage_bookConditions
(1, 9, NOW()),
(1, 10, NOW()),
-- Librarian gets all management permissions except user management
(2, 2, NOW()), -- manage_authors
(2, 3, NOW()), -- manage_books
(2, 4, NOW()), -- manage_loans
(2, 5, NOW()), -- manage_categories
(2, 6, NOW()), -- manage_publishers
(2, 7, NOW()), -- manage_reservations
(2, 8, NOW()),
(2, 9, NOW()); 

INSERT INTO User (role_id, username, password, email, full_name, date_of_birth, gender, phone, address, member_type, expiry_date, max_books, status, note, created_at, updated_at) VALUES
(1, 'admin', 'adminpass', 'admin@example.com', N'Nguyễn Văn Admin', '1980-01-01', 'male', '0901234567', N'123 Lê Lợi, Hà Nội', 'Admin', '2030-01-01', 999, 'active', NULL, NOW(), NOW()),
(2, 'librarian1', 'lib123', 'lib1@lib.com', N'Trần Thị Thủ Thư', '1985-03-15', 'female', '0912345678', N'456 Nguyễn Huệ, TP.HCM', 'Staff', '2030-01-01', 10, 'active', NULL, NOW(), NOW()),
(2, 'librarian2', 'lib456', 'lib2@lib.com', N'Lê Văn Quản Lý', '1988-06-20', 'male', '0923456789', N'789 Trần Phú, Đà Nẵng', 'Staff', '2030-01-01', 10, 'active', NULL, NOW(), NOW()),
(3, 'member1', 'mem123', 'mem1@example.com', N'Phạm Thị Hoa', '1995-02-10', 'female', '0934567890', N'321 Hai Bà Trưng, Hà Nội', 'Regular', '2025-01-01', 5, 'active', NULL, NOW(), NOW()),
(3, 'member2', 'mem456', 'mem2@example.com', N'Nguyễn Văn Nam', '1992-07-25', 'male', '0945678901', N'654 Lê Duẩn, TP.HCM', 'Premium', '2025-01-01', 8, 'active', NULL, NOW(), NOW()),
(3, 'member3', 'mem789', 'mem3@example.com', N'Trần Văn Bình', '1998-04-12', 'male', '0956789012', N'987 Ngô Quyền, Hải Phòng', 'Regular', '2025-01-01', 5, 'active', NULL, NOW(), NOW()),
(3, 'member4', 'mem101', 'mem4@example.com', N'Lê Thị Mai', '1997-09-30', 'female', '0967890123', N'147 Trần Hưng Đạo, Đà Nẵng', 'Premium', '2025-01-01', 8, 'active', NULL, NOW(), NOW()),
(3, 'member5', 'mem202', 'mem5@example.com', N'Hoàng Văn Đức', '1993-11-15', 'male', '0978901234', N'258 Phan Chu Trinh, Huế', 'Regular', '2025-01-01', 5, 'active', NULL, NOW(), NOW()),
(3, 'member6', 'mem303', 'mem6@example.com', N'Nguyễn Thị Lan', '1996-08-20', 'female', '0989012345', N'369 Lý Thường Kiệt, Cần Thơ', 'Premium', '2025-01-01', 8, 'active', NULL, NOW(), NOW()),
(3, 'member7', 'mem404', 'mem7@example.com', N'Trần Văn Hùng', '1991-12-05', 'male', '0990123456', N'741 Lê Thánh Tôn, TP.HCM', 'Regular', '2025-01-01', 5, 'active', NULL, NOW(), NOW()),
(3, 'member8', 'mem505', 'mem8@example.com', N'Phạm Thị Hương', '1994-05-22', 'female', '0901234562', N'852 Nguyễn Văn Linh, Đà Nẵng', 'Premium', '2025-01-01', 8, 'active', NULL, NOW(), NOW()),
(3, 'member9', 'mem606', 'mem9@example.com', N'Lê Văn Tùng', '1999-03-17', 'male', '0912345672', N'963 Trần Phú, Hà Nội', 'Regular', '2025-01-01', 5, 'active', NULL, NOW(), NOW()),
(3, 'member10', 'mem707', 'mem10@example.com', N'Hoàng Thị Thủy', '1990-10-08', 'female', '0923456782', N'159 Điện Biên Phủ, TP.HCM', 'Premium', '2025-01-01', 8, 'active', NULL, NOW(), NOW()),
(3, 'member11', 'mem808', 'mem11@example.com', N'Nguyễn Văn Long', '1995-07-14', 'male', '0934567892', N'753 Nguyễn Trãi, Hải Phòng', 'Regular', '2025-01-01', 5, 'active', NULL, NOW(), NOW()),
(3, 'member12', 'mem909', 'mem12@example.com', N'Trần Thị Thu', '1997-01-25', 'female', '0945678902', N'951 Lê Lợi, Đà Nẵng', 'Premium', '2025-01-01', 8, 'active', NULL, NOW(), NOW());

-- Insert dữ liệu cho bảng publisher
INSERT INTO publisher (name, address, phone, email, website, created_at, updated_at) VALUES
(N'NXB Kim Đồng', N'55 Quang Trung, Hà Nội', '024-39434730', 'info@nxbkimdong.com.vn', 'www.nxbkimdong.com.vn', NOW(), NOW()),
(N'NXB Trẻ', N'161B Lý Chính Thắng, Q3, TP.HCM', '028-39316289', 'hopthubandoc@nxbtre.com.vn', 'www.nxbtre.com.vn', NOW(), NOW()),
(N'NXB Nhã Nam', N'59 Đỗ Quang, Cầu Giấy, Hà Nội', '024-37345415', 'info@nhanam.vn', 'www.nhanam.vn', NOW(), NOW()),
(N'NXB Phụ Nữ', N'39 Hàng Chuối, Hà Nội', '024-39710717', 'nxbphunu@gmail.com', 'www.nxbphunu.com.vn', NOW(), NOW()),
(N'NXB Văn Học', N'18 Nguyễn Trường Tộ, Ba Đình, Hà Nội', '024-37161518', 'info@nxbvanhoc.com.vn', 'www.nxbvanhoc.com.vn', NOW(), NOW()),
(N'NXB Giáo Dục', N'81 Trần Hưng Đạo, Hà Nội', '024-38220801', 'contact@nxbgd.vn', 'www.nxbgiaoduc.vn', NOW(), NOW()),
(N'NXB Hội Nhà Văn', N'65 Nguyễn Du, Hà Nội', '024-38222135', 'info@nxbhoinhavan.vn', 'www.nxbhoinhavan.vn', NOW(), NOW()),
(N'NXB Tổng hợp TP.HCM', N'62 Nguyễn Thị Minh Khai, Q1, TP.HCM', '028-38225340', 'info@nxbhcm.com.vn', 'www.nxbhcm.com.vn', NOW(), NOW()),
(N'NXB Lao Động', N'175 Giảng Võ, Hà Nội', '024-38515380', 'info@nxblaodong.com.vn', 'www.nxblaodong.com.vn', NOW(), NOW()),
(N'NXB Thanh Niên', N'64 Bà Triệu, Hà Nội', '024-39434844', 'info@nxbthanhnien.vn', 'www.nxbthanhnien.vn', NOW(), NOW()),
(N'NXB Hà Nội', N'4 Tống Duy Tân, Hà Nội', '024-38252916', 'info@nxbhanoi.com.vn', 'www.nxbhanoi.com.vn', NOW(), NOW()),
(N'NXB Tư pháp', N'35 Trần Quốc Toản, Hà Nội', '024-37739717', 'info@nxbtuphap.vn', 'www.nxbtuphap.vn', NOW(), NOW()),
(N'NXB Thế Giới', N'46 Trần Hưng Đạo, Hà Nội', '024-38253841', 'info@thegioipublishers.vn', 'www.thegioipublishers.vn', NOW(), NOW()),
(N'NXB Đại học Quốc gia Hà Nội', N'16 Hàng Chuối, Hà Nội', '024-39714896', 'info@nxb.vnu.edu.vn', 'www.press.vnu.edu.vn', NOW(), NOW()),
(N'NXB Chính trị Quốc gia', N'24 Quang Trung, Hà Nội', '024-38223786', 'info@nxbctqg.vn', 'www.nxbctqg.org.vn', NOW(), NOW());

-- Thêm dữ liệu cho bảng author (bổ sung thêm cho đủ 15 bản ghi)
INSERT INTO author (name, biography, birth_date, nationality, created_at, updated_at) VALUES
(N'Nguyễn Nhật Ánh', N'Nhà văn chuyên viết cho thiếu nhi và tuổi mới lớn', '1955-05-07', N'Việt Nam', NOW(), NOW()),
(N'Tô Hoài', N'Nhà văn nổi tiếng với tác phẩm Dế Mèn Phiêu Lưu Ký', '1920-09-27', N'Việt Nam', NOW(), NOW()),
(N'Nguyễn Ngọc Tư', N'Nhà văn chuyên viết về đề tài đồng bằng sông Cửu Long', '1976-02-15', N'Việt Nam', NOW(), NOW()),
(N'Nguyễn Phong Việt', N'Nhà thơ trẻ với nhiều tác phẩm về tình yêu', '1980-08-20', N'Việt Nam', NOW(), NOW()),
(N'Rosie Nguyễn', N'Tác giả của nhiều sách self-help nổi tiếng', '1987-12-03', N'Việt Nam', NOW(), NOW()),
(N'Nguyễn Quang Thạch', N'Chuyên viết về đề tài lịch sử và văn hóa', '1965-04-18', N'Việt Nam', NOW(), NOW()),
(N'Phan Việt Bắc', N'Tác giả sách kinh tế và quản trị', '1978-09-30', N'Việt Nam', NOW(), NOW()),
(N'Trần Mai Hạnh', N'Chuyên viết truyện ngắn và tản văn', '1982-11-25', N'Việt Nam', NOW(), NOW()),
(N'Lê Minh Hà', N'Tác giả sách về tâm lý và phát triển cá nhân', '1985-07-12', N'Việt Nam', NOW(), NOW()),
(N'Đoàn Thạch Biền', N'Nhà văn chuyên viết về đề tài dân gian', '1960-03-08', N'Việt Nam', NOW(), NOW()),
(N'Nguyễn Đình Thi', N'Nhà thơ và nhà văn nổi tiếng', '1924-12-20', N'Việt Nam', NOW(), NOW()),
(N'Vũ Trọng Phụng', N'Nhà văn hiện thực phê phán', '1912-10-20', N'Việt Nam', NOW(), NOW()),
(N'Nam Cao', N'Nhà văn hiện thực xuất sắc', '1917-10-29', N'Việt Nam', NOW(), NOW()),
(N'Nguyễn Du', N'Đại thi hào dân tộc', '1766-01-03', N'Việt Nam', NOW(), NOW()),
(N'Xuân Diệu', N'Nhà thơ tình yêu nổi tiếng', '1916-02-02', N'Việt Nam', NOW(), NOW());

-- Thêm dữ liệu cho bảng book
INSERT INTO book (publisher_id, title, publication_year, edition, pages, language, description, cover_image, quantity, available_quantity, status) VALUES
(1, N'Mắt biếc', 2019, N'Tái bản lần 5', 300, N'Tiếng Việt', N'Câu chuyện tình của tuổi học trò', 'mat_biec.jpg', 100, 95, 'available'),
(2, N'Dế Mèn phiêu lưu ký', 2020, N'Tái bản lần 20', 250, N'Tiếng Việt', N'Cuộc phiêu lưu của chú Dế Mèn', 'de_men_phieu_luu_ky.jpg', 150, 147, 'available'),
(3, N'Cánh đồng bất tận', 2018, N'Tái bản lần 3', 280, N'Tiếng Việt', N'Tập truyện ngắn về miền Tây Nam Bộ', 'canh_dong_bat_tan.jpg', 80, 75, 'available'),
(4, N'Cho tôi xin một vé đi tuổi thơ', 2021, N'Tái bản lần 2', 200, N'Tiếng Việt', N'Những kỷ niệm tuổi thơ đáng nhớ', 'cho_toi_xin_mot_ve_di_tuoi_tho.jpg', 120, 118, 'available'),
(5, N'Tôi thấy hoa vàng trên cỏ xanh', 2021, N'Tái bản lần 8', 320, N'Tiếng Việt', N'Câu chuyện về tuổi thơ miền quê', 'toi_thay_hoa_vang_tren_co_xanh.jpg', 200, 190, 'available'),
(6, N'Số đỏ', 2020, N'Tái bản', 280, N'Tiếng Việt', N'Tác phẩm văn học hiện thực phê phán', 'so_do.jpg', 150, 145, 'available'),
(7, N'Chí Phèo', 2019, N'Tái bản', 220, N'Tiếng Việt', N'Tác phẩm văn học hiện thực', 'chi_pheo.jpg', 100, 98, 'available'),
(8, N'Truyện Kiều', 2021, N'Tái bản đặc biệt', 350, N'Tiếng Việt', N'Tác phẩm thơ Nôm kinh điển', 'truyen_kieu.jpg', 180, 175, 'available'),
(9, N'Nhật ký trong tù', 2018, N'Tái bản', 180, N'Tiếng Việt', N'Tập thơ về cuộc sống trong tù', 'nhat_ky_trong_tu.jpg', 90, 88, 'available'),
(10, N'Bên kia sông đuống', 2020, N'Tái bản', 200, N'Tiếng Việt', N'Tập thơ về quê hương', 'ben_kia_song_duong.jpg', 120, 115, 'available'),
(11, N'Vang bóng một thời', 2019, N'Tái bản', 280, N'Tiếng Việt', N'Tập truyện ngắn về văn hóa xưa', 'vang_bong_mot_thoi.jpg', 150, 148, 'available'),
(12, N'Tắt đèn', 2021, N'Tái bản', 240, N'Tiếng Việt', N'Tiểu thuyết về số phận người phụ nữ', 'tat_den.jpg', 130, 128, 'available'),
(13, N'Người mẹ cầm súng', 2020, N'Tái bản', 260, N'Tiếng Việt', N'Tiểu thuyết về thời kháng chiến', 'nguoi_me_cam_sung.jpg', 140, 137, 'available'),
(14, N'Tuổi thơ dữ dội', 2019, N'Tái bản', 300, N'Tiếng Việt', N'Hồi ký về thời chiến tranh', 'tuoi_tho_du_doi.jpg', 160, 155, 'available'),
(15, N'Từ ấy', 2021, N'Tái bản', 180, N'Tiếng Việt', N'Tập thơ tình yêu và cách mạng', 'tu_ay.jpg', 100, 98, 'available');

-- Thêm dữ liệu cho bảng category
INSERT INTO category (name, description) VALUES
(N'Văn học Việt Nam', N'Các tác phẩm văn học của tác giả Việt Nam'),
(N'Thiếu nhi', N'Sách dành cho độc giả nhỏ tuổi'),
(N'Tình cảm', N'Các tác phẩm về đề tài tình yêu'),
(N'Kỹ năng sống', N'Sách hướng dẫn kỹ năng và phát triển bản thân'),
(N'Thơ', N'Các tập thơ và thơ tuyển'),
(N'Tiểu thuyết lịch sử', N'Các tác phẩm về đề tài lịch sử'),
(N'Truyện ngắn', N'Tập hợp các truyện ngắn'),
(N'Hồi ký', N'Sách viết về ký ức cá nhân'),
(N'Văn học dịch', N'Các tác phẩm văn học được dịch sang tiếng Việt'),
(N'Kinh tế', N'Sách về kinh tế và tài chính'),
(N'Tâm lý', N'Sách về tâm lý học và phát triển cá nhân'),
(N'Khoa học', N'Sách về các chủ đề khoa học'),
(N'Giáo dục', N'Sách giáo khoa và tài liệu học tập'),
(N'Văn hóa', N'Sách về văn hóa và phong tục'),
(N'Nghệ thuật', N'Sách về các loại hình nghệ thuật');

-- Thêm dữ liệu cho bảng book_author
INSERT INTO book_author (book_id, author_id, role) VALUES
(1, 1, 'Tác giả chính'),
(2, 2, 'Tác giả chính'),
(3, 3, 'Tác giả chính'),
(4, 4, 'Tác giả chính'),
(5, 5, 'Tác giả chính'),
(6, 6, 'Tác giả chính'),
(7, 7, 'Tác giả chính'),
(8, 8, 'Tác giả chính'),
(9, 9, 'Tác giả chính'),
(10, 10, 'Tác giả chính'),
(11, 11, 'Tác giả chính'),
(12, 12, 'Tác giả chính'),
(13, 13, 'Tác giả chính'),
(14, 14, 'Tác giả chính'),
(15, 15, 'Tác giả chính');

-- Thêm dữ liệu cho bảng book_category (15 kết nối sách-danh mục)
INSERT INTO book_category (book_id, category_id) VALUES
(1, 1), (1, 3),
(2, 1), (2, 2),
(3, 1), (3, 7),
(4, 1), (4, 8),
(5, 1), (5, 2),
(6, 1), (6, 7),
(7, 1), (7, 3),
(8, 1), (8, 5),
(9, 1), (9, 8),
(10, 1), (10, 5),
(11, 1), (11, 14),
(12, 1), (12, 6),
(13, 1), (13, 6),
(14, 1), (14, 8),
(15, 1), (15, 5);

-- Thêm dữ liệu cho bảng loan (15 bản ghi mượn sách)
INSERT INTO loan (book_id, issued_by, issued_date, due_date, status, notes) VALUES
(1, 4, '2024-01-01', '2024-01-15', 'issued', N'Mượn lần đầu'),
(2, 5, '2024-01-02', '2024-01-16', 'returned', N'Đã trả đúng hạn'),
(3, 6, '2024-01-03', '2024-01-17', 'overdue', N'Quá hạn 5 ngày'),
(4, 7, '2024-01-04', '2024-01-18', 'issued', N'Gia hạn 1 lần'),
(5, 8, '2024-01-05', '2024-01-19', 'returned', N'Trả sớm 2 ngày'),
(6, 9, '2024-01-06', '2024-01-20', 'issued', N'Mượn lại lần 2'),
(7, 10, '2024-01-07', '2024-01-21', 'overdue', N'Quá hạn 3 ngày'),
(8, 11, '2024-01-08', '2024-01-22', 'issued', N'Mượn lần đầu'),
(9, 12, '2024-01-09', '2024-01-23', 'returned', N'Đã trả đúng hạn'),
(10, 13, '2024-01-10', '2024-01-24', 'issued', N'Gia hạn 2 lần'),
(11, 14, '2024-01-11', '2024-01-25', 'overdue', N'Quá hạn 1 ngày'),
(12, 15, '2024-01-12', '2024-01-26', 'issued', N'Mượn lần đầu'),
(13, 4, '2024-01-13', '2024-01-27', 'returned', N'Trả sớm 1 ngày'),
(14, 5, '2024-01-14', '2024-01-28', 'issued', N'Mượn lại lần 3'),
(15, 6, '2024-01-15', '2024-01-29', 'overdue', N'Quá hạn 2 ngày');

-- Insert dữ liệu cho bảng loan_detail
INSERT INTO loan_detail (loan_id, book_id, quantity, status, notes) VALUES
(1, 1, 1, 'issued', N'Sách mới, tình trạng tốt'),
(2, 2, 1, 'returned', N'Đã trả đúng hạn'),
(3, 3, 1, 'lost', N'Người mượn báo mất sách'),
(4, 4, 1, 'issued', N'Sách có vết ghi chú nhẹ'),
(5, 5, 1, 'issued', N'Sách mới hoàn toàn');

-- Insert dữ liệu cho bảng fine
INSERT INTO fine (loan_id, user_id, issued_date, due_date, status) VALUES
(1, 1, '2024-01-01', '2024-01-15', 'unpaid'),
(2, 2, '2024-01-05', '2024-01-19', 'paid'),
(3, 3, '2024-01-10', '2024-01-24', 'unpaid'),
(4, 3, '2024-01-15', '2024-01-29', 'paid'),
(5, 3, '2024-01-20', '2024-02-03', 'unpaid');

-- Insert dữ liệu cho bảng fine_payment
INSERT INTO fine_payment (fine_id, amount, payment_date, payment_method, receive_by) VALUES
(1, 50000, '2024-01-16', N'Tiền mặt', 1),
(2, 30000, '2024-01-20', N'Chuyển khoản', 2),
(3, 100000, '2024-01-25', N'Tiền mặt', 3),
(4, 25000, '2024-01-30', N'Chuyển khoản', 3),
(5, 75000, '2024-02-04', N'Tiền mặt', 3);

-- Insert dữ liệu cho bảng book_condition
INSERT INTO book_condition (book_id, loan_id, condition_before, condition_after, assessed_by, assessed_date) VALUES
(1, 1, N'Sách mới hoàn toàn', N'Có vài vết gấp nhẹ ở góc', 1, '2024-01-15'),
(2, 2, N'Tình trạng tốt', N'Không thay đổi', 2, '2024-01-19'),
(3, 3, N'Bìa có vết xước nhẹ', N'Mất sách', 3, '2024-01-24'),
(4, 4, N'Sách đã qua sử dụng', N'Thêm vài trang bị ghi chú', 3, '2024-01-29'),
(5, 5, N'Sách mới', N'Còn nguyên vẹn', 3, '2024-02-03');

-- Insert dữ liệu cho bảng reservation
INSERT INTO reservation (user_id, reservation_date, expiry_date, status) VALUES
(1, '2024-01-01', '2024-01-08', 'fulfilled'),
(2, '2024-01-05', '2024-01-12', 'pending'),
(3, '2024-01-10', '2024-01-17', 'cancelled'),
(3, '2024-01-15', '2024-01-22', 'expired'),
(3, '2024-01-20', '2024-01-27', 'pending');

INSERT INTO reservation_detail (reservation_id, book_id, quantity) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 2),
(4, 4, 3),
(5, 5, 1);