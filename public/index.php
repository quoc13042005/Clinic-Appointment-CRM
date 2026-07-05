<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Core/helpers.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/DuplicateRecordException.php';

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
});

$coreFiles = ['Database', 'DuplicateRecordException'];
foreach($coreFiles as $f) require_once __DIR__ . '/../app/Core/' . $f . '.php';
$repoFiles = ['UserRepository', 'PatientRepository', 'AppointmentRepository'];
foreach($repoFiles as $f) require_once __DIR__ . '/../app/Repositories/' . $f . '.php';
$serviceFiles = ['AuthService', 'PatientService', 'AppointmentService'];
foreach($serviceFiles as $f) require_once __DIR__ . '/../app/Services/' . $f . '.php';
$controllerFiles = ['AuthController', 'DashboardController', 'PatientController', 'AppointmentController', 'HealthController'];
foreach($controllerFiles as $f) {
    if(file_exists(__DIR__ . '/../app/Controllers/' . $f . '.php')) {
        require_once __DIR__ . '/../app/Controllers/' . $f . '.php';
    }
}

$dbConfig = require __DIR__ . '/../config/database.php';
$db = Database::connect($dbConfig);

$container = [];
$container['UserRepository'] = new UserRepository($db);
$container['PatientRepository'] = new PatientRepository($db);
$container['AppointmentRepository'] = new AppointmentRepository($db);

$container['AuthService'] = new AuthService($container['UserRepository']);
$container['PatientService'] = new PatientService($container['PatientRepository']);
$container['AppointmentService'] = new AppointmentService($container['AppointmentRepository']);

$container['HomeController'] = new class {
    public function index() {
        if (!empty($_SESSION['user_id'])) redirect('/dashboard');
        redirect('/login');
    }
};
$container['AuthController'] = new AuthController($container['AuthService']);
$container['DashboardController'] = new DashboardController();
$container['PatientController'] = new PatientController($container['PatientService']);
$container['AppointmentController'] = new AppointmentController($container['AppointmentService']);
$container['HealthController'] = new class($db) {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function index() {
        header('Content-Type: application/json');
        try {
            $this->db->query("SELECT 1");
            echo json_encode(['status' => 'ok', 'database' => 'connected']);
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'database' => 'disconnected']);
        }
    }
};

$routes = [
    'GET' => [
        '/' => ['HomeController', 'index'],
        '/login' => ['AuthController', 'login'],
        '/dashboard' => ['DashboardController', 'index'],
        '/patients' => ['PatientController', 'index'],
        '/patients/create' => ['PatientController', 'create'],
        '/patients/edit' => ['PatientController', 'edit'],
        '/appointments' => ['AppointmentController', 'index'],
        '/appointments/create' => ['AppointmentController', 'create'],
        '/appointments/edit' => ['AppointmentController', 'edit'],
        '/health' => ['HealthController', 'index'],
    ],
    'POST' => [
        '/login' => ['AuthController', 'handleLogin'],
        '/logout' => ['AuthController', 'logout'],
        '/patients/store' => ['PatientController', 'store'],
        '/patients/update' => ['PatientController', 'update'],
        '/patients/delete' => ['PatientController', 'delete'],
        '/appointments/store' => ['AppointmentController', 'store'],
        '/appointments/update' => ['AppointmentController', 'update'],
        '/appointments/delete' => ['AppointmentController', 'delete'],
    ],
];

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if path exists in any method to determine 405 vs 404
$pathExistsInAnyMethod = false;
foreach ($routes as $m => $paths) {
    if (isset($paths[$path])) {
        $pathExistsInAnyMethod = true;
        break;
    }
}

if ($pathExistsInAnyMethod && !isset($routes[$method][$path])) {
    http_response_code(405);
    render('errors/405', ['title' => '405 Method Not Allowed']);
    exit;
}

if (!isset($routes[$method][$path])) {
    http_response_code(404);
    render('errors/404', ['title' => '404 Not Found']);
    exit;
}

try {
    [$class, $action] = $routes[$method][$path];
    $container[$class]->$action();
} catch (Exception $e) {
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    $show_error = defined('APP_DEBUG') && APP_DEBUG;
    render('errors/500', ['title' => '500 Internal Server Error', 'error' => $show_error ? $e->getMessage() : null]);
}