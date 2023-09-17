<?php

namespace App\Controllers\auth;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RefreshToken extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        try {
            $model = new UsersModel();
            $refresh_token =  $this->request->getCookie('refresh_token');
            if (!$refresh_token) return $this->fail('cookie missed');

            $user = $model->where('refresh_token', $refresh_token)->first();
            if (!$user) return $this->fail('invalid token');

            $jwtConfig = config('JwtConfig');
            $decoded_refresh = JWT::decode($user['refresh_token'], new Key($jwtConfig->refresh_token, 'HS256'));
            if (!$decoded_refresh) return $this->fail('invalid token');

            $payload = [
                'iat' => 1356999524,
                'nbf' => 1357000000,
                "exp" => time() + 30,
                "uid" => $user['id'],
                "email" => $user['email']
            ];

            $access_token = JWT::encode($payload, $jwtConfig->access_token, 'HS256');

            return $this->respond($access_token);
        } catch (\Throwable $th) {
            return $this->failUnauthorized();
        }
    }
}
