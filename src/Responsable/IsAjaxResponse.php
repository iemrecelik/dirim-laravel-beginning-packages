<?php

namespace Dirim\BeginningPackage\Responsable;

use Collection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class IsAjaxResponse implements Responsable
{
    protected $ajaxRes;
    protected $view;
    protected $arguments;

    public function __construct(
        $ajaxRes = null,
        $view = null,
        Array $arguments = []
    ) {
        $this->ajaxRes = $ajaxRes;
        $this->view = $view;
        $this->arguments = $arguments;
    }

    public function toResponse($request)
    {
        if ($request->ajax()) {
            return $this->ajaxRes;
        } elseif ($this->view !== null && is_string($this->view)) {
            return view($this->view, $this->arguments);
        } else {
            return abort(404);
        }
    }
}
