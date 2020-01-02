<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\Paginator;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $statusCode = 200;

    /**
     * @return int
     */
    private function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    protected function respond($data)
    {
        return response()->json($data,$this->getStatusCode());
    }

    protected function respondWithError($message)
    {
        return response()->json([
            'error' => [
                'message' => $message,
                'status' => $this->getStatusCode()
            ]
        ],$this->getStatusCode());
    }

    protected function respondWithSuccess($message = '')
    {
        return response()->json([
            'success' => [
                'message' => $message,
                'status' => $this->getStatusCode()
            ]
        ],$this->getStatusCode());
    }


    /**
     * @param Paginator $items
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithPagination(Paginator $items, $data): \Illuminate\Http\JsonResponse
    {
        $data = array_merge($data, [
            'paginator' => [
                'total'        => $items->total(),
                'total_pages'  => ceil($items->total() / $items->perPage()),
                'current_page' => $items->currentPage(),
                'limit'        => $items->perPage(),
            ]
        ]);

        return $this->respond($data);
    }
}