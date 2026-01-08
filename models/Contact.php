<?php
class Contact {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function saveMessage($data) {
        $stmt = $this->db->prepare("
            INSERT INTO contacts (name, email, phone, subject, message, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message'],
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        ]);
    }
    
    public function getAllMessages() {
        $stmt = $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function sendEmailNotification($data) {
        $to = ADMIN_EMAIL;
        $subject = "Nouveau message de contact Eyora: " . $data['subject'];
        $message = "
            <html>
            <head>
                <title>Nouveau message de contact</title>
            </head>
            <body>
                <h2>Nouveau message reçu sur Eyora</h2>
                <p><strong>Nom:</strong> {$data['name']}</p>
                <p><strong>Email:</strong> {$data['email']}</p>
                <p><strong>Téléphone:</strong> {$data['phone']}</p>
                <p><strong>Sujet:</strong> {$data['subject']}</p>
                <p><strong>Message:</strong></p>
                <p>{$data['message']}</p>
                <hr>
                <p>IP: {$_SERVER['REMOTE_ADDR']}</p>
                <p>Date: " . date('Y-m-d H:i:s') . "</p>
            </body>
            </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: contact@eyora.com" . "\r\n";
        
        return mail($to, $subject, $message, $headers);
    }
}
?>