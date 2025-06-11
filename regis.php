<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];

    $role = 'user'; 

    $checkEmailQuery = "SELECT * FROM tbl_userss WHERE email=? OR phone=?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registrasi Gagal</title>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background-color: #f8f9fa;
                    font-family: Arial, sans-serif;
                }
                .container {
                    text-align: center;
                    padding: 30px;
                    border: 1px solid #dc3545;
                    border-radius: 10px;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .container h1 {
                    color: #dc3545;
                }
                .btn {
                    margin-top: 20px;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                }
                .btn:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Registrasi Gagal</h1>
                <p>Email atau Nomor Telepon sudah terdaftar.</p>
                <a href="register.php" class="btn">Kembali ke Halaman Registrasi</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertQuery = "INSERT INTO tbl_userss (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $role);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit(); 
        } else {
            echo "Registrasi Gagal: " . $stmt->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>
