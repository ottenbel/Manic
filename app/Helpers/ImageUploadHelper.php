<?php

namespace App\Helpers;

use App\Models\Image;
use Webpatser\Uuid\Uuid;
use InterventionImage;
use Zipper;
use Storage;
use File;

class ImageUploadHelper
{
	public static function UploadImage($file)
	{
		//Calculate file hash
		$hash = hash_file("sha256", $file->getPathName());
		
		//Does the image already exist?
		$image = Image::where('hash', '=', $hash)->first();
		if (count($image))
		{
			//Write the file back to disk if we already have an entry for it in the database but no corresponding file.
			if (!(Storage::exists($image->name)))
			{
				$path = $file->storeAs('storage/images/full', str_replace('storage/images/full/', '', $image->name));
			}
			
			//Write the thumbnail back to disk if we already have an entry for it in the database but no corresponding file.
			if (!(Storage::exists($image->thumbnail)))
			{
				$thumbnailPath = public_path($image->thumbnail);
				$thumbnail = InterventionImage::make($file->getRealPath());
				$thumbnailRatio = 250/$thumbnail->height();
				$thumbnailHeight = 250;
				$thumbnailWidth = $thumbnail->width() * $thumbnailRatio;
				$thumbnail->resize($thumbnailWidth, $thumbnailHeight);
				$thumbnail->save($thumbnailPath);
			}
			
			return $image;
		}
		else
		{
			$path = $file->store('storage/images/full');
			$file_extension = $file->guessExtension();
			
			$image = new Image();
			$image->name = str_replace('public', 'storage', $path);
			$image->hash = $hash;
			$image->extension = $file_extension;
			
			$thumbnailPath = str_replace('full', 'thumb', $image->name);
			$thumbnailDBPath = str_replace('public', 'storage', $thumbnailPath);
			$thumbnail = InterventionImage::make($file->getRealPath());
			$thumbnailRatio = 250/$thumbnail->height();
			$thumbnailHeight = 250;
			$thumbnailWidth = $thumbnail->width() * $thumbnailRatio;
			$thumbnail->resize($thumbnailWidth, $thumbnailHeight);
			$thumbnail->save($thumbnailPath);
	
			$image->thumbnail = $thumbnailDBPath;
			$image->save();
			
			return $image;
		}
	}
	
	public static function UploadImageFromZip($file)
	{
		//Calculate file hash
		$hash = hash_file("sha256", $file->getPathName());
		
		//Does the image already exist?
		$image = Image::where('hash', '=', $hash)->first();
		if (count($image))
		{
			return $image;
		}
		else
		{
			$basePath = base_path();
			$file_extension = File::extension($file);
			$randomString = str_replace('-', '', (Uuid::generate(4) . Uuid::generate(4)));
			$newFileName = $randomString . "." . $file_extension;
			$newFilePath = $basePath . '/public/storage/images/full/' . $newFileName;
			File::Move($file, $newFilePath);
			
			$image = new Image();
			$image->name = 'storage/images/full/' . $newFileName;
			$image->hash = $hash;
			$image->extension = $file_extension;
			
			$thumbnailPath = str_replace('full', 'thumb', $newFilePath);
			$thumbnailDBPath = 'storage/images/thumb/' . $newFileName;
			$thumbnail = InterventionImage::make($newFilePath);
			$thumbnailRatio = 250/$thumbnail->height();
			$thumbnailHeight = 250;
			$thumbnailWidth = $thumbnail->width() * $thumbnailRatio;
			$thumbnail->resize($thumbnailWidth, $thumbnailHeight);
			$thumbnail->save($thumbnailPath);
	
			$image->thumbnail = $thumbnailDBPath;
			$image->save();
			
			return $image;
		}
	}
	
	public static function UploadZip(&$chapter, &$page_number, $file)
	{
		$basePath = base_path();
		//Generate a guid
		$folder = Uuid::generate(4);
		//Build a file to extract pages to
		$extractionPath = $basePath . '/public/storage/images/tmp/' . $folder;
		//Unzip to temp directory
		Zipper::make($file->getPathName())->extractTo($extractionPath);
		
		$files = array_sort(File::allFiles($extractionPath), function($file)
		{
			return $file->getFilename();
		});
		
		//Parse through each file in the temp directory
		//if it's an image save it to the relevant directory [might need a slightly different image upload helper function]
		foreach ($files as $file)
		{
			$fileExtension = File::mimeType($file);
			if (($fileExtension == "image/jpeg") || ($fileExtension == "image/bmp") || ($fileExtension == "image/png"))
			{
				//Add check if the upload file is an image type
				$image = self::UploadImageFromZip($file);
				$chapter->pages()->attach($image, ['page_number' => $page_number]);	
				$page_number++;
			}
		}
		
		//Delete the temp directory
		File::deleteDirectory($extractionPath);
	}
}
?>