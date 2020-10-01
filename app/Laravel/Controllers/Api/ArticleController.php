<?php 

namespace App\Laravel\Controllers\Api;


/* Request validator
 */
use App\Laravel\Requests\PageRequest;


/* Models
 */
use App\Laravel\Models\{Article};


/* Data Transformer
 */
use App\Laravel\Transformers\{TransformerManager,ArticleTransformer};

/* App classes
 */
use Illuminate\Support\Facades\Auth;
use Carbon,DB,Str,FileUploader,URL,Helper,ImageUploader;

class ArticleController extends Controller{
	protected $response = [];
	protected $response_code;
	protected $guard = 'citizen';


	public function __construct(){
		$this->response = array(
			"msg" => "Bad Request.",
			"status" => FALSE,
			'status_code' => "BAD_REQUEST"
			);
		$this->response_code = 400;
		$this->transformer = new TransformerManager;
	}

	public function index(PageRequest  $request,$format = NULL){
		$per_page = $request->get('per_page',10);

		$articles = Article::where("status",'active')
							->orderBy('updated_at',"DESC")
							->paginate($per_page);

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "ARTICLE_LIST";
		$this->response['msg'] = "Article list.";
		$this->response['data'] = $this->transformer->transform($articles,new ArticleTransformer,'collection');
		$this->response_code = 200;
		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}
	}

	public function show(PageRequest $request,$format = NULL){
		$article = $request->get('article_data');

		$this->response['status'] = TRUE;
		$this->response['status_code'] = "ARTICLE_DETAIL";
		$this->response['msg'] = "Article detail.";
		$this->response['data'] = $this->transformer->transform($travel,new ArticleTransformer,'item');
		$this->response_code = 200;
		callback:
		switch(Str::lower($format)){
		    case 'json' :
		        return response()->json($this->response, $this->response_code);
		    break;
		    case 'xml' :
		        return response()->xml($this->response, $this->response_code);
		    break;
		}
	}

}