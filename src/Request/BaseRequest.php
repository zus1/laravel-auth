<?php

namespace Zus1\LaravelAuth\Request;

use Illuminate\Foundation\Http\FormRequest;
use Zus1\LaravelAuth\Helper\UserHelper;

abstract class BaseRequest extends FormRequest
{
    public function __construct(
        protected UserHelper $userHelper,
    ){
    }
    
    protected function getEmailRules()
    {
        return 'required|email|exists:'.$this->userHelper->getUserTable();
    }
}