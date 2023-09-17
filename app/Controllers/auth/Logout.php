<?php

namespace App\Controllers\auth;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class Logout extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $model = new UsersModel();
        $refresh_token =  $this->request->getCookie('refresh_token');
        if (!$refresh_token) return $this->fail('cookie missed');

        $user = $model->where('refresh_token', $refresh_token)->first();
        if (!$user) return $this->fail('cookie not match');

        $model->update($user['id'], ['refresh_token' => '']);

        $this->response->setCookie(
            'refresh_token',
            '',
            $expire = time() - 3600,
            $domain = '',
            $path = '/',
            $prefix = '',
            $secure = false,
            $httponly = true,
            $samesite = null
        );

        $this->response->setStatusCode(200);
    }
}
