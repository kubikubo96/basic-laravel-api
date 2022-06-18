<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\TelegramService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Validator;

class PermissionController extends Controller
{

    protected $permissionRepo;
    protected $roleRepo;
    protected $userRepo;

    public function __construct(PermissionRepository $permissionRepo, RoleRepository $roleRepo, UserRepository $userRepo)
    {
        $this->permissionRepo = $permissionRepo;
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->permissionRepo->paginate([], $request->page, $request->limit);
            return Response::success($data['data'], $data['total']);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'name' => 'required|max:100|unique:permissions,name',
                    'title' => 'required|max:500',
                ]
            );
            if ($validator->fails()) {
                return Response::error($validator->messages());
            }
            $data = $this->permissionRepo->createOrUpdate($request->all());
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
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->permissionRepo->find($id);
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
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $data = $this->permissionRepo->delete($id);
            if (!$data) {
                return Response::error();
            }
            return Response::success();
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
        }
    }

    /**
     * Thêm/bớt danh sách permissions cho user
     *
     * @param $request
     * @return JsonResponse
     */
    public function syncedPermissions($request): JsonResponse
    {
        try {
            $user_id = Arr::get($request, 'user_id');
            $permission_ids = Arr::get($request, 'permission_ids');

            $user = $this->userRepo->find($user_id);
            if (!$user) {
                return Response::error('NOT_FOUND_USER');
            }
            if (!$permission_ids) {
                return Response::error('EMPTY_PERMISSION');
            }
            $user->syncPermissions($permission_ids);
            return Response::success();
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    /**
     * Loại bỏ 1 permission khỏi user
     *
     * @param $request
     * @return JsonResponse
     */
    public function revokePermission($request): JsonResponse
    {
        try {
            $user_id = Arr::get($request, 'user_id');
            $permission_id = (int)Arr::get($request, 'permission_id');

            $user = $this->userRepo->find($user_id);
            if (!$user) {
                return Response::error('NOT_FOUND_USER');
            }
            if (!$permission_id) {
                return Response::error('EMPTY_PERMISSION');
            }
            $user->revokePermissionTo($permission_id);
            return Response::success();
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }
}
