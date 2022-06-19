<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Repositories\TodoRepository;
use App\Services\TelegramService;
use Exception;
use Illuminate\Http\Request;
use Validator;

class TodoController extends Controller
{

    protected $todoRepo;

    public function __construct(TodoRepository $todoRepo)
    {
        $this->todoRepo = $todoRepo;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        try {
            $data = $this->todoRepo->index($request);
            return Response::success($data['data'], $data['total']);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
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
                    'content' => 'required',
                ]
            );
            if ($validator->fails()) {
                return Response::error($validator->messages());
            }
            $data = $this->todoRepo->createOrUpdate($request->all());
            if (!$data) {
                return Response::error();
            }
            return Response::success($data);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
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
            $data = $this->todoRepo->find($id);
            if (!$data) {
                return Response::error();
            }
            return Response::success($data);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id): array
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'content' => 'required',
                ]
            );
            if ($validator->fails()) {
                return Response::error($validator->messages());
            }
            $data = $this->todoRepo->update($id, $request->all());
            if (!$data) {
                return Response::error();
            }
            return Response::success($data);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
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
            $data = $this->todoRepo->delete($id);
            if (!$data) {
                return Response::error();
            }
            return Response::success();
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
        }
    }
}
