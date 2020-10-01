<?php 

namespace App\Laravel\Services;

/*
*
* Models used for this class
*/

/*
*
* Classes used for this class
*/
use Carbon, Str, File, Image, AzureStorage, URL;

class FileUploader {

	/**
	*
	*@param Illuminate\Support\Facades\File $file
	*@param string $file_directory
	*
	*@return array
	*/
	public static function upload($file, $file_directory = "uploads",$storage = "file"){
		
		$storage = env('FILE_STORAGE', "file");


		switch (Str::lower($storage)) {
			case 'file':
				// $file = $request->file("file");
				$ext = $file->getClientOriginalExtension();
				$path_directory = $file_directory."/".Carbon::now()->format('Ymd');

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}
				
				$filename = Helper::create_filename($ext);

				$file->move($path_directory, $filename); 

				return [ 
					"path" => $file_directory, 
					"directory" => URL::to($path_directory), 
					"filename" => $filename
				];

			break;

			case 'azure':
				// $file = $request->file('file');
				$ext = $file->getClientOriginalExtension();
				$filename = $file->getClientOriginalName();

				$path_directory = "{$file_directory}/".Carbon::now()->format('Ymd');

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}


				// $filename = Helper::create_filename($ext);
				$new_image_filename = $filename;
				$file->move($path_directory, $filename); 

				// if(Image::make("{$path_directory}/{$filename}")->width() > Image::make("{$path_directory}/{$filename}")->height()){
				// 	Image::make("{$path_directory}/{$filename}")->resize(null, 512, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$resized_directory}/{$filename}",95);
				// 	Image::make("{$path_directory}/{$filename}")->resize(null, 256, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$thumb_directory}/{$filename}",90);

				// }else{
				// 	Image::make("{$path_directory}/{$filename}")->resize(512, null, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$resized_directory}/{$filename}",95);
				// 	Image::make("{$path_directory}/{$filename}")->resize(256, null, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$thumb_directory}/{$filename}",90);
				// }

				$client = new AzureStorage(env('BLOB_STORAGE_URL'),env('BLOB_ACCOUNT_NAME'),env('BLOB_ACCESS_KEY'));
				
				$container= env('BLOB_CONTAINER');
				$orig_container = env('BLOB_ORIG_CONTAINER');
				$directory = env('BLOB_STORAGE_URL')."/".env('BLOB_CONTAINER');

				// $new_file_directory = "{$directory}/{$path_directory}";
				// $new_image_path = "{$path_directory}";

				$new_file_directory = "{$directory}/".str_replace("uploads/", "", $path_directory); 
				$new_image_path = str_replace("uploads/", "", $path_directory);
				
				$client->putBlob($orig_container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}");
				$client->putBlob($container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}");
				
			
				if (File::exists("{$path_directory}/{$filename}")){
					File::delete("{$path_directory}/{$filename}");
				}

				return [ 
					"path" => $new_image_path, 
					"directory" => $new_file_directory, 
					"filename" => $new_image_filename,
				];
			break;
			
