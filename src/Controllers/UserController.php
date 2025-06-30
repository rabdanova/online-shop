<?php
namespace Controllers;
use Model\User;
use Request\ChangeProfileRequest;
use Request\LoginRequest;
use Request\RegistrateRequest;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    public function getRegistrate()
    {
        require_once '../Views/registration.php';
    }

    public function getLogin()
    {
        require_once '../Views/login.php';
    }

    public function getEditForm()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
        }

        $user = $this->authService->getCurrentUser();

        $this->userModel->getById($user->getId());
        require_once '../Views/edit-profile.php';
    }

    public function registrate(RegistrateRequest $request)
    {
        $errors = $request->IsValidData();

        if (empty($errors)) {

            $password = password_hash($request->getPassword(), PASSWORD_DEFAULT);

            $this->userModel->insertData($request->getName(),$request->getEmail(),$password);

            header("location: /login");
        } else {
            print_r($errors);
        }
    }

    public function login(LoginRequest $request)
    {
        $errors = $request->validateLogin();

        if (empty($errors)) {
            $result = $this->authService->auth($request->getEmail(),$request->getPassword());

            if ($result === true) {
                header('Location: /catalog');
                exit;
            } else {
                echo "Username or password is incorrect";
            }
        }
        $this->getLogin();
    }

    public function profile()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $user = $this->authService->getCurrentUser();

        require_once '../Views/profile.php';
    }

    public function editProfile(ChangeProfileRequest $request)
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $errors = $request->validateEditProfile();

        if (empty($errors)) {

            $user = $this->authService->getCurrentUser();

            if ($request->getName() !== '') {
                $this->userModel->updateNameById($request->getName(), $user->getId());
            }

            if ($request->getEmail() !== '') {
                $this->userModel->updateEmailById($request->getEmail(), $user->getId());
            }

            if (!empty($request->getNewPassword())) {
                $hashedPassword = password_hash($request->getNewPassword(), PASSWORD_DEFAULT);
                $this->userModel->updatePasswordById($hashedPassword, $user->getId());
            }

            header('Location: /profile');
            exit;

        } else {
            print_r($errors);
        }
    }

    public function logout()
    {
        $this->authService->logout();
        header("Location: /login");
        exit;
    }



}