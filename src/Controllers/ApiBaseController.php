<?php

namespace Ssgroup\ApiManager\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ApiBaseController extends Controller
{

    public $title;

    public function sendDataResponse($data,$status = Response::HTTP_OK)
    {
        return response()->json([
            'error' => false,
            'data' => $data
        ],$status);
    }

    public function sendPaginateResponse($data,$status = Response::HTTP_OK)
    {
        return response()->json($data,$status);
    }

    public function sendSuccessMessageResponse($message,$status = Response::HTTP_OK)
    {
        return response()->json([
            'error' => false,
            'message' => $message
        ],$status);
    }

    public function sendNotPermissionMessage($status = Response::HTTP_FORBIDDEN)
    {
        return response()->json([
            'error' => true,
            'message' => 'User has not permission'
        ],$status);
    }

    public function sendErrorMessageResponse($message,$status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'error' => true,
            'message' => $message
        ],$status);
    }

    public function sendValidationErrorResponse($errors,$status = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'error' => true,
            'message' => 'Validation error.',
            'validation_errors' => $errors
        ],$status);
    }

    public function sendCreateResponse($status = Response::HTTP_CREATED)
    {
        return response()->json([
            'error' => false,
            'message' => $this->title.' create successful.'
        ],$status);
    }

    public function sendShareResponse($message = null,$status = Response::HTTP_CREATED)
    {
        if ($message == null){
            $message = $this->title.' share successful.';
        }

        return response()->json([
            'error' => false,
            'message' => $message
        ],$status);
    }

    public function sendDeleteResponse($status = Response::HTTP_CREATED)
    {
        return response()->json([
            'error' => false,
            'message' => ($this->title ?? '').' delete successful.'
        ],$status);
    }

    public function sendUpdateResponse($status = Response::HTTP_RESET_CONTENT)
    {
        return response()->json([
            'error' => false,
            'message' => $this->title.' update successful.'
        ],$status);
    }

    public function sendNotfoundResponse($status = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'error' => true,
            'message' => $this->title.' not found.'
        ],$status);
    }

    public function sendNotPermissionResponse($status = Response::HTTP_MISDIRECTED_REQUEST)
    {
        return response()->json([
            'error' => true,
            'message' => $this->title.' not permission.'
        ],$status);
    }

    public function sendServerErrorResponse($status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'error' => true,
            'message' => 'Something went wrong.'
        ],$status);
    }

    public function storeBase64Image($request)
    {
        $returnFileName = null;
        $folder_name = $this->folder ?? 'no-folder';

        if (isset($request->image) && preg_match('/^data:image\/(\w+);base64,/', $request->image)) {
            if(!is_dir(storage_path('app/public/'.$folder_name))) {
                mkdir(storage_path('app/public/'.$folder_name));
            }
            $fileName = 'public/'.$folder_name.'/'.uniqid(rand(10000,99999)).'.png';

            $data = substr($request->image, strpos($request->image, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('local')->put($fileName, $data);
            $returnFileName = asset(str_replace('public','storage',$fileName));
        }
        return $returnFileName;
    }

    public function storeBase64ImageFile($image)
    {
        $returnFileName = null;
        $folder_name = $this->folder ?? 'no-folder';

        if (isset($image) && preg_match('/^data:image\/(\w+);base64,/', $image)) {
            if(!is_dir(storage_path('app/public/'.$folder_name))) {
                mkdir(storage_path('app/public/'.$folder_name),0777,true);
            }
            $fileName = 'public/'.$folder_name.'/'.uniqid(rand(10000,99999)).'.png';
            $data = substr($image, strpos($image, ',') + 1);
            $data = base64_decode($data);
//            $data = Image::make($data)->resize(300, 200);
            Storage::disk('local')->put($fileName, $data);
            $returnFileName = asset(str_replace('public','storage',$fileName));
        }
        return $returnFileName;
    }

    public function uploadFile($file)
    {
        $folder_name = $this->folder ?? 'no-folder';
        $file = $file->store('public/'.$folder_name);
        return asset(str_replace('public','storage',$file));
    }


}
