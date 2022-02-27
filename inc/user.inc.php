<?php
class User {
    public function __construct($user) {
        $this->data = $user;
    }

    public function getName() {
        return $this->data['name'];
    }
    
    public function getID() {
        return $this->data['id'];
    }

    public function getUsername() {
        return $this->data['username'];
    }

    public function getUser($user, $type = null) {
        global $db;

        if ($type == "username") {
            $getUser = $db->prepare("SELECT * FROM users WHERE username = ?");
            $getUser->bind_param("s", $user);
        } else if ($type == "id") {
            $getUser = $db->prepare("SELECT * FROM users WHERE id = ?");
            $getUser->bind_param("s", $user);
        } else {
            $getUser = $db->prepare("SELECT * FROM users WHERE id = ? OR username = ?");
            $getUser->bind_param("ss", $user, $user);
        }
        $getUser->execute();
        $getUser = $getUser->get_result();

        if ($getUser->num_rows) {
            $getUser = $getUser->fetch_assoc();
        
            return new User($getUser);
        } else {
            return null;
        }
    }

    public function getAvatar($user = null) {
        global $dir, $utils;
        if (!$user) {
            $user = $this->getID();
        }
        if (file_exists("{$dir}assets/profiles/{$user}.png")) {
            return "/assets/profiles/{$user}.png?".filemtime("{$dir}assets/profiles/{$user}.png");
        } else {
            return "https://avatars.dicebear.com/api/{$utils->getSetting("default_avatar")}/{$this->getName($user)}{$user}.svg";
        }
    }


    public function removeUser($id) {
        global $db;
        $delete = $db->prepare("DELETE FROM users WHERE id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM users_groups WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM users_notifications WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM users_milestones WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM users_points WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM shop_orders WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM shop_cart WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();

        $delete = $db->prepare("DELETE FROM sessions WHERE user_id = ?");
        $delete->bind_param("s", $id);
        $delete->execute();
    }
}