			default:
				return array();
			break;
		}
	}

	/**
	*
	*@param string $url
	*@param string $file_directory
	*
	*@return array
	*/
	public static function copy($url = "", $file_directory = "uploads"){
		
		$storage = env('IMAGE_STORAGE', "file");
		$file = Image::make($url);
		$ext = "jpg";

		$width = $file->width();
		$height = $file->height();

		switch (Str::lower($storage)) {
			case 'file':
				// $file = $request->file("file");
				// $ext = $file->getClientOriginalExtension();
				$thumbnail = ['height' => 250, 'width' => 250];
				$path_directory = $file_directory."/".Carbon::now()->format('Ymd');
				$resized_directory = $file_directory."/".Carbon::now()->format('Ymd')."/resized";
				$thumb_directory = $file_directory."/".Carbon::now()->format('Ymd')."/thumbnails";

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}

				if (!File::exists($resized_directory)){
					File::makeDirectory($resized_directory, $mode = 0777, true, true);
				}

				if (!File::exists($thumb_directory)){
					File::makeDirectory($thumb_directory, $mode = 0777, true, true);
				}

				$filename = Helper::create_filename($ext);

				// $file->move($path_directory, $filename); 
				File::copy($url, $path_directory . "/" . $filename);
				Image::make("{$path_directory}/{$filename}")->interlace()->save("{$resized_directory}/{$filename}",95);
				Image::make("{$path_directory}/{$filename}")->interlace()->crop($thumbnail['width'],$thumbnail['height'])->save("{$thumb_directory}/{$filename}",95);

				return [ 
					"path" => $file_directory, 
					"directory" => URL::to($path_directory), 
					"filename" => $filename,
					"width" => $width,
					"height" => $height,
				];

			break;

			case 'azure':
				// $file = $request->file('file');
				// $ext = $file->getClientOriginalExtension();
				$thumbnail = ['height' => 250, 'width' => 250];

				$path_directory = "{$file_directory}/".Carbon::now()->format('Ymd');
				$resized_directory = "{$file_directory}/".Carbon::now()->format('Ymd')."/resized";
				$thumb_directory = "{$file_directory}/".Carbon::now()->format('Ymd')."/thumbnails";

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}

				if (!File::exists($resized_directory)){
					File::makeDirectory($resized_directory, $mode = 0777, true, true);
				}

				if (!File::exists($thumb_directory)){
					File::makeDirectory($thumb_directory, $mode = 0777, true, true);
				}

				$filename = Helper::create_filename($ext);
				$new_image_filename = $filename;

				// $file->move($path_directory, $filename); 
				File::copy($url, $path_directory . "/" . $filename);

				// if(Image::make("{$path_directory}/{$filename}")->width() > Image::make("{$path_directory}/{$filename}")->height()){
				// 	Image::make("{$path_directory}/{$filename}")->resize(null, 512, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$resized_directory}/{$filename}",95);
				// 	Image::make("{$path_directory}/{$filename}")->resize(null, 256, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$thumb_directory}/{$filename}",90);

				// }else{
				// 	Image::make("{$path_directory}/{$filename}")->resize(512, null, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$resized_directory}/{$filename}",95);
				// 	Image::make("{$path_directory}/{$filename}")->resize(256, null, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$thumb_directory}/{$filename}",90);
				// }

				Image::make("{$path_directory}/{$filename}")->interlace()->save("{$resized_directory}/{$filename}",95);
				Image::make("{$path_directory}/{$filename}")->interlace()->crop($thumbnail['width'],$thumbnail['height'])->save("{$thumb_directory}/{$filename}",95);
				
				$client = new AzureStorage(env('BLOB_STORAGE_URL'),env('BLOB_ACCOUNT_NAME'),env('BLOB_ACCESS_KEY'));
				
				$container= env('BLOB_CONTAINER');
				$orig_container = env('BLOB_ORIG_CONTAINER');
				$directory = env('BLOB_STORAGE_URL')."/".env('BLOB_CONTAINER');

				// $new_file_directory = "{$directory}/{$path_directory}";
				// $new_image_path = "{$path_directory}";

				$new_file_directory = "{$directory}/".str_replace("uploads/", "", $path_directory); 
				$new_image_path = str_replace("uploads/", "", $path_directory);
				
				$client->putBlob($orig_container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}");
				$client->putBlob($container, "{$new_image_path}/thumbnails/{$filename}", "{$path_directory}/thumbnails/{$filename}");
				$client->putBlob($container, "{$new_image_path}/resized/{$filename}", "{$path_directory}/resized/{$filename}");
			
				if (File::exists("{$path_directory}/{$filename}")){
					File::delete("{$path_directory}/{$filename}");
				}
				if (File::exists("{$path_directory}/thumbnails/{$filename}")){
					File::delete("{$path_directory}/thumbnails/{$filename}");
				}
				if (File::exists("{$path_directory}/resized/{$filename}")){
					File::delete("{$path_directory}/resized/{$filename}");
				}

				return [ 
					"path" => $new_image_path, 
					"directory" => $new_file_directory, 
					"filename" => $new_image_filename,
					"width" => $width,
					"height" => $height,
				];

			break;
			
			default:
				return array();
			break;
		}
	}
}