<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $role     = $_POST['role'];

    if (empty($fullname) || empty($email) || empty($_POST['password']) || empty($role)) {
        $error = "Please fill in all required fields.";
    } else {
       
        $check_query = "SELECT * FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($conn, $check_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = "Email already exists. Please choose another email.";
            } else {
                
                $insert_query = "INSERT INTO users (FullName, Email, PasswordHash, PhoneNumber, Address, Role) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $insert_query);

                if ($stmt_insert) {
                    mysqli_stmt_bind_param($stmt_insert, "ssssss", $fullname, $email, $password, $phone, $address, $role);

                    if (mysqli_stmt_execute($stmt_insert)) {
                        header("Location: login.php");
                        exit();
                    } else {
                        $error = "Error adding user: " . mysqli_error($conn);
                    }

                    mysqli_stmt_close($stmt_insert);
                } else {
                    $error = "Error preparing registration query: " . mysqli_error($conn);
                }
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Email check error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>
<body>

    <div class="register-container">
        <h2>Sign up</h2>
        <?php if (!empty($_GET['success'])): ?>
            <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (!empty($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
    <div class="mb-3">
            <label for="fullname" class="form-label">FullName:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" id="address" name="address">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role:</label>
            <select class="form-control" id="role" name="role" required>
                <option value="">-- Choose Role --</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>

        <button type="submit" class="btn btn-register">Register</button>
    </form>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</form>
        </form>

        <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
