<?php
require_once __DIR__ . '/functions.php';

function find_user(string $email): ?array {
    foreach (load_json('users.json') as $user) {
        if (($user['email'] ?? '') === $email) {
            return $user;
        }
    }
    return null;
}

function login_user(string $email, string $password): bool {
    $user = find_user($email);
    if ($user && password_verify($password, $user['password'] ?? '')) {
        $_SESSION['user'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        save_account_session($user);
        return true;
    }
    return false;
}

function register_user(string $name, string $email, string $password): bool {
    $users = load_json('users.json');
    foreach ($users as $user) {
        if (($user['email'] ?? '') === $email) {
            return false;
        }
    }
    $users[] = [
        'id' => uniqid('usr_', true),
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('c')
    ];
    return save_json('users.json', $users);
}
?>
