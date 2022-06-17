<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Repositories\PermissionRepository;
use App\Services\Debug\TelegramService;
use Illuminate\Http\Request;
use Exception;
use Validator;

class PermissionController extends Controller
{

    protected $permissionRepo;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        try {
            $data = $this->permissionRepo->paginate([], $request->page, $request->limit);
            return Response::success($data['data'], $data['total']);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'title' => 'required',
                    'name' => 'required',
                ]
            );
            if ($validator->fails()){
                return Response::error($validator->messages());
            }
            $data = $this->permissionRepo->createOrUpdate($request->all());
            if(!$data) {
                return Response::error('BAD_REQUEST', 400);
            }
            return Response::success($data);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return array
     */
    public function show($id): array
    {
        try {
            $data = $this->permissionRepo->find($id);
            if (!$data) {
                return Response::error('BAD_REQUEST', 400);
            }
            return Response::success($data);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return array
     */
    public function destroy($id): array
    {
        try {
            $data= $this->permissionRepo->delete($id);
            if (!$data) {
                return Response::error('BAD_REQUEST', 400);
            }
            return Response::success();
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage(), 400);
        }
    }
}
