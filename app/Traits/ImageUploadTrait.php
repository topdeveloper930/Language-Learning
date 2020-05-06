<?php


namespace App\Traits;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;

trait ImageUploadTrait {

	public function uploadOne( $uploadedFile, $folder = null, $filename = null, $disk = 'img' )
	{
		return ( $uploadedFile instanceof \Intervention\Image\Image )
			? $this->interventionImage( $uploadedFile, $folder, $filename, $disk )
			: $this->uploadedFile( $uploadedFile, $folder, $filename, $disk );
	}

	public function uploadedFile( UploadedFile $uploadedFile, $folder = null, $filename = null, $disk = 'img' )
	{
		$filename OR $filename = Str::random( 25 ) . '.' . $uploadedFile->getClientOriginalExtension();

		return $uploadedFile->storeAs( $folder, $filename, $disk );
	}

	public function interventionImage( Image $uploadedFile, $folder = null, $filename = null, $disk = 'img' )
	{
		$filename OR $filename = Str::random( 25 )
		                         . str_replace('image/', '.', $uploadedFile->mime());

		$path   = rtrim( config( "filesystems.disks.$disk.root" ), '\/' );
		$folder = trim( $folder, '\/' );
		$path   .= DIRECTORY_SEPARATOR . $folder;

		if( ! File::isDirectory( $path ) )
			File::makeDirectory( $path, 493, true );

		return $uploadedFile->save( $path . DIRECTORY_SEPARATOR . $filename )->basePath();
	}
}