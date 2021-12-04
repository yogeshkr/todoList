<?php


namespace App\Http\Controllers;


use App\Models\Todo;
use App\ServiceManager\TodoService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * @var TodoService
     */
    private $todoService;
    /**
     * @var Authenticatable|null
     */
    private $user;

    public function __construct(TodoService $todo)
    {
        $this->todoService = $todo;
        $this->user =  auth('token')->user();
    }

    public function index(Request $request)
    {
        $orderBy    = (!empty($request->get('order_by'))) ? $request->get('order_by') : 'created_at';
        $direction  = (!empty($request->get('direction'))) ? $request->get('direction') : 'DESC';

        if($request->has('completed')){
            $todo = Todo::where(['user_id' => $this->user->id, 'completed' => (int) $request->get('completed')])->orderBy($orderBy, $direction)->get();
        }else{
            $todo = Todo::where(['user_id' => $this->user->id])->orderBy($orderBy, $direction)->get();
        }

        return response()->json([
            'success' => true,
            'data'    => $todo
        ], 200);
    }


    public function show($id)
    {
        $todo = Todo::where(['id' => $id, 'user_id' => $this->user->id])->first();

        if ($todo instanceof Todo) {

            return response()->json([
                'success'    => true,
                'data'      => $todo
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [
                'message' => 'Todo task not found'
            ]
        ], 401);
    }


    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'filled|max:500',
            'body'  => 'filled|max:1000',
            'media_file' => 'required|mimes:png,jpeg,jpg,pdf|max:2048',
        ]);

        if ($validate->fails()) {

            return response()->json([
                'success' => false,
                'data' => [
                    'message' => $validate->errors()->first()
                ]
            ]);
        }
        $requestData = $request->all();
        $requestData['user_id'] = $this->user->id;
        if(empty($requestData['due_date']) || is_null($requestData['due_date'])){
            $requestData['due_date'] = null;
        }

        $todo = new Todo();
        if ($todo->fill($requestData)->save()) {
            $this->todoService->addMedia($request, $todo->id);
            $this->todoService->addReminders($request, $todo->id);
                return response()->json([
                    'success' => true,
                    'data' => [
                        'todo' => $todo,
                        'message' => 'Todo task has been created successfully'
                    ]], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [
                'message' => 'Unable to create Todo record, please try again'
            ]
        ]);
    }


    public function update(Request $request, $id)
    {
        $todo = Todo::where(['id' => $id, 'user_id' => $this->user->id])->first();

        if ($todo instanceof Todo) {
            $validate = Validator::make($request->all(), [
                'title' => 'filled|max:500',
                'body'  => 'filled|max:1000',
                'media_file' => 'required|mimes:png,jpeg,jpg,pdf|max:2048',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'message' => $validate->errors()->first()
                    ]
                ]);
            }
            $formData = $request->all();
            $requestData['user_id'] = $this->user->id;
            $requestData['title'] = $formData['title'];
            $requestData['body'] = $formData['body'];
            $requestData['completed'] = $formData['completed'];
            $requestData['due_date'] = $formData['due_date'];

            if(empty($formData['due_date']) || is_null($formData['due_date'])){
                $requestData['due_date'] = null;
            }

            if (Todo::where('id', $id)->update($requestData)) {
                $this->todoService->addMedia($request, $id);
                $this->todoService->addReminders($request, $id);

                $todo = Todo::where(['id' => $id, 'user_id' => $this->user->id])->first();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'todo' => $todo,
                        'message' => 'Todo task has been updated successfully'
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Unable to update Todo record, please try again'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [
                'message' => 'Todo task not found'
            ]
        ]);
    }


    public function destroy($id)
    {
        $todo = Todo::where(['id' => $id, 'user_id' => $this->user->id])->first();

        if ($todo instanceof Todo) {
            if (Todo::destroy($id)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'message' => 'Selected todo task has been deleted'
                    ]
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'data' => [
                'message' => 'Todo task not found'
            ]
        ]);
    }

    public function complete($id)
    {
        $todo = Todo::where(['id' => $id, 'user_id' => $this->user->id])->first();

        if ($todo instanceof Todo) {

            $completed = [
                'completed' => 1
            ];

            if (Todo::where('id', $id)->update($completed)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'message' => 'Todo task marked as completed successfully'
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Unable to update Todo record, please try again'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [
                'message' => 'Todo task not found'
            ]
        ]);
    }
}
