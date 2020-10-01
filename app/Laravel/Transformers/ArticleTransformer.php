<?php 

namespace App\Laravel\Transformers;

use Input,Str;
use JWTAuth, Carbon, Helper;
use App\Laravel\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;

class ArticleTransformer extends TransformerAbstract{

	protected $availableIncludes = [
    ];


	public function transform(Article $article) {

	    return [
	     	'article_id' => $article->id,
	     	'title' => $article->title?:"",
	     	'content' => $article->content?:"",


	     	'date_created' => [
	     		'date_db' => $article->created_at->format("Y-m-d"),
	     		'month_year' => $article->created_at->format("F Y"),
	     		'time_passed' => Helper::time_passed($article->created_at),
	     		'timestamp' => $article->created_at
	     	],

	     	'last_modified' => [
	     		'date_db' => $article->updated_at->format("Y-m-d"),
	     		'month_year' => $article->updated_at->format("F Y"),
	     		'time_passed' => Helper::time_passed($article->updated_at),
	     		'timestamp' => $article->updated_at
	     	],
 	     	
 			'thumbnail' => [
 				'path' => $article->directory?:"",
 	 			'filename' => $article->filename?:"",
 	 			'path' => $article->path?:"",
 	 			'directory' => $article->directory?:"",
 	 			'full_path' => strlen($article->filename) > 0 ? "{$article->directory}/resized/{$article->filename}": "",
 	 			'thumb_path' => strlen($article->filename) > 0 ? "{$article->directory}/thumbnails/{$article->filename}": "",
 			],
	     ];
	}
}