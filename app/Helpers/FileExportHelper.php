<?php

namespace App\Helpers;


use App\Models\ChapterExport;
use App\Models\VolumeExport;
use App\Models\CollectionExport;
use DateTime;
use Storage;
use Webpatser\Uuid\Uuid;

class FileExportHelper
{
	public static function ExportChapter($chapter)
	{
		$chapterExport = $chapter->export;
		
		if($chapterExport != null)
		{
			//Update the last downloaded date to keep the file on disk for another week
			$chapterExport->last_downloaded = new DateTime;
			$chapterExport->save();
			
			return $chapterExport;
		}
		else
		{
			if ($chapter->pages->count() > 0)
			{
				//Generate a random file name for the chapter zip
				$fileName = str_replace('-', '', (Uuid::generate(4) . Uuid::generate(4))) . ".zip";
				$filePath = 'storage/images/export/chapters/' . $fileName;
				
				$chapterExport = new ChapterExport;
				$chapterExport->chapter_id = $chapter->id;
				$chapterExport->last_downloaded = new DateTime;
				$chapterExport->path = $filePath;
				$chapterExport->save();
			
				$chapterPath = $chapter->collection->name . " - Chapter " . $chapter->chapter_number;
				if ($chapter->name != null)
				{
					$chapterPath = $chapterPath . " - " .  $chapter->name;
				}
				
				$zipper = new \Chumper\Zipper\Zipper;
				$zipper->make($filePath)->folder($chapterPath);
				//Generate zip file corresponding to the chapter
				foreach ($chapter->pages as $page)
				{
					if (Storage::exists($page->name))
					{
						$zippedPageName = $page->pivot->page_number . '.' . $page->extension;
						$zipper->zip($filePath)->folder($chapterPath)->add($page->name, $zippedPageName);
					}
				}
				$zipper->close();
				
				return $chapterExport;
			}
			else
			{
				return null;
			}
		}
	}
	
	public static function ExportVolume($volume)
	{
		$volumeExport = $volume->export;
		
		if($volumeExport != null)
		{
			//Update the last downloaded date to keep the file on disk for another week
			$volumeExport->last_downloaded = new DateTime;
			$volumeExport->save();
			
			return $volumeExport;
		}
		else
		{
			if ($volume->chapters->count() > 1)
			{
				//Generate a random file name for the volume zip
				$fileName = str_replace('-', '', (Uuid::generate(4) . Uuid::generate(4))) . ".zip";
				$filePath = 'storage/images/export/volumes/' . $fileName;
				
				$volumeExport = new VolumeExport;
				$volumeExport->volume_id = $volume->id;
				$volumeExport->last_downloaded = new DateTime;
				$volumeExport->path = $filePath;
				$volumeExport->save();
			
				$volumePath = $volume->collection->name . " - Volume " . $volume->volume_number;
				if ($volume->name != null)
				{
					$volumePath = $volumePath . " - " .  $volume->name;
				}
				
				$zipper = new \Chumper\Zipper\Zipper;
				$zipper->make($filePath)->folder($volumePath);
				//Generate zip file corresponding to the volume
				foreach ($volume->chapters as $chapter)
				{
					$chapterPath = $volumePath . '/' . "Chapter " . $chapter->chapter_number;
					if ($chapter->name != null)
					{
						$chapterPath = $chapterPath . " - " .  $chapter->name;
					}
					
					foreach ($chapter->pages as $page)
					{
						if (Storage::exists($page->name))
						{
							$zippedPageName = $page->pivot->page_number . '.' . $page->extension;
							$zipper->zip($filePath)->folder($chapterPath)->add($page->name, $zippedPageName);
						}
					}
				}
				$zipper->close();
				
				return $volumeExport;
			}
			else
			{
				return null;
			}
		}
	}
	
	public static function ExportCollection($collection)
	{
		$collectionExport = $collection->export;
		
		if($collectionExport != null)
		{
			//Update the last downloaded date to keep the file on disk for another week
			$collectionExport->last_downloaded = new DateTime;
			$collectionExport->save();
			
			return $collectionExport;
		}
		else
		{
			if ($collection->volumes->count() > 1)
			{
				//Generate a random file name for the volume zip
				$fileName = str_replace('-', '', (Uuid::generate(4) . Uuid::generate(4))) . ".zip";
				$filePath = 'storage/images/export/collections/' . $fileName;
				
				$collectionExport = new CollectionExport;
				$collectionExport->collection_id = $collection->id;
				$collectionExport->last_downloaded = new DateTime;
				$collectionExport->path = $filePath;
				$collectionExport->save();
				
				$collectionName = $collection->name;
				
				$zipper = new \Chumper\Zipper\Zipper;
				$zipper->make($filePath)->folder($collectionName);
				//Generate zip file corresponding to the collection
				
				foreach ($collection->volumes as $volume)
				{
					$volumePath = $collectionName . '/' . "Volume " . $volume->volume_number;
					if ($volume->name != null)
					{
						$volumePath = $volumePath . " - " .  $volume->name;
					}
					
					foreach ($volume->chapters as $chapter)
					{
						$chapterPath = $volumePath . '/' . "Chapter " . $chapter->chapter_number;
						if ($chapter->name != null)
						{
							$chapterPath = $chapterPath . " - " .  $chapter->name;
						}
						
						foreach ($chapter->pages as $page)
						{
							if (Storage::exists($page->name))
							{
								$zippedPageName = $page->pivot->page_number . '.' . $page->extension;
								$zipper->zip($filePath)->folder($chapterPath)->add($page->name, $zippedPageName);
							}
						}
					}
				}
				
				$zipper->close();
				
				return $collectionExport;
			}
			else
			{
				return null;
			}
		}
	}
}
?>