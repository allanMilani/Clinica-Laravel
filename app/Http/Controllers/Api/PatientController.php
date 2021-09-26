<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private $patient;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
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
            $patients = $this->patient->where([
                ['name', 'like', '%' . $name . '%']
            ])->paginate(10);
        } else {
            $patients = $this->patient->paginate(10);
        }

        if ($patients && count($patients) > 0) {
            $data['data'] = $patients;
            $data['msg'] = 'Pacientes encontrados!';
            $data['status'] = 200;
        } else {
            $data['msg'] = 'Nenhum paciente foi encontrado';
            $data['status'] = 404;
        }

        return response()->json($data, $data['status']);
    }

    public function store(Request $request)
    {
        $data = [
            'status' => 200,
            'msg' => '',
            'data' => []
        ];

        if (!empty($request->name)) {
            $queryPatient = $this->patient->where([
                ['name', '=', $request->name]
            ])->get();
            if ($queryPatient && count($queryPatient) > 0) {
                $data['msg'] = 'Paciente já cadastrado!';
                $data['status'] = 409;
            } else {
                try {
                    $this->patient->name = $request->name;
                    $this->patient->save();
                    $data['msg'] = 'Paciente cadastrado com sucesso!';
                    $data['status'] = 201;
                } catch (\Exception $e) {
                    $data['msg'] = 'Ocorreu um erro inesperado no serivor, por favor tente novamente!';
                    $data['status'] = 500;
                }
            }
        } else {
            $data['msg'] = 'Verifique os campos obrigatórios e tente novamente!';
            $data['status'] = 400;
        }

        return response()->json($data, $data['status']);
    }

    public function show($id)
    {

        $data = [
            'status' => 200,
            'msg' => '',
            'data' => []
        ];
        $queryPatient = $this->patient->find($id);

        if ($queryPatient) {
            $data['data'] = $queryPatient;
            $data['msg'] = 'Paciente encontrado!';
        } else {
            $data['msg'] = 'O paciente informado não existe!';
            $data['status'] = 404;
        }

        return response()->json($data, $data['status']);
    }

    public function edit(Request $request, $id)
    {
        $data = [
            'status' => 200,
            'msg' => '',
            'data' => []
        ];
        $queryPatient = $this->patient->find($id);

        if ($queryPatient) {
            if ($request->name && !empty($request->name)) {
                try {
                    $queryPatient->name = $request->name;
                    $queryPatient->save();
                    $data['msg'] = 'Paciente atualizado com sucesso!';
                    $data['status'] = 204;
                } catch (\Exception $e) {
                    $data['msg'] = 'Ocorreu um erro inesperado no servidor, tente novamente!';
                    $data['status'] = 500;
                }
            } else {
                $data['msg'] = 'Verifique os campos obrigatórios e tente novamente!';
                $data['status'] = 400;
            }
        } else {
            $data['msg'] = 'O paciente informado não existe!';
            $data['status'] = 404;
        }

        return response()->json($data, $data['status']);
    }

    public function destroy($id)
    {
        $data = [
            'status' => 200,
            'msg' => '',
            'data' => []
        ];
        $queryPatient = $this->patient->find($id);

        if ($queryPatient) {
            try {
                $queryPatient->delete();
                $data['msg'] = 'Paciente deletado com sucesso!';
                $data['status'] = 204;
            } catch (\Exception $e) {
                $data['msg'] = 'Ocorreu um erro inesperado no servidor, tente novamente!';
                $data['status'] = 500;
            }
        } else {
            $data['msg'] = 'O paciente informado não existe!';
            $data['status'] = 404;
        }

        return response()->json($data, $data['status']);
    }
}
