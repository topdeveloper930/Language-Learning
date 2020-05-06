<?php

namespace App\Notifications;

use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Payment extends Notification implements ShouldQueue
{
    use Queueable;

	/**
	 * @var Transaction
	 */
    public $transaction;

    /**
     * Create a new notification instance.
     *
     * @param Transaction $transaction
     *
     * @return void
     */
    public function __construct( Transaction $transaction )
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    protected function getLanguage()
    {
	    return ( $this->transaction->hasAttribute( 'paymentFor' ) AND $this->transaction->paymentFor )
	        ? substr( $this->transaction->paymentFor, 0, strpos( $this->transaction->paymentFor, '-' ))
		    : $this->transaction->getCourseLanguage();
    }

	protected function getCourseType()
	{
		return ( $this->transaction->hasAttribute( 'paymentFor' ) AND $this->transaction->paymentFor )
			? substr( $this->transaction->paymentFor, strpos( $this->transaction->paymentFor, '-' ) + 1 )
			: $this->transaction->getCourseType();
	}

	protected function getCourseTitle()
	{
		return ( $this->transaction->hasAttribute( 'paymentFor' ) AND $this->transaction->paymentFor )
			? $this->transaction->paymentFor
			: $this->transaction->purchase->course->getCourseTitle();
	}
}
