<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Services\TelegramService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{

    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     *
     */
    public function index(Request $request)
    {
        try {
            $data = $this->userRepo->paginate([], $request->page, $request->limit, ['roles.permissions', 'permissions']);
            return Response::success($data['data'], $data['total']);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreUserRequest $request
     *
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $params = $request->all();
            $params['id'] = (string)Str::uuid();
            $params['password'] = bcrypt($request->password);
            $data = $this->userRepo->create($params);
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
     *
     */
    public function show($id)
    {
        try {
            $data = $this->userRepo->find($id);
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
     * @param UpdateUserRequest $request
     * @param $id
     *
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = $this->userRepo->update($id, $request->all());
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
     *
     */
    public function destroy($id)
    {
        try {
            $data = $this->userRepo->delete($id);
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
