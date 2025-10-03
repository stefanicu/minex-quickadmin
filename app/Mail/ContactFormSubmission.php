<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmission extends Mailable
{
    use Queueable, SerializesModels;
    
    public $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function build()
    {
        $source = "Homepage";
        $type = "HOMEPAGE";
        
        // limba activă
        $locale = app()->getLocale();
        $this->data['locale'] = $locale;
        
        if ( ! empty($this->data['product'])) {
            // cu relații
            $product = Product::with(['application', 'category'])->find($this->data['product']);
            
            if ($product) {
                $source = "Product {$product->translate($locale)->name}";
                $type = 'PRODUCT';
                
                // produs locale
                $this->data['product_name'] = $product->translate($locale)->name;
                $this->data['product_slug'] = $product->translate($locale)->slug;
                $this->data['application_slug'] = $product->application->translate($locale)->slug ?? '';
                $this->data['category_slug'] = $product->category->translate($locale)->slug ?? '';
                
                // produs en
                if ($locale != 'en') {
                    $this->data['product_name_en'] = $product->translate('en')->name ?? '';
                    $this->data['product_slug_en'] = $product->translate('en')->slug ?? '';
                    $this->data['application_slug_en'] = $product->application->translate('en')->slug ?? '';
                    $this->data['category_slug_en'] = $product->category->translate('en')->slug ?? '';
                }
            }
        }
        
        return $this->from(config('mail.from.address'), "Form $type minexgroup.eu")
            ->subject("Mesaj nou din formularul de contact ($source)")
            ->view('emails.contact')
            ->with('contact', $this->data);
    }
}
