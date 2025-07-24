<?php include "connect.php"; ?>

<?php
// C - Thêm người dùng
if (isset($_POST['create'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // bảo mật

    $conn->query("INSERT INTO users (FullName, Email, PhoneNumber, Address, PasswordHash) 
                  VALUES ('$fullname', '$email', '$phone', '$address', '$hashed_password')");
    header("Location: manage_user.php");
    exit;
}

// U - Sửa thông tin người dùng
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn->query("UPDATE users 
                  SET FullName='$fullname', Email='$email', PhoneNumber='$phone', Address='$address', PasswordHash='$hashed_password' 
                  WHERE UserID = $id");
    header("Location: manage_user.php");
    exit;
}

// D - Xóa người dùng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE UserID = $id");
    header("Location: manage_user.php");
    exit;
}

// Lấy dữ liệu để sửa
$edit_mode = false;
$edit_user = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM users WHERE UserID = $id");
    $edit_user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="manage.css">
</head>
<body>
<div class="container">
    <h2>Quản Lý Người Dùng</h2>

    <!-- Form -->
    <form method="POST" class="form">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_user['UserID'] ?>">
        <?php endif; ?>

        <input type="text" name="fullname" placeholder="Họ tên" required value="<?= $edit_mode ? $edit_user['FullName'] : '' ?>">
        <input type="email" name="email" placeholder="Email" required value="<?= $edit_mode ? $edit_user['Email'] : '' ?>">
        <input type="text" name="phone" placeholder="Số điện thoại" value="<?= $edit_mode ? $edit_user['PhoneNumber'] : '' ?>">
        <input type="text" name="address" placeholder="Địa chỉ" value="<?= $edit_mode ? $edit_user['Address'] : '' ?>">
        <input type="password" name="password" placeholder="Mật khẩu" required>

        <?php if ($edit_mode): ?>
            <button type="submit" name="update" class="btn update">Cập nhật</button>
            <a href="manage_user.php" class="btn cancel">Hủy</a>
        <?php else: ?>
            <button type="submit" name="create" class="btn save">Lưu</button>
        <?php endif; ?>
    </form>

    <!-- Danh sách -->
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Địa chỉ</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $result = $conn->query("SELECT * FROM users ORDER BY UserID DESC");
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['UserID'] ?></td>
                <td><?= $row['FullName'] ?></td>
                <td><?= $row['Email'] ?></td>
                <td><?= $row['PhoneNumber'] ?></td>
                <td><?= $row['Address'] ?></td>
                <td>
                    <a href="?edit=<?= $row['UserID'] ?>" class="btn edit">Sửa</a>
                    <a href="?delete=<?= $row['UserID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn delete">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="indexteacher.php" class="btn back">← Quay lại</a>
</div>
</body>
</html>
