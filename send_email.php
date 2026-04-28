<?php
// التأكد إن الطلب جاي من الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. استقبال البيانات وتأمينها (Sanitization)
    $fullName = htmlspecialchars($_POST['fname'] ?? 'Unknown Client');
    $jobTitle = htmlspecialchars($_POST['jobTitle'] ?? '');
    $companyName = htmlspecialchars($_POST['companyName'] ?? '');
    $mobileNo = htmlspecialchars($_POST['mobileNo'] ?? '');
    $emailAddress = htmlspecialchars($_POST['femail'] ?? '');
    $area = htmlspecialchars($_POST['area'] ?? '');
    $city = htmlspecialchars($_POST['city'] ?? '');
    $meeting = htmlspecialchars($_POST['meeting'] ?? '');
    $message = htmlspecialchars($_POST['fmsg'] ?? 'No specific message.');

    // تحديد نوع الميتينج
    $meetingText = ($meeting === 'office') ? "Meeting in Client's Office" : "Meeting in CATE Office";

    // 2. إعدادات الإيميل
    $to = "khaled.magdy@cate-eg.com"; // الإيميل اللي هيستقبل الرسايل
    $subject = "New Contact Request from $fullName - CATE Website";

    // 3. تجهيز محتوى الرسالة
    $body = "Hello Team,\n\n";
    $body .= "You have received a new contact request from the CATE website. Here are the details:\n\n";
    
    $body .= "👤 Client Information:\n";
    $body .= "- Name: $fullName\n";
    $body .= "- Job Title: $jobTitle\n";
    $body .= "- Company: $companyName\n\n";
    
    $body .= "📞 Contact Details:\n";
    $body .= "- Mobile: $mobileNo\n";
    $body .= "- Email: $emailAddress\n\n";
    
    $body .= "📍 Location & Preferences:\n";
    $body .= "- Location: $area, $city\n";
    $body .= "- Request Type: $meetingText\n\n";
    
    $body .= "📝 Message:\n\"$message\"\n\n";
    $body .= "---\nThis is an automated message from the CATE-EG website contact form.";

    // 4. إعدادات الـ Headers عشان الرسالة تتبعت بشكل سليم ومتروحش Spam
    $headers = "From: noreply@cate-eg.com\r\n"; // يُفضل يكون إيميل رسمي من الدومين بتاعك
    $headers .= "Reply-To: $emailAddress\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // 5. إرسال الإيميل والرد على الجافاسكريبت
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "success", "message" => "Email sent successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send email."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>