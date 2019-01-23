<?php

namespace Dirim\BeginningPackage\Library;

use Illuminate\Http\UploadedFile;
use Storage;
use Image;

/**
 * File upload proccess
 */
class ImgFileUpload
{
    protected $file;
    protected $hashFileName;
    protected $guessExtension;
    protected $filters;
    protected $savePath = [];
    
    public function __construct(Array $config = null)
    {
        if (is_array($config)) {
            $this->setConfig(
                $config['file'],
                $config['filters'],
                $config['extension']
            );
        }
    }

    public function setConfig(UploadedFile $file, Array $filters = null, String $extension = null)
    {
        $this->file = $file;
        $this->filters = $filters;
        $this->hashFileName = explode('.', $file->hashName())[0];
        $this->guessExtension = $extension ?? $file->guessExtension();
    }

    public function saveImg()
    {
        if ($this->filters !== null) {
            $savePathAndFileName = $this->savePathAndFileName();
            
            foreach ($this->filters as $keyFilt => $valFilt) {
                $img = $this->performFilters($valFilt);

                Storage::put(
                    str_replace('*filtName*', $keyFilt, $savePathAndFileName),
                    $img->encode()
                );
            }
        } else {
            $img = Image::make($this->file);
            Storage::put($this->savePathAndFileName('raw'), $img->encode());
        }

        return $this;
    }

    public function performFilters($filter)
    {
        $img = Image::make($this->file);

        foreach ($filter as $key => $val) {
            if (is_array($val) && count($val) > 0) {
                $img->$key(...$val);
            } elseif (is_string($val)) {
                $img->$key($val);
            }
        }

        return $img;
    }

    public function savePathAndFileName($filtName = '*filtName*')
    {
        $fileName = $this->hashFileName.'.'.$this->guessExtension;
        $pathName = implode('/', [
            date('Y'),
            date('m'),
            date('d'),
            date('H'),
        ]);
        $imagesPath = '/public/upload/images/'.$filtName;
        
        $pathAndFileName = "{$imagesPath}/{$pathName}/{$fileName}";
        
        $this->setSavePath("/{$pathName}/{$fileName}");

        return $pathAndFileName;
    }

    /**
     * @return mixed
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * @param mixed $savePath
     *
     * @return self
     */
    public function setSavePath($savePath)
    {
        $this->savePath[] = $savePath;

        return $this;
    }

    /**
     * @param mixed $file
     *
     * @return self
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }
}
