<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phisician;
use Illuminate\Http\Request;

class PhiscianController extends Controller
{
    private $phisician;

    public function __construct(Phisician $phisician)
    {
        $this->phisician = $phisician;
    }

    public function index()
    {
        $data = [
            'status' => 200,
            'msg' => '',
            'data' => []
        ];

        $name = request('name');
        if ($name) {
            $phisicians = $this->phisician->where([
                ['name', 'like', '%' . $name . '%']
            ])->paginate(10);
        } else {
            $phisicians = $this->phisician->paginate(10);
        }

        if ($phisicians && count($phisicians) > 0) {
            $data['data'] = $phisicians;
            $data['msg'] = 'Médicos encontrados';
            $data['status'] = 200;
        } else {
            $data['msg'] = 'Nenhum médico foi encontrado';
            $data['status'] = 404;
        }

        return response()->json($data, $data['status']);
    }
}
