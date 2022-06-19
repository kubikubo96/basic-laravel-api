<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\TelegramService;
use Exception;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{

    protected $roleRepo;
    protected $userRepo;
    protected $permissionRepo;

    public function __construct(RoleRepository $roleRepo, UserRepository $userRepo, PermissionRepository $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index(Request $request): array
    {
        try {
            $data = $this->roleRepo->all();
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
                    'name' => 'required|max:100|unique:roles,name',
                    'title' => 'required|max:500'
                ]
            );
            if ($validator->fails()) {
                return Response::error($validator->messages());
            }
            $data = $this->roleRepo->createOrUpdate($request->all());
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
            $data = $this->roleRepo->find($id);
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
            $data = $this->roleRepo->delete($id);
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
     * Thêm/bớt danh sách permissions cho role
     *
     * @param $request
     * @return array
     */
    public function syncedPermissions($request): array
    {
        try {
            $role_id = $request->input('role_id');
            $permission_ids = $request->input('permission_ids');

            $role = $this->roleRepo->find($role_id);
            if (!$role) {
                return Response::error('NOT_FOUND_ROLE');
            }
            if (empty($permission_ids)) {
                return Response::error('EMPTY_PERMISSION');
            }
            $role->syncPermissions($permission_ids);
            return Response::success();
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    /**
     * Loại bỏ 1 permission khỏi role
     *
     * @param $request
     * @return array
     */
    public function revokePermission($request): array
    {
        try {
            $role_id = $request->input('role_id');
            $permission_id = $request->input('permission_id');

            $role = $this->roleRepo->find($role_id);
            if (!$role) {
                return Response::error('NOT_FOUND_ROLE');
            }
            if (!$permission_id) {
                return Response::error('EMPTY_PERMISSION');
            }
            $role->revokePermissionTo($permission_id);

            return Response::success();
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    /**
     * Thêm/bớt danh sách role cho user
     *
     * @param $request
     * @return array
     */
    public function syncedSub($request): array
    {
        try {
            $user_id = $request->input('user_id');
            $role_ids = $request->input('role_ids');

            $user = $this->userRepo->find($user_id);
            if (!$user) {
                return Response::error('NOT_FOUND_USER');
            }
            if (empty($role_ids)) {
                return Response::error('EMPTY_ROLE');
            }
            $user->syncRoles($role_ids);

            return Response::success();
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    /**
     * Loại bỏ 1 role khỏi user
     *
     * @param $request
     * @return array
     */
    public function removeRole($request): array
    {
        try {
            $user_id = $request->input('user_id');
            $role_id = $request->input('role_id');

            $sub = $this->userRepo->find($user_id);
            if (!$sub) {
                return Response::error();
            }
            if (!$role_id) {
                return Response::error();
            }
            $sub->removeRole($role_id);

            return Response::success();
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }
}
