<?php

namespace App\Controllers\auth;

use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Login extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $model = new UsersModel();
        helper(['form']);
        $rules = $model->myValidationRules;
        unset($rules['conf_password']);
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $user = $model->where('email', $this->request->getVar('email'))->first();
        if (!$user) return $this->fail('email not found');

        $verify = password_verify($this->request->getVar('password'), $user['password']);
        if (!$verify) return $this->fail('wrong password');

        $jwtConfig = config('JwtConfig');
        $payload = [
            'iat' => 1356999524,
            'nbf' => 1357000000,
            "exp" => time() + 30,
            'uid' => $user['id'],
            'email' => $user['email']
        ];

        $access_token = JWT::encode($payload, $jwtConfig->access_token, 'HS256');
        $payload['exp'] = time() + (3600 * 24);
        $refresh_token = JWT::encode($payload, $jwtConfig->refresh_token, 'HS256');

        $model->update($user['id'], ['refresh_token' => $refresh_token]);

        $this->response->setCookie(
            'refresh_token',
            $refresh_token,
            $expire = time() + (3600 * 24),
            $domain = '',
            $path = '/',
            $prefix = '',
            $secure = false,
            $httponly = true,
            $samesite = null
        );

        return $this->respond($access_token);
    }
}
