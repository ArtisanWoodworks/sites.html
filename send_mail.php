<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // إعدادات البريد الإلكتروني
    $to = "hassanaitari4@gmail.com"; // عوّض هذا بإيميلك
    $subject = "Nouveau message du formulaire de contact";

    // استلام البيانات من النموذج
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);
    $password = htmlspecialchars($_POST["password"]); // تأكد من وجود حقل كلمة المرور في النموذج

    // التحقق من reCAPTCHA
    $recaptcha_secret = "YOUR_SECRET_KEY"; // ضع مفتاحك السري هنا
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $response_data = json_decode($verify);

    if (!$response_data->success) {
        echo "<script>alert('Erreur: reCAPTCHA non validé!'); window.history.back();</script>";
        exit;
    }

    // إعداد الرسالة
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Nom: $name\n";
    $body .= "E-mail: $email\n\n";
    $body .= "Message:\n$message\n";

    // إرسال البريد الإلكتروني
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message envoyé avec succès!');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'envoi du message.'); window.history.back();</script>";
        exit;
    }

    // إدخال البيانات في قاعدة البيانات
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mon_site_db', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO utilisateurs (name, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $email, $hashed_password]);

        echo "<script>alert('Utilisateur ajouté à la base de données avec succès!'); window.location.href='index.html';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de l\'ajout dans la base de données: " . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    header("Location: index.html");
    exit();
}
?>
