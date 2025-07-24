<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Sinh viên - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="left">
        <img src="assets/logo1.png" alt="FPT Logo" class="logo">
        <nav>
            <a href="indexstd.php">Home</a>
            <a href="#">My Learning</a>
        </nav>
    </div>
    <div class="user-circle">NQ</div>
</header>

<!-- PHP xử lý đăng ký khóa học -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $student_id = 1; // giả lập người dùng
    $course_id = $_POST['course_id'];

    $check = $conn->query("SELECT * FROM enrollments WHERE student_id = $student_id AND course_id = $course_id");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO enrollments (student_id, course_id) VALUES ($student_id, $course_id)");
        $conn->query("UPDATE courses SET enrolled = enrolled + 1 WHERE id = $course_id");
        echo "<div class='alert success'>Đăng ký thành công!</div>";
    } else {
        echo "<div class='alert danger'>Bạn đã đăng ký khóa học này rồi.</div>";
    }
}
?>

<!-- CHI TIẾT KHÓA HỌC -->
<?php
if (isset($_GET['view']) && $_GET['view'] === 'course' && isset($_GET['id'])):
    $course_id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM courses WHERE id = $course_id");
    if ($result->num_rows):
        $course = $result->fetch_assoc();
?>
<section class="course-detail">
    <div class="course-image">
        <img src="assets/sample-course.png" alt="Ảnh minh họa">
    </div>
    <div class="course-info">
        <h1><?= $course['name'] ?></h1>
        <p><strong>Bộ môn:</strong> <?= $course['department'] ?></p>
        <p><strong>Giảng viên:</strong> <?= $course['teacher'] ?></p>
        <p><strong>Số lượng:</strong> <?= $course['enrolled'] ?>/<?= $course['slots'] ?></p>
        <p><strong>Mô tả:</strong> Khóa học giúp bạn nắm vững kiến thức chuyên ngành <?= $course['department'] ?>.</p>
        <form method="POST">
            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
            <button type="submit" class="btn-primary">Đăng ký khóa học</button>
        </form>
    </div>
</section>
<hr>
<?php
    endif;
endif;
?>

<!-- DANH SÁCH KHÓA HỌC -->
<main class="course-list">
    <h2>Danh sách khóa học</h2>
    <div class="grid">
        <?php
        $result = $conn->query("SELECT * FROM courses");
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="course-card">
            <h3><?= $row['name'] ?></h3>
            <p><strong>Giảng viên:</strong> <?= $row['teacher'] ?></p>
            <p><strong>Bộ môn:</strong> <?= $row['department'] ?></p>
            <p><strong>Sĩ số:</strong> <?= $row['enrolled'] ?>/<?= $row['slots'] ?></p>
            <a href="indexstd.php?view=course&id=<?= $row['id'] ?>" class="btn-outline">Xem chi tiết</a>
        </div>
        <?php endwhile; ?>
    </div>
</main>

</body>
</html>
