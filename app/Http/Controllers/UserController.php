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
     * @return array
     */
    public function index(Request $request): array
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
     * @return array
     */
    public function store(StoreUserRequest $request): array
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
     * @return array
     */
    public function show($id): array
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
     * @return array
     */
    public function update(UpdateUserRequest $request, $id): array
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
     * @return array
     */
    public function destroy($id): array
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
