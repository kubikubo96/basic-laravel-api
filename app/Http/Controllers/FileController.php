<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Repositories\FileRepository;
use App\Services\Debug\TelegramService;
use Illuminate\Http\Request;
use Exception;
use Validator;

class FileController extends Controller
{

    protected $fileRepo;

    public function __construct(FileRepository $fileRepo)
    {
        $this->fileRepo = $fileRepo;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        try {
            $data = $this->fileRepo->paginate([], $request->page, $request->limit);
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
                    'name' => 'required',
                    'type' => 'required',
                ]
            );
            if ($validator->fails()){
                return Response::error($validator->messages());
            }
            $data = $this->fileRepo->createOrUpdate($request->all());
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
            $data = $this->fileRepo->find($id);
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
            $data= $this->fileRepo->delete($id);
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
