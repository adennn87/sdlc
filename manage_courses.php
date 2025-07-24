<?php include "connect.php"; ?>

<?php
// C - CREATE (Thêm mới khóa học)
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $department = $_POST['department'];
    $teacher = $_POST['teacher'];
    $slots = $_POST['slots'];
    $description = $_POST['description'];
    $conn->query("INSERT INTO courses (name, department, teacher, slots, description, enrolled) ) 
                  VALUES ('$name', '$department', '$teacher',  '$description',$slots, 0)");
    header("Location: manage_courses.php");
    exit;
}

// U - UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $teacher = $_POST['teacher'];
    $slots = $_POST['slots'];
    $description = $_POST['description'];
   $conn->query("UPDATE courses 
    SET 
        name='$name', 
        department='$department', 
        teacher='$teacher', 
        slots=$slots, 
        description='$description' 
    WHERE id = $id");
    header("Location: manage_courses.php");
    exit;
}

// D - DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM courses WHERE id = $id");
    header("Location: manage_courses.php");
    exit;
}

// Lấy dữ liệu để sửa nếu có
$edit_mode = false;
$edit_course = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM courses WHERE id = $id");
    $edit_course = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khóa học</title>
    <link rel="stylesheet" href="manage.css">
</head>
<body>
<div class="container">
    <h2>Quản Lý Khóa Học</h2>

    <!-- FORM -->
    <form method="POST" class="form">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_course['id'] ?>">
        <?php endif; ?>

        <input type="text" name="name" placeholder="Tên khóa học" required value="<?= $edit_mode ? $edit_course['name'] : '' ?>">
        <input type="text" name="department" placeholder="Bộ môn" required value="<?= $edit_mode ? $edit_course['department'] : '' ?>">
        <input type="text" name="teacher" placeholder="Giảng viên" required value="<?= $edit_mode ? $edit_course['teacher'] : '' ?>">
        <input type="text" name="description" placeholder="Mô tả khóa học" value="<?= $edit_mode ? $edit_course['description'] : '' ?>">
        <input type="number" name="slots" placeholder="Sĩ số" required value="<?= $edit_mode ? $edit_course['slots'] : '' ?>">
        

        <?php if ($edit_mode): ?>
            <button type="submit" name="update" class="btn update">Cập nhật</button>
            <a href="manage_courses.php" class="btn cancel">Hủy</a>
        <?php else: ?>
            <button type="submit" name="create" class="btn save">Lưu</button>
        <?php endif; ?>
    </form>

    <!-- BẢNG KHÓA HỌC (READ) -->
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên khóa học</th>
            <th>Bộ môn</th>
            <th>Giảng viên</th>
            <th>Sĩ số</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $result = $conn->query("SELECT * FROM courses ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['department'] ?></td>
                <td><?= $row['teacher'] ?></td>
                <td><?= $row['enrolled'] ?>/<?= $row['slots'] ?></td>
                <td><?= $row['description'] ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn edit">Sửa</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn delete">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="indexteacher.php" class="btn back">← Quay lại</a>
</div>
</body>
</html>
