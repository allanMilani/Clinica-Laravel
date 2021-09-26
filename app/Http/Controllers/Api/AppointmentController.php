<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Phisician;

class AppointmentController extends Controller
{

    private $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function index()
    {
        $data = [
            'status' => 200,
            'msg' => '',
            'data' => []
        ];

        $fromDate = request('fromDate');
        $toDate = request('toDate');

        if ($fromDate || $toDate) {
            $queryParams = [];
            if ($fromDate) {
                array_push($queryParams, array('start', '>', $fromDate));
            }
            if ($toDate) {
                array_push($queryParams, array('start', '<', $toDate));
            }
            $appointments = $this->appointment->where($queryParams)->with('patient')->with('phisician')->paginate(10);
        } else {
            $appointments = $this->appointment->with('patient')->with('phisician')->paginate(10);
        }

        if ($appointments && count($appointments) > 0) {
            $data['data'] = $appointments;
            $data['msg'] = 'Consultas encontrados';
            $data['status'] = 200;
        } else {
            $data['msg'] = 'Nenhum consulta foi encontrado';
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

        if (!empty($request->start) && !empty($request->phisicianId) && !empty($request->patientId)) {
            $queryPhisician = Phisician::find($request->phisicianId);
            $queryPatient = Patient::find($request->patientId);
            if ($queryPhisician && $queryPatient) {
                $queryAppointment = $this->appointment->where([
                    ['phisician_id', '=', $request->phisicianId],
                    ['patient_id', '=', $request->patientId],
                    ['start', '=', date('Y-m-d', strtotime($request->start))]
                ])->get();
                if (count($queryAppointment) <= 0) {
                    try {
                        $this->appointment->start = date('Y-m-d', strtotime($request->start));
                        $this->appointment->phisician_id = $queryPhisician->id;
                        $this->appointment->patient_id = $queryPatient->id;
                        $this->appointment->save();
                        $data['msg'] = 'Consulta cadastrada com sucesso!';
                        $data['status'] = 201;
                    } catch (\Exception $e) {
                        $data['msg'] = 'Ocorreu um erro inesperado no serivor, por favor tente novamente!';
                        $data['status'] = 500;
                    }
                } else {
                    $data['msg'] = 'Consulta já cadastrado!';
                    $data['status'] = 409;
                }
            } else {
                $data['msg'] = 'O médico/paciente informado não existe';
                $data['status'] = 409;
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

        $queryAppointment = $this->appointment->with('patient')->with('phisician')->find($id);

        if ($queryAppointment) {
            $data['data'] = $queryAppointment;
            $data['msg'] = 'Consulta encontrada!';
        } else {
            $data['msg'] = 'A consulta informada não existe!';
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
        $queryAppointment = $this->appointment->find($id);

        if ($queryAppointment) {
            $queryPhisician = null;
            $queryPatient = null;
            if ($request->phisicianId && !empty($request->phisicianId)) {
                $queryPhisician = Phisician::find($request->phisicianId);
            }
            if ($request->patientId && !empty($request->patientId)) {
                $queryPatient = Patient::find($request->patientId);
            }
            if (
                ($request->patientId && !empty($request->patientId) && $queryPatient)
                || ($request->phisicianId && !empty($request->phisicianId) && $queryPhisician)
            ) {
                try {
                    if ($request->start && !empty($request->start)) {
                        $queryAppointment->start = date('Y-m-d', strtotime($request->start));
                    }
                    if ($request->end && !empty($request->end)) {
                        $queryAppointment->end = date('Y-m-d', strtotime($request->end));
                    }
                    if ($queryPatient) {
                        $queryAppointment->patient_id = $queryPatient->id;
                    }
                    if ($queryPhisician) {
                        $queryAppointment->phisician_id = $queryPhisician->id;
                    }
                    $queryAppointment->save();
                    $data['msg'] = 'Consulta atualizada com sucesso!';
                    $data['status'] = 201;
                } catch (\Exception $e) {
                    $data['msg'] = 'Ocorreu um erro inesperado no servidor, tente novamente!';
                    $data['status'] = 500;
                }
            } else {
                $data['msg'] = 'O médico/paciente informado não existe';
                $data['status'] = 409;
            }
        } else {
            $data['msg'] = 'A consulta informada não existe!';
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

        $queryAppointment = $this->appointment->find($id);

        if ($queryAppointment) {
            try {
                $queryAppointment->delete();
                $data['msg'] = 'Paciente deletado com sucesso!';
                $data['status'] = 204;
            } catch (\Exception $e) {
                $data['msg'] = 'Ocorreu um erro inesperado no servidor, tente novamente!';
                $data['status'] = 500;
            }
        } else {
            $data['msg'] = 'A consulta informada não existe!';
            $data['status'] = 404;
        }

        return response()->json($data, $data['status']);
    }
}
