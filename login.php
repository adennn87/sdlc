<?php
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role_input = trim($_POST['role']);

    if (empty($email) || empty($password) || empty($role_input)) {
        $error = "Please enter full Email, Password and Role.";
    } else {
        $query = "SELECT UserID, FullName, PasswordHash, Role FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $fullname, $hashed_password, $role_db);
                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $hashed_password)) {
                    if ($role_input === $role_db) {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['fullname'] = $fullname;
                        $_SESSION['role'] = $role_db;

                        if ($role_input === 'student') {
                            header("Location: main.php");
                        } else if ($role_input === 'teacher') {
                            header("Location: main1.php");
                        }
                        exit();
                    } else {
                        $error = "Role does not match the registered account.";
                    }
                } else {
                    $error = "Wrong password.";
                }
            } else {
                $error = "Email does not exist.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Query error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <div class="card p-4 shadow">
        <h3 class="text-center mb-3 text-warning">Login</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select name="role" id="roleSelect" class="form-select" required>
                    <option value="">-- Choose Role --</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning">Login</button>
            </div>
        </form>

        <p class="mt-3 text-center">No account yet? <a href="register.php">Sign Up</a></p>
    </div>
</div>

</body>
</html>
