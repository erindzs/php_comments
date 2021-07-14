<?php
class Table
{
    private $table_name;
    private $table = [];
    private $connection;

    public function __construct($connection, $table_name) {
        $this->table_name = $table_name;
        $this->connection = $connection;
    }

    public function &getTable() {
        $sql = "SELECT * FROM " . $this->table_name;
        $result = $this->connection->query($sql);

        /*if ($result->num_rows > 0) {
            //$this->table = $result->fetch_all(MYSQLI_ASSOC);
        }*/

        while ($row = $result->fetch_assoc()) {
            $this->table[$row['id']] = $row;
        }

        return $this->table;
    }

    public function getEntry(int $id) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id=$id";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    public function addEntry($entry) {
        $name = $this->connection->real_escape_string(@$entry['name']);
        $message = $this->connection->real_escape_string(@$entry['message']);

        $sql = "INSERT INTO " . $this->table_name . " (name, message) VALUES ('$name', '$message')";

        if ($this->connection->query($sql) === TRUE) {
            echo "New record created successfully";

            $this->table[$this->connection->insert_id] = [
                'id' => $this->connection->insert_id,
                'name' => $name,
                'message' => $message
            ];

        } else {
            echo "Error: " . $sql . "<br>" . $this->connection->error;
        }
    }

    public function updateEntry(int $id, $entry) {
        $allowed_keys = ['name' => null, 'message' => null];
        $entry = array_intersect_key($entry, $allowed_keys);
        $name = $this->connection->real_escape_string(@$entry['name']);
        $message = $this->connection->real_escape_string(@$entry['message']);

        $sql = "UPDATE " . $this->table_name . " SET name='$name', message='$message' WHERE id=$id";

        if ($this->connection->query($sql) === TRUE) {
            echo "Record ID:$id updated successfully";

            $this->table[$id]['name'] = $name;
            $this->table[$id]['message'] = $message;
        } else {
            echo "Error: " . $sql . "<br>" . $this->connection->error;
        }
    }

    public function remove(int $id) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id=$id";

        if ($this->connection->query($sql) === TRUE) {
            echo "Record ID:$id removed successfully";

            if (isset($this->table[$id])) {
                unset($this->table[$id]);
            }
        } else {
            echo "Error: " . $sql . "<br>" . $this->connection->error;
        }
    }
}