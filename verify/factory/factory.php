<?php
class Factory {
    public static function create($type, $params = []) {
        switch ($type) {
            case 'User':
                return new User($params['name'], $params['email'], $params['role']);
            
            default:
                throw new Exception("Invalid type specified for Factory.");
        }
    }
}
?>
