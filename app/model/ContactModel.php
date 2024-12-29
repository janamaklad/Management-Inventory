<?php
// ContactModel.php
require_once(_ROOT_ . "/app/model/Model.php");
require_once(_ROOT_ . "/app/model/contact.php");

class ContactModel extends Model
{
    private $contacts;

    // Constructor to initialize contacts array
    function __construct()
    {
        $this->contacts = array();
        $this->fillArray(); // Fill the contacts array with data
    }

    // Function to fill the contacts array
    function fillArray()
    {
        $this->db = $this->connect(); // Database connection
        $result = $this->readContacts(); // Fetch contacts
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push(
                    $this->contacts,
                    new Contact(
                        $row["contact_id"],
                        $row["name"],
                        $row["email"],
                        $row["subject"],
                        $row["message"]
                    )
                );
            }
        }
    }

    // Function to return the contacts array
    function getContacts()
    {
        return $this->contacts;
    }

    // Function to read all contacts from the database
    function readContacts()
    {
        $sql = "SELECT * FROM contact_messages"; // Correct table name (contact_messages)
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Function to add a new contact to the database
    function addContact($name, $email, $subject, $message)
    {
        // Prepare the SQL query
        $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        if ($this->db->query($sql) === true) {
            return true;
        } else {
            throw new Exception("Error inserting contact message: " . $this->db->error);
        }
    }
}
?>