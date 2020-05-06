<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.03.2019
 * Time: 10:23
 */

namespace Tests\Unit;


use App\Affiliate;
use App\Components\Payment\Classes\PaymentGateway;
use App\Http\Requests\TeacherUpdateRequest;
use App\Notifications\PaymentConfirmation;
use App\Teacher;
use App\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Payment;

class ExampleTest extends TestCase {

	public function testSomething() {
		$dr = Payment::gateway( PaymentGateway::GW_MANUAL );

		$foo = '';

	}

	public function testAnything() {

		Teacher::find(191)->students()->attach( 21628, [
			'language' => 'English',
			'course'   => 'Standard English'
		] );

		$foo = '';
	}

	public function testPaypal()
	{
		$transaction = Transaction::find( 22752 );

		$transaction->paymentAmount = 22;

		$res = Payment::gateway('paypal')
			->withTransaction($transaction)
			->updateOrder();

		$baz = 'bar';
	}

	/**
	 * @dataProvider provider
	 * @param $ref_code
	 * @param $expected
	 */
	public function testDo( $ref_code, $expected )
	{
		$dash_indx = strpos( $ref_code, '-' );
		$code = ( !$dash_indx ) ? $ref_code : substr( $ref_code, 0, $dash_indx);

		$affiliate = Affiliate::where([['affiliateCode', $code], ['active', 1]])->first();

		$commission = ( $affiliate ) ? $affiliate->commission : 0;

		$this->assertEquals( $expected, $commission);
	}

	public function provider()
	{
		return [
			['taoc821', 15],
			['', 0],
			['something', 0],
			['FB-192.178.161.203', 0]
		];
	}

	public function feePP()
	{
		$order = $this->getOrder();

		$capture = Arr::first(
			$order->purchase_units[0]->payments->captures,
			function ( $capture ){ return $capture->final_capture; }
		);

		return $capture->seller_receivable_breakdown->paypal_fee->value;
	}

	public function getOrder() {
		return
			'{
  "id": "5O190127TN364715T",
  "status": "COMPLETED",
  "payer": {
    "name": {
      "given_name": "John",
      "surname": "Doe"
    },
    "email_address": "customer@example.com",
    "payer_id": "QYR5Z8XDVJNXQ"
  },
  "purchase_units": [
    {
      "reference_id": "d9f80740-38f0-11e8-b467-0ed5f89f718b",
      "shipping": {
        "address": {
          "address_line_1": "2211 N First Street",
          "address_line_2": "Building 17",
          "admin_area_2": "San Jose",
          "admin_area_1": "CA",
          "postal_code": "95131",
          "country_code": "US"
        }
      },
      "payments": {
        "captures": [
          {
            "id": "3C679366HH908993F",
            "status": "COMPLETED",
            "amount": {
              "currency_code": "USD",
              "value": "100.00"
            },
            "seller_protection": {
              "status": "ELIGIBLE",
              "dispute_categories": [
                "ITEM_NOT_RECEIVED",
                "UNAUTHORIZED_TRANSACTION"
              ]
            },
            "final_capture": true,
            "disbursement_mode": "INSTANT",
            "seller_receivable_breakdown": {
              "gross_amount": {
                "currency_code": "USD",
                "value": "100.00"
              },
              "paypal_fee": {
                "currency_code": "USD",
                "value": "3.00"
              },
              "net_amount": {
                "currency_code": "USD",
                "value": "97.00"
              }
            },
            "create_time": "2018-04-01T21:20:49Z",
            "update_time": "2018-04-01T21:20:49Z",
            "links": [
              {
                "href": "https://api.paypal.com/v2/payments/captures/3C679366HH908993F",
                "rel": "self",
                "method": "GET"
              },
              {
                "href": "https://api.paypal.com/v2/payments/captures/3C679366HH908993F/refund",
                "rel": "refund",
                "method": "POST"
              }
            ]
          }
        ]
      }
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v2/checkout/orders/5O190127TN364715T",
      "rel": "self",
      "method": "GET"
    }
  ]
}';
	}

	private function getSourse() {
		return '{
	"id": "WH-58251543AK436080M-5G142140ML6701117",
	"create_time": "2020-02-05T19:44:59.007Z",
	"resource_type": "checkout-order",
	"event_type": "CHECKOUT.ORDER.APPROVED",
	"summary": "An order has been approved by buyer",
	"resource": {
		"create_time": "2020-02-05T19:44:35Z",
		"purchase_units": [
			{
				"reference_id": "22736",
				"amount": {
					"currency_code": "USD",
					"value": "48.00"
				},
				"payee": {
					"email_address": "papajini-facilitator@gmail.com",
					"merchant_id": "D8GJX2YRPAJWG",
					"display_data": {
						"brand_name": "Live Lingua"
					}
				},
				"description": "Charge for Standard English"
			}
		],
		"links": [
			{
				"href": "https://api.sandbox.paypal.com/v2/checkout/orders/42942721MY574140S",
				"rel": "self",
				"method": "GET"
			},
			{
				"href": "https://api.sandbox.paypal.com/v2/checkout/orders/42942721MY574140S/capture",
				"rel": "capture",
				"method": "POST"
			}
		],
		"id": "42942721MY574140S",
		"intent": "CAPTURE",
		"payer": {
			"name": {
				"given_name": "test",
				"surname": "buyer"
			},
			"email_address": "papajini-buyer@gmail.com",
			"payer_id": "SZZFSNVWET7DL",
			"address": {
				"country_code": "US"
			}
		},
		"status": "APPROVED"
	},
	"status": "PENDING",
	"transmissions": [
		{
			"webhook_url": "https://ac5e7f71.ngrok.io/api/callback/paypal",
			"response_headers": {
				"Content-Length": "34",
				"Content-Type": "text/plain"
			},
			"transmission_id": "00ab95d0-4850-11ea-80c5-718bcfd83275",
			"status": "PENDING",
			"timestamp": "2020-02-05T19:45:03Z"
		}
	],
	"links": [
		{
			"href": "https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-58251543AK436080M-5G142140ML6701117",
			"rel": "self",
			"method": "GET",
			"encType": "application/json"
		},
		{
			"href": "https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-58251543AK436080M-5G142140ML6701117/resend",
			"rel": "resend",
			"method": "POST",
			"encType": "application/json"
		}
	],
	"event_version": "1.0",
	"resource_version": "2.0"
}';
	}
}
