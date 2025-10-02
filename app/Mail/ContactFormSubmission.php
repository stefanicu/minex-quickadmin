<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmission extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instanța modelului Contact cu datele din formular.
     *
     * @var \App\Models\Contact
     */
    public $contact;

    /**
     * Creează o nouă instanță a mesajului.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Definește "plicul" mesajului (subiect, destinatar etc.).
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Formular de Contact Nou',
        );
    }

    /**
     * Definește conținutul mesajului.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.contact_submission',
        );
    }

    /**
     * Definește atașamentele mesajului.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
