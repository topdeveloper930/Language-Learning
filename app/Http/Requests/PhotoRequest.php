<?php

namespace App\Http\Requests;

use App\Traits\ImageUploadTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

abstract class PhotoRequest extends FormRequest
{
	use ImageUploadTrait;

	protected $subFolder = 'profiles/';
	protected $imageFieldName = 'profileImage';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can( 'update', $this->inst );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    return [
		    'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . $this->maxSize()
	    ];
    }

	public function updatePhoto()
	{
		$image = $this->file( 'file' );

		$image_resize = Image::make( $image->getRealPath() )
		                     ->resize( ...$this->resizeXY() );

		$name = Str::slug( $this->inst->fullName() ) .  '.' . $image->getClientOriginalExtension();

		$filePath = $this->uploadOne( $image_resize, $this->subFolder . $this->inst->getPK(), $name );

		$src = $this->getSrc( $filePath );

		$this->inst->{$this->imageFieldName} = $src;

		return ( $this->inst->save() ) ? $src : false;
	}

	protected function prepareForValidation()
	{
		$this->offsetSet('inst', app( $this->instClass )->findOrFail( (int) $this->route('id') ) );
	}

	protected function maxSize()
	{
		return 512;
	}

	protected function resizeXY()
	{
		return [300, 300];
	}

	protected function getSrc( $path )
	{
		// Add image version as get param to override browser cached image.
		$v = ( $pos = strpos( $this->inst->{$this->imageFieldName}, '?v=' ) )
			? intval( substr( $this->inst->{$this->imageFieldName}, $pos + strlen( '?v=' ) ) ) + 1
			: 0;

		return '/img' . str_replace( [config( 'filesystems.disks.img.root' ), '\\'], ['', '/'], $path ) . '?v=' . $v;
	}
}
