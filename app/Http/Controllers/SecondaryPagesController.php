<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecondaryPagesController extends Controller
{
    public function aboutUs() {
        return view('secondary-pages/about-us');
    }

    public function frecuentQuestions() {
        return view('secondary-pages/frequent-questions');
    }

    public function shippingPolicies() {
        return view('secondary-pages/shipping-policies');
    }

    public function returnsExchanges() {
        return view('secondary-pages/returns-exchanges');
    }

    public function termsAndConditions(){
        return view('secondary-pages/terms-and-conditions');
    }
}
