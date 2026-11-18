<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\UserRepository;

final class AuthController extends Controller
{
    public function login(): void { if (isset($_SESSION['user'])) redirect('dashboard'); $this->view('auth/login', ['title'=>'Bem-vindo de volta'], true); }
    public function register(): void { if (isset($_SESSION['user'])) redirect('dashboard'); $this->view('auth/register', ['title'=>'Seu próximo passo começa aqui'], true); }

    public function authenticate(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $repo = new UserRepository();
        $user = $repo->byEmail($email);
        // Hash fictício reduz diferenças de tempo entre emails existentes e inexistentes.
        $valid = password_verify($_POST['password'] ?? '', $user['password'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi');
        if (!$user || !$valid) {
            http_response_code(422);
            $this->view('auth/login', ['title'=>'Bem-vindo de volta','errors'=>['E-mail ou senha incorretos.'],'old'=>['email'=>$email]], true);
            return;
        }
        $this->signIn($user);
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $errors = [];
        if (mb_strlen($name) < 3 || mb_strlen($name) > 100) $errors[] = 'Seu nome deve ter entre 3 e 100 caracteres.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email)>190) $errors[] = 'Informe um e-mail válido.';
        if (strlen($password)<8 || strlen($password)>72) $errors[] = 'Use uma senha entre 8 e 72 bytes (até 72 caracteres simples).';
        if ($password !== ($_POST['password_confirmation'] ?? '')) $errors[] = 'A confirmação da senha não confere.';
        $repo = new UserRepository();
        if (!$errors) {
            try { $id = $repo->create($name, $email, $password); }
            catch (\PDOException $error) { if ($error->getCode() !== '23000') throw $error; $errors[] = 'Este e-mail já está cadastrado.'; }
        }
        if ($errors) { http_response_code(422); $this->view('auth/register', ['title'=>'Crie sua conta','errors'=>$errors,'old'=>compact('name','email')], true); return; }
        flash('Conta criada! Organize seu primeiro atendimento.');
        $this->signIn(['id'=>$id,'name'=>$name,'email'=>$email]);
    }

    private function signIn(array $user): never
    {
        session_regenerate_id(true);
        $_SESSION['user'] = array_intersect_key($user, array_flip(['id','name','email']));
        $_SESSION['_token'] = bin2hex(random_bytes(32));
        redirect('dashboard');
    }

    public function logout(): never
    {
        $_SESSION = [];
        session_destroy();
        redirect('login');
    }
}
