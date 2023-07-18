<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

use App\Models\Secretaria;

use App\Services\_Filtros;

class SecretariaService
{
    protected $filtros;

    public function __construct(_Filtros $filtros)
    {
        $this->filtros                      = $filtros;
    }

    public function index($filtros)
    {
        try {
            $secretarias = $this->_getConsultaBase($filtros);

            if(!empty($filtros['paginate']))
                $secretarias = $secretarias->paginate($filtros['paginate']);
            else
                $secretarias = !empty($filtros['id']) ?
                                        $secretarias->first() :
                                        $secretarias->get();

            $return = array('message' => $secretarias, 'status' => 200);
        } catch (Exception $e) {
            $return = array('message' => $e->getMessage(), 'status' => 500);
        }

        return $return;
    }

    public function store($request)
    {
        $validator = Validator::make($request, [
            'nome' => 'required|string|max:100'
        ]);

        if ($validator->fails())
            $return = array('message' => ['Invalid Syntax'], 'status' => 400);


        try {
            $secretaria = new Secretaria;
            $secretaria->create(['nome' => strtoupper($request['nome'])]);

            $return = array('message' => $secretaria, 'status' => 201);
        } catch (Exception $e) {
            $return = array('message' => $e->getMessage(), 'status' => 500);
        }


        return $return;
    }

    public function update($request)
    {
        $validator = Validator::make($request, [
            'id'    => 'required|integer',
            'nome'  => 'required|string|max:100'
        ]);

        if ($validator->fails())
            $return = array('message' =>  $validator->errors(), 'status' => 400);

        try {
            $secretaria = Secretaria::find($request['id']);
            $secretaria->nome = strtoupper($request['nome']);
            $secretaria->save();

            $return = array('message' => $secretaria, 'status' => 201);
        } catch (Exception $e) {
            $return = array('message' => $e->getMessage(), 'status' => 500);
        }

        return $return;
    }

    public function restore($request)
    {
        $validator = Validator::make($request, [
            'id' => 'required|integer'
        ]);

        if ($validator->fails())
            $return = array('message' =>  $validator->errors(), 'status' => 400);

        try{
            $secretaria = Secretaria::withTrashed()
                                        ->find($request['id'])
                                        ->restore();

            $return = array('message' => $secretaria, 'status' => 201);
        } catch (Exception $e) {
            $return = array('message' => $e->getMessage(), 'status' => 500);
        }

        return $return;
    }

    public function delete($id)
    {
        if(!(is_numeric($id) && is_int($id+0)))
            $return = array('message' => ['Invalid Syntax'], 'status' => 400);

        try {
            $secretaria = Secretaria::find($id)->delete();

            $return = array('message' => $secretaria, 'status' => 201);
        } catch (Exception $e) {
            $return = array('message' => $e->getMessage(), 'status' => 500);
        }

        return $return;
    }

    protected function _getConsultaBase($filtros)
    {
        $query = $this->filtros->montarQuery($filtros, 'secretarias');

        if(!empty($filtros['todos']) && $filtros['todos'])
                $secretarias = Secretaria::withTrashed()
                                            ->where(function ($q) use($query) {
                                                $q->where($query);
                                            });

        elseif(!empty($filtros['deletados']) && $filtros['deletados'])
            $secretarias = Secretaria::withTrashed()->where(function ($q) use($query) {
                $q->where($query);
            })->whereNotNull('deleted_at');

        else
            $secretarias = Secretaria::where(function ($q) use($query) {
                $q->where($query);
            });

        return $secretarias->latest('id');
    }
}
