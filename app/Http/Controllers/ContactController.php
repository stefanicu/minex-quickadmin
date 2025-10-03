<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmission;
use App\Models\Contact;
use App\Models\ContactSpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            // Handle GET request
            return redirect()->route('home.'.app()->getLocale())->withFragment('contact');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email',
            'job' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'how_about' => 'required|string|max:255',
            'message' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'product' => 'sometimes|int|max:10000',
            'ip' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            // Redirect back with errors and add the anchor #form-errors to the URL
            return redirect(url()->previous().'#form-errors')
                ->withErrors($validator)
                ->withInput();
        }
        
        if ( ! empty($request->all())) {
            if (empty($request->district)) {
                try {
                    $contact = Contact::create([
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'email' => $request->email,
                        'job' => $request->job,
                        'industry' => $request->industry,
                        'how_about' => $request->how_about,
                        'message' => $request->message,
                        'company' => $request->company,
                        'phone' => $request->phone,
                        'country' => $request->country,
                        'county' => $request->county,
                        'city' => $request->city,
                        'checkbox' => $request->checkbox,
                        'product' => $request->product,
                        'ip' => $request->ip,
                    ]);
                    // Trimite e-mailul după ce contactul a fost salvat
                    Mail::to('formular@minexgroup.eu')
                        ->cc(['marketing@minexgroup.eu'])
                        ->bcc(['stefan.nicula@9online.ro'])
                        ->send(new ContactFormSubmission($contact));
                } catch (\Exception $exception) {
                    return back()->withError($exception->getMessage());
                }
            } else {
                try {
                    ContactSpam::create([
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'email' => $request->email,
                        'job' => $request->job,
                        'industry' => $request->industry,
                        'how_about' => $request->how_about,
                        'message' => $request->message,
                        'company' => $request->company,
                        'phone' => $request->phone,
                        'country' => $request->country,
                        'county' => $request->county,
                        'city' => $request->city,
                        'checkbox' => $request->checkbox,
                        'product' => $request->product,
                        'ip' => $request->ip,
                        'district' => $request->district,
                    ]);
                } catch (\Exception $exception) {
                    return back()->withError($exception->getMessage());
                }
            }
            
            return Redirect::to(URL::previous()."#contact")->with('success', 'Contact saved!');
        }
    }
}
