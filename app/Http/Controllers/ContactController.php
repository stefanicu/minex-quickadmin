<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\ContactSpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ContactController extends Controller
{
    /**
     * @param  Request  $store_contact_request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function index(Request $store_contact_request)
    {
        if(!empty($store_contact_request->all()))
        {
            if(empty($store_contact_request->district))
            {
                try
                {
                    Contact::create([
                        'name' => $store_contact_request->name,
                        'surname' => $store_contact_request->surname,
                        'email' => $store_contact_request->email,
                        'job' => $store_contact_request->job,
                        'industry' => $store_contact_request->industry,
                        'how_about' => $store_contact_request->how_about,
                        'message' => $store_contact_request->message,
                        'company' => $store_contact_request->company,
                        'phone' => $store_contact_request->phone,
                        'country' => $store_contact_request->country,
                        'county' => $store_contact_request->county,
                        'city' => $store_contact_request->city,
                        'checkbox' => $store_contact_request->checkbox,
                        'product' => $store_contact_request->product,
                        'ip' => $store_contact_request->ip,
                    ]);
                }
                catch (\Exception $exception)
                {
                    return back()->withError($exception->getMessage());
                }
            }
            else
            {
                try
                {
                    ContactSpam::create([
                        'name' => $store_contact_request->name,
                        'surname' => $store_contact_request->surname,
                        'email' => $store_contact_request->email,
                        'job' => $store_contact_request->job,
                        'industry' => $store_contact_request->industry,
                        'how_about' => $store_contact_request->how_about,
                        'message' => $store_contact_request->message,
                        'company' => $store_contact_request->company,
                        'phone' => $store_contact_request->phone,
                        'country' => $store_contact_request->country,
                        'county' => $store_contact_request->county,
                        'city' => $store_contact_request->city,
                        'checkbox' => $store_contact_request->checkbox,
                        'product' => $store_contact_request->product,
                        'ip' => $store_contact_request->ip,
                        'district' => $store_contact_request->district,
                    ]);
                }
                catch (\Exception $exception)
                {
                    return back()->withError($exception->getMessage());
                }
            }

            return Redirect::to(URL::previous() . "#contact")->with('success','Contact saved!');
        }
    }
}
