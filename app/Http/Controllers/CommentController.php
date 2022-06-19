<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Repositories\CommentRepository;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Validator;

class CommentController extends Controller
{

    protected $commentRepo;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     *
     */
    public function index(Request $request)
    {
        try {
            $data = $this->commentRepo->paginate([], $request->page, $request->limit);
            return Response::success($data['data'], $data['total']);
        } catch (Exception $e) {
            TelegramService::sendError($e);
            return Response::error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     *
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'user_id' => 'required',
                    'post_id' => 'required',
                    'content' => 'required'
                ]
            );
            if ($validator->fails()){
                return Response::error($validator->messages());
            }
            $data = $this->commentRepo->createOrUpdate($request->all());
            if(!$data) {
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
            $data = $this->commentRepo->find($id);
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
            $data= $this->commentRepo->delete($id);
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
