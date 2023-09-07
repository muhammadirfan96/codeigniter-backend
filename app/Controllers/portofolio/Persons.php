<?php

namespace App\Controllers\portofolio;

use App\Models\PersonsModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Persons extends ResourceController
{
    use ResponseTrait;
    protected $db, $model;
    public function __construct()
    {
        $this->db = Database::connect();
        $this->model = new PersonsModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = $this->model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) return $this->failNotFound('no data found');
        return $this->respond($data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper(['form']);

        $rules = $this->model->myValidationRules;
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $photo = $this->request->getFile('photo');
        $namaFile = $photo->getRandomName();
        $photo->move('img/portofolio', $namaFile);

        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'birthday' => $this->request->getVar('birthday'),
            'photo' => base_url('img/portofolio/' . $namaFile),
        ];

        $this->model->save($data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'data inserted'
            ]
        ];
        return $this->respondCreated($response);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $findData = $this->model->find($id);
        if (!$findData) return $this->failNotFound('no data found');

        helper(['form']);

        $photo = $this->request->getFile('photo');
        $rules = $this->model->myValidationRules;
        if ($photo == '') {
            unset($rules['photo']);
        }
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'birthday' => $this->request->getVar('birthday'),
        ];

        if ($photo != '') {
            $namaFile = $photo->getRandomName();
            $data['photo'] = base_url('img/portofolio/' . $namaFile);
            if (file_exists("img/portofolio/" . explode("portofolio/", $findData['photo'])[1])) {
                unlink("img/portofolio/" . explode("portofolio/", $findData['photo'])[1]);
            }
            $photo->move('img/portofolio', $namaFile);
        }

        $this->model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'data updated'
            ]
        ];
        return $this->respondUpdated($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $findData = $this->model->find($id);
        if (!$findData) return $this->failNotFound('no data found');

        if (file_exists("img/portofolio/" . explode("portofolio/", $findData['photo'])[1])) {
            unlink("img/portofolio/" . explode("portofolio/", $findData['photo'])[1]);
        }

        $this->model->delete($id);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'data deleted'
            ]
        ];

        return $this->respondDeleted($response);
    }

    public function find($key, $limit = 0, $offset = 0)
    {
        if (str_contains($key, '@')) {
            $keys = explode("@", $key);
            if (str_contains($key, 'name')) $where = "name LIKE '%$keys[1]%'";
            if (str_contains($key, 'email')) $where = "email LIKE '%$keys[1]%'";
        } else {
            $where = null;
        }

        $persons = $this->db->table('persons')
            ->orderBy('persons.id', 'DESC')
            ->getWhere($where, $limit, $offset)
            ->getResultArray();

        return $this->respond($persons);
    }
}
