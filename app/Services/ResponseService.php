<?php

namespace App\Services;

use Illuminate\Http\Request;

class ResponseService
{

    protected $data = array();
    protected $headers = array();
    protected $status = 200;

    /**
     * Create a new ResponseService instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->data = array(
            'app' => config('app.name'),
            'version' => env('APP_VERSION', 'v1'),
            'api_version' => env('API_VERSION', 'v1'),
            'status' => 'OK',
            'collection' => null,
            'code' => 200,
            'data' => [
                'data' => null,
                'count' => 0,
            ],
            'meta' => null,
        );
    }

    public function setData($value)
    {
        $this->data['data']['data'] = $value;
    }

    public function setCount($value)
    {
        $this->data['data']['count'] = $value;
    }

    public function setMessage($value)
    {
        $this->data['data']['message'] = $value;
    }

    public function setErrors($message, $status = 400)
    {
        $this->data['errors']['message'] = $message;
        $this->data['errors']['status'] = $status;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function remove($key)
    {
        unset($this->data[$key]);
    }

    public function setStatus($value, $status)
    {
        $this->status = $value;
        $this->set('code', $value);
        $this->set('status', $status);
    }

    public function header($key = null, $value = null)
    {
        if (is_null($key) && is_null($value)) {
            return $this->headers;
        }
        if (is_null($value)) {
            $this->headers = $key;
        } else {
            $this->headers[$key] = $value;
        }
    }

    public function response()
    {
        return response()->json($this->data['data']);
    }

    public function responseFullData()
    {
        return response()->json($this->data, $this->status);
    }
}
