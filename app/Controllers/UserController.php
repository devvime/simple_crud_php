<?php

namespace App\Controllers;

use App\Core\ControllerService;
use App\Core\SqlService;

class UserController extends ControllerService {

    private static $usersModel;

    public function __construct()
    {
        self::$usersModel = new SqlService('user');
    }

    public function index() {
        $result = self::$usersModel->select('id, name, email');
        $this->json([
            "status"=>200,
            "data"=>$result
        ]);
    }

    public function find($req, $res)
    {
        $res->json($req);
    }

    public function store($req) {
        $this->validate($req->body->name, 'required');
        $this->validate($req->body->name, 'minValue', 4);
        $this->validate($req->body->name, 'maxValue', 100);
        $this->validate($req->body->email, 'required');
        $this->validate($req->body->email, 'isEmail');
        $this->validate($req->body->password, 'required');
        $result = self::$usersModel->create($req->body);
        if ($result) {
            $this->index();
        }
    }

    public function update($req) {
        $this->validate($req->body->id, 'required');
        $result = self::$usersModel->update($req->body, "WHERE id = {$req->body->id}");
        if ($result) {
            $this->index();
        }
    }

    public function destroy($req) {
        $this->validate($req->body->id, 'required');
        $result = self::$usersModel->destroy($req->body->id);
        if ($result) {
            $this->index();
        }
    }

}
