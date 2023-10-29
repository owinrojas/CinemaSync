<?php

class Email
{

    public string $recipient;
    public string $sender;

    function __construct($sender, $recipient)
    {
        try {
            if (!filter_var($recipient, FILTER_VALIDATE_EMAIL) && !filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email address");
            }
            $this->recipient = $recipient;
            $this->sender = $sender;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function reminderEmail($movie, $date): bool
    {
        $subject = "Reminder for \"" . $movie . "\""; // email subject
        $message = "You have a movie coming up on " . $date . " called \"" . $movie . "\". Don't forget to book your ticket!"; // email message

        return  $this->sendMail($subject, $message);
    }

    /**
     * The sendMail function sends an email with a specified subject and message.
     * 
     * @param subject The subject parameter is a string that represents the subject of the email. It is
     * typically a short description of the content of the email.
     * @param message The  parameter is the content of the email that you want to send. It can
     * be a plain text message or HTML content.
     */
    function sendMail($subject, $message): bool
    {
        $headers = "From: " . $this->sender;
        return mail($this->sender, $subject, $message, $headers);
    }
}
