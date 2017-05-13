<?php

namespace App\Helpers;

use App\Models\Image;
use InterventionImage;

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
}
?>