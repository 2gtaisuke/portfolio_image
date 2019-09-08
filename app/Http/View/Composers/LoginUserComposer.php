<?php
namespace App\Http\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginUserComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $view->with('login_user', $this->request->user());
    }
}