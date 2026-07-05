<?php
class AuthController {
    public function __construct(private AuthService $authService) {}
    public function login(): void {
        if (!empty($_SESSION['user_id'])) redirect('/dashboard');
        render('auth/login', ['title' => 'Login', 'errors' => []], 'layouts/main');
    }
    public function handleLogin(): void {
        // T09: Honeypot & Rate limit
        if (!empty($_POST['website_hp'])) { http_response_code(403); exit('Spam detected.'); }
        if (isset($_SESSION['last_login_attempt']) && time() - $_SESSION['last_login_attempt'] < 5) {
            flash('error', 'Vui lòng thử lại sau 5 giây.'); redirect('/login');
        }
        $_SESSION['last_login_attempt'] = time();

        // T07: Validate input (Secure)
        $email = trim($_POST['email'] ?? ''); 
        $password = $_POST['password'] ?? '';
        
        $user = $this->authService->attemptLogin($email, $password);
        
        // T08: PRG form công khai (Redirect on failure instead of render)
        if (!$user) { 
            flash('error', 'Sai thông tin đăng nhập.'); 
            $_SESSION['old'] = ['email' => $email]; 
            redirect('/login'); 
        }
        
        session_regenerate_id(true); 
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['user_name'] = $user['name']; 
        $_SESSION['last_activity'] = time();
        flash('success', 'Đăng nhập thành công.'); 
        redirect('/dashboard');
    }
    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) { $p = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]); }
        session_destroy(); redirect('/login');
    }
}