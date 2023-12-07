<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Genres;
use App\Models\Resources;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Str;
use App\Models\Songs;
use App\Models\Artists;
use Illuminate\Database\QueryException;

class ApiResourcesController extends Controller
{
    protected $table_name = null;
    protected $model = null;
    protected $segments = [];
    protected $collection = null;
    protected $id = null;
    protected $action = null;
    protected $responder = null;

    public $response = array();

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Request $request, Resources $model, ResponseService $responder)
    {
        try {
            $this->responder = $responder;
            $this->collection = $request->segment(3);
            $this->id = $request->segment(4);
            $this->action = $request->segment(5);

            if (file_exists(app_path('Models/' . Str::studly($this->collection)) . '.php')) {
                $this->model = app("App\Models\\" . Str::studly($this->collection));
            } else {
                if ($model->checkTableExists($this->collection)) {
                    $this->model = $model;
                    $this->model->setTable($this->collection);
                }
            }
            if ($this->model) {
                $this->responder->set('collection', $this->model->getTable());
            }

            if (is_null($this->model)) {
                $this->responder->set('message', "Model not found!");
                $this->responder->setStatus(404, 'Not found.');
                return $this->responder->response();
            }

            if (is_null($this->table_name)) $this->table_name = $this->collection;
            $this->segments = $request->segments();
        } catch (Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $entries = $this->model
                ->filter($request)
                ->orderFilter($request)
                ->statusActive();

            $entriesCounted = $entries->count();
            $entriesData = $entries->paginateFilter($request)->get();

            $this->responder->setCount($entriesCounted);
            $this->responder->setData($entriesData);
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Display a data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        try {
            $entry = $this->model->statusActive()->find($this->id);

            if (!$entry) {
                $this->responder->set('message', "Data not found!");
                $this->responder->setStatus(404, 'Not found.');
                return $this->responder->response();
            }

            $this->responder->setData($entry);
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Store a data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $validator = $this->model->validator($request);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->set('message', $validator->errors()->first());
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }
            $fields = $request->only($this->model->getFields());
            foreach ($fields as $key => $value) {
                $this->model->setAttribute($key, $value);
            }
            $this->model->save();

            $this->responder->setMessage('Data created.');
            $this->responder->setData($this->model);
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    // /**
    //  * Store a data of the resoure.
    //  * 
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {   
    //     $attr = $request->all();
    //     $datasongs = Songs::create([])
    //     try {
    //         $validator = $this->model->validator($request);
    //         if ($validator->fails()) {
    //             $this->responder->set('errors', $validator->errors());
    //             $this->responder->set('message', $validator->errors()->first());
    //             $this->responder->setStatus(400, 'Bad Request.');
    //             return $this->responder->response();
    //         }
    //         $fields = $request->only($this->model->getFields());
    //         foreach ($fields as $key => $value) {
    //             $this->model->setAttribute($key, $value);
    //         }
    //         $this->model->save();

    //         $this->responder->setMessage('Data created.');
    //         $this->responder->setData($this->model);
    //         return $this->responder->response();
    //     } catch (Exception $e) {
    //         $this->responder->set('message', $$e->getMessage());
    //         $this->responder->setStatus(500, 'Internal server error.');
    //         return $this->responder->response();
    //     }
    // }

    /**
     * Update a data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $entry = $this->model->statusActive()->find($this->id);

            if (!$entry) {
                $this->responder->set('message', "Data not found!");
                $this->responder->setStatus(404, 'Not found.');
                return $this->responder->response();
            }

            $validator = $this->model->validator($request);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->set('message', $validator->errors()->first());
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }
            $fields = $request->only($this->model->getFields());
            foreach ($fields as $key => $value) {
                $entry->setAttribute($key, $value);
            }
            $entry->save();

            $this->responder->setMessage('Data updated.');
            $this->responder->setData($entry);
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Soft Delete a data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
        try {
            $ids = explode(',', $this->id);

            if (count($ids) === 1) {
                $entry = $this->model->statusActive()->find($this->id);

                if (!$entry) {
                    $this->responder->set('message', "Data not found!");
                    $this->responder->setStatus(404, 'Not found.');
                    return $this->responder->response();
                }

                $entry->setAttribute('status', false);
                $entry->save();

                $this->responder->setMessage('Data deleted.');
                $this->responder->setData($entry);
                return $this->responder->response();
            }

            if (count($ids) > 1) {
                $entries = [];
                foreach ($ids as $id) {
                    $entry = $this->model->statusActive()->find($id);

                    if ($entry) {
                        $entry->setAttribute('status', false);
                        $entry->save();
                        array_push($entries, $entries);
                    }
                }

                $this->responder->setMessage('Data deleted.');
                $this->responder->setData($entries);
                return $this->responder->response();
            }

            $this->responder->set('message', "Data not found!");
            $this->responder->setStatus(404, 'Not found.');
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Hard Delete a data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    public function hardDelete(Request $request)
    {
        try {
            $ids = explode(',', $this->id);

            if (count($ids) === 1) {
                $entry = $this->model->statusActive()->find($this->id);

                if (!$entry) {
                    $this->responder->set('message', "Data not found!");
                    $this->responder->setStatus(404, 'Not found.');
                    return $this->responder->response();
                }

                $entry->delete();

                $this->responder->setMessage('Data deleted.');
                $this->responder->setData($entry);
                return $this->responder->response();
            }

            if (count($ids) > 1) {
                $entries = [];
                foreach ($ids as $id) {
                    $entry = $this->model->statusActive()->find($id);

                    if ($entry) {
                        $entry->delete();
                        array_push($entries, $entries);
                    }
                }

                $this->responder->setMessage('Data deleted.');
                $this->responder->setData($entries);
                return $this->responder->response();
            }

            $this->responder->set('message', "Data not found!");
            $this->responder->setStatus(404, 'Not found.');
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * restore Deleted a data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        try {
            $ids = explode(',', $this->id);

            if (count($ids) === 1) {
                $entry = $this->model->statusInactive()->find($this->id);

                if (!$entry) {
                    $this->responder->set('message', "Data not found!");
                    $this->responder->setStatus(404, 'Not found.');
                    return $this->responder->response();
                }

                $entry->setAttribute('status', true);
                $entry->save();

                $this->responder->setMessage('Data restored.');
                $this->responder->setData($entry);
                return $this->responder->response();
            }

            if (count($ids) > 1) {
                $entries = [];
                foreach ($ids as $id) {
                    $entry = $this->model->statusInactive()->find($id);

                    if ($entry) {
                        $entry->setAttribute('status', true);
                        $entry->save();
                        array_push($entries, $entries);
                    }
                }

                $this->responder->setMessage('Data restored.');
                $this->responder->setData($entries);
                return $this->responder->response();
            }

            $this->responder->set('message', "Data not found!");
            $this->responder->setStatus(404, 'Not found.');
            return $this->responder->response();
        } catch (Exception $e) {
            $this->responder->set('message', $$e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * add artists data of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
    
        // public function addSongs(Request $request){
        //  try{
        //      $validatedData =  $request->validate([
        //          'songsName' => 'required|string',
        //          'genresName' => 'required|string',
        //          'artistsName'=> 'required|array|min:1',
        //          'artistsName.*'=> 'string',
        //          'publishedDate'=> 'Date'
        //      ]);
     
        //      $newSongs = new Songs();
        //      $newSongs->Name = $validatedData['songsName'];
        //      $newSongs->genres_id = Genres::firstOrCreate(['Name' => trim($validatedData['genresName'])])->id;
        //      $newSongs->publishedDate = $validatedData['publishedDate'];
        //      $newSongs->save();
     
             
        //      // $newArtists = explode(',', $validatedData['artistsName']);
        //      foreach($validatedData['artistsName'] as $newArtists){
        //          $artists = Artists::firstOrCreate(['Name' => trim($newArtists)]);
        //          $newSongs->artists()->attach($artists->id);
        //      }
             
        //      return response()->json(['message' => 'Data stored successfully'], 201);
        //  } catch (QueryException $e) {
        //      // Handle database query exception, e.g., duplicate entry error
        //      return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        //  } catch (\Exception $e) {
        //      // Handle other exceptions
        //      return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        //  }
             
        // }
     
     

   /**
     * show artists of the resoure.
     * 
     * @return \Illuminate\Http\Response
     */
     public function Artists(){
        $artists = Songs::with('Artists')->get();
        return $this->responder->response()->json($artists);
    }
}
