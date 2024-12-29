<?php
// Contact.php
//require_once(_ROOT_ . "model/model.php");


class Contact extends Model
{
    private $contact_id;
    private $name;
    private $email;
    private $subject;
    private $message;

    function __construct($id, $name = "", $email = "", $subject = "", $message = "")
    {
        $this->contact_id = $id;
        $this->db = $this->connect();

        if ("" === $name) {
            $this->readContact($id);
        } else {
            $this->name = $name;
            $this->email = $email;
            $this->subject = $subject;
            $this->message = $message;
        }
    }

    function getName()
    {
        return $this->name;
    }
    function setName($name)
    {
        return $this->name = $name;
    }

    function getEmail()
    {
        return $this->email;
    }
    function setEmail($email)
    {
        return $this->email = $email;
    }

    function getSubject()
    {
        return $this->subject;
    }
    function setSubject($subject)
    {
        return $this->subject = $subject;
    }

    function getMessage()
    {
        return $this->message;
    }
    function setMessage($message)
    {
        return $this->message = $message;
    }

    function getID()
    {
        return $this->contact_id;
    }

    function readContact($id)
    {
        $sql = "SELECT * FROM contact_messages  WHERE contact_id=" . $id;
        $db = $this->connect();
        $result = $db->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->name = $row["name"];
            $this->email = $row["email"];
            $this->subject = $row["subject"];
            $this->message = $row["message"];
        } else {
            $this->name = "";
            $this->email = "";
            $this->subject = "";
            $this->message = "";
        }
    }

}

?>

