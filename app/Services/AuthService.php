<?php
class AuthService {
    public function __construct(private UserRepository $userRepository) {}
    public function attemptLogin(string $email, string $password): bool|array {
        $user = $this->userRepository->findActiveByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) return false;
        return $user;
    }
}