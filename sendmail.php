<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
$mail = new PHPMailer(true);
try{
    //服务器配置
    $mail->CharSet ="UTF-8";                     //设定邮件编码
    $mail->SMTPDebug = 0;                        // 调试模式输出
    $mail->isSMTP();                             // 使用SMTP
    $mail->Host = 'smtp.qq.com';                // SMTP服务器
    $mail->SMTPAuth = true;                      // 允许 SMTP 认证
    $mail->Username = '1835660809@qq.com';                // SMTP 用户名  即邮箱的用户名
    $mail->Password = 'pqnqcjxsvqmhbbig';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
    $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
    $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

    $mail->setFrom('1835660809@qq.com', 'wyqgg');  //发件人
    $mail->addAddress('2717719404@qq.com', 'wyq');  // 收件人
    $mail->addReplyTo('1835660809@qq.163.com', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
    //Content
    $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    $mail->Subject = '登录提示' . time();
    $mail->Body    = '<h1>登录成功</h1>' . date('Y-m-d H:i:s');
    $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';

    $mail->send();
    echo '邮件发送成功';

}catch (Exception $e){
    echo '邮件发送失败!';
}