<?php


namespace App\Http\classes;


use Curl\Curl;

class EnvatoPurchaseCodeVerifier
{
    protected $accessToken = '';

    const AUTHOR_SALES_ENDPOINT_URI = 'https://api.envato.com/v3/market/author/sale';

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function verified($purchaseCode)
    {
        if (empty($purchaseCode)) {
            return false;
        }

        $curl = new Curl();

        $curl->setHeader('Authorization', 'Bearer ' . $this->accessToken);

        $curl->get(self::AUTHOR_SALES_ENDPOINT_URI, [
            'code' => $purchaseCode
        ]);

        $curl->close();

        if ($curl->error) {
            return false;
        } else {
            return $curl->response;
        }
    }
}
