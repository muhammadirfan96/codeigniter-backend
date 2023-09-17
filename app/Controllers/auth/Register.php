<?php

namespace App\Controllers\auth;

use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Register extends ResourceController
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
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $data = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'refresh_token' => "",
            'created_by' => $this->request->getVar('created_by'),
            'updated_by' => $this->request->getVar('updated_by'),
        ];

        $model->save($data);
        return $this->respondCreated('registered successfully');
    }
}
