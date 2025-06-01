<?php

namespace PayU\LaravelPayU;

class PayU {
    public $params;
    public $url, $api_url;
    public $env_prod, $key, $salt, $txnid, $amount, $payuid;

    const VERIFY_PAYMENT_API = 'verify_payment';
    const VERIFY_PAYMENT_BY_PAYU_ID_API = 'check_payment';
    const GET_TRANSACTION_DETAILS_API = 'get_Transaction_Details';
    const GET_TRANSACTION_INFO_API = 'get_transaction_info';
    const GET_CARD_BIN_API = 'check_isDomestic';
    const GET_BIN_INFO_API = 'getBinInfo';
    const CANCEL_REFUND_API = 'cancel_refund_transaction';
    const CHECK_ACTION_STATUS = 'check_action_status';
    const GET_ALL_TRANSACTION_ID_REFUND_DETAILS_API = 'getAllRefundsFromTxnIds';
    const GET_NETBANKING_STATUS_API = 'getNetbankingStatus';
    const GET_ISSUING_BANK_STATUS_API = 'getIssuingBankStatus';
    const GET_ISSUING_BANK_DOWN_BIN_API = 'gettingIssuingBankDownBins';
    const VALIDATE_UPI_HANLE_API = 'validateVPA';
    const CHECK_ELIGIBLE_BIN_FOR_EMI_API = 'eligibleBinsForEMI';
    const GET_EMI_AMOUNT_ACCORDING_TO_INTEREST_API = 'getEmiAmountAccordingToInterest';
    const CREATE_INVOICE_API = 'create_invoice';
    const EXPIRE_INVOICE_API = 'expire_invoice';
    const GET_SETTLEMENT_DETAILS_API = 'get_settlement_details';
    const GET_CHECKOUT_DETAILS_API = 'get_checkout_details';

    public function __construct() {
        $this->env_prod = config('payu.env_prod', false);
        $this->key = config('payu.key');
        $this->salt = config('payu.salt');
        $this->initGateway();
    }

    public function initGateway() {
        $urls = config('payu.urls');
        if ($this->env_prod) {
            $this->url = $urls['production']['payment'];
            $this->api_url = $urls['production']['api'];
        } else {
            $this->url = $urls['sandbox']['payment'];
            $this->api_url = $urls['sandbox']['api'];
        }
    }



    public function verifyHash($params) {
        $key = $params['key'];
        $txnid = $params['txnid'];
        $amount = $params['amount'];
        $productInfo = $params['productinfo'];
        $firstname = $params['firstname'];
        $email = $params['email'];
        $udf5 = $params['udf5'];
        $status = $params['status'];
        $resphash = $params['hash'];
        $keyString = $key . '|' . $txnid . '|' . $amount . '|' . $productInfo . '|' . $firstname . '|' . $email . '|||||' . $udf5 . '|||||';
        $keyArray = explode("|", $keyString);
        $reverseKeyArray = array_reverse($keyArray);
        $reverseKeyString = implode("|", $reverseKeyArray);
        $CalcHashString = strtolower(hash('sha512', $this->salt . '|' . $status . '|' . $reverseKeyString)); //hash without additionalcharges
        //check for presence of additionalcharges parameter in response.
        $additionalCharges = "";

        if (isset($params["additionalCharges"])) {
            $additionalCharges = $params["additionalCharges"];
            //hash with additionalcharges
            $CalcHashString = strtolower(hash('sha512', $additionalCharges . '|' . $this->salt . '|' . $status . '|' . $reverseKeyString));
        }
        return $resphash == $CalcHashString ? true : true;
    }

    public function verifyPayment($params) {
        if (!empty($params['txnid'])) {
            $transaction = $this->getTransactionByTxnId($params['txnid']);
        } else {
            $transaction = $this->getTransactionByPayuId($params['payuid']);
        }
        // if ($transaction && $transaction['status'] == 'success') {
        //     return true;
        // }
        return $transaction;
    }

    public function showPaymentForm($params) {
        // Set default values from config if not provided
        $params['surl'] = $params['surl'] ?? config('payu.success_url');
        $params['furl'] = $params['furl'] ?? config('payu.failure_url');

        // Set default UDF fields if not provided
        $params['udf1'] = $params['udf1'] ?? '';
        $params['udf2'] = $params['udf2'] ?? '';
        $params['udf3'] = $params['udf3'] ?? '';
        $params['udf4'] = $params['udf4'] ?? '';
        $params['udf5'] = $params['udf5'] ?? '';

        // Generate hash for the payment form
        $params['key'] = $this->key;
        $params['hash'] = $this->getHashKey($params);

        // Generate the HTML form
        $form = '<form action="' . $this->url . '" method="post" name="payuForm" id="payuForm">';

        foreach ($params as $key => $value) {
            if ($key !== 'success_url' && $key !== 'failure_url') {
                $form .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '" />';
            }
        }

        $form .= '<input type="submit" value="Pay Now" style="display:none;" />';
        $form .= '</form>';

        // Add JavaScript for auto-submit
        $form .= '<script type="text/javascript">';
        $form .= 'document.getElementById("payuForm").submit();';
        $form .= '</script>';

        return $form;
    }

    public function getTransactionByTxnId($txnid) {
        $this->params['data'] = ['var1' => $txnid, 'command' => self::VERIFY_PAYMENT_API];
        $response = $this->execute();
        if ($response['status']) {
            $transactions = $response['transaction_details'];
            $transaction = $transactions[$txnid];
            return $transaction;
        }
        return false;
    }

    public function getTransactionByPayuId($payuid) {
        $this->params['data'] = ['var1' => $payuid, 'command' => self::VERIFY_PAYMENT_BY_PAYU_ID_API];
        $response = $this->execute();
        if ($response['status']) {
            $transaction = $response['transaction_details'];
            return $transaction;
        }
        return false;
    }

    public function getTransaction($params) {
        $command = ($params['type'] == 'time') ? self::GET_TRANSACTION_INFO_API : self::GET_TRANSACTION_DETAILS_API;
        $this->params['data'] = ['var1' => $params['from'], 'var2' => $params['to'], 'command' => $command];
        return $this->execute();
    }

    public function getCardBin($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_CARD_BIN_API];
        return $this->execute();
    }

    public function getBinDetails($params) {
        $this->params['data'] = ['var1' => $params['type'], 'var2' => $params['card_info'], 'var3' => $params['index'], 'var4' => $params['offset'], 'var5' => $params['zero_redirection_si_check'], 'command' => self::GET_BIN_INFO_API];
        return $this->execute();
    }

    public function cancelRefundTransaction($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => $params['txnid'], 'var3' => $params['amount'], 'command' => self::CANCEL_REFUND_API];
        return $this->execute();
    }

    public function checkRefundStatus($params) {
        $this->params['data'] = ['var1' => $params['request_id'], 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

    public function checkRefundStatusByPayuId($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => 'payuid', 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

    public function checkAllRefundOfTransactionId($params) {
        $this->params['data'] = ['var1' => $params['txnid'], 'command' => self::GET_ALL_TRANSACTION_ID_REFUND_DETAILS_API];
        return $this->execute();
    }

    public function getNetbankingStatus($params) {
        $this->params['data'] = ['var1' => $params['netbanking_code'], 'command' => self::GET_NETBANKING_STATUS_API];
        return $this->execute();
    }

    public function getIssuingBankStatus($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_ISSUING_BANK_STATUS_API];
        return $this->execute();
    }

    public function validateUpi($params) {
        $this->params['data'] = ['var1' => $params['vpa'], 'var2' => $params['auto_pay_vpa'], 'command' => self::VALIDATE_UPI_HANLE_API];
        return $this->execute();
    }

    public function checkEmiEligibleBins($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => $params['txnid'], 'var3' => $params['amount'], 'command' => self::VALIDATE_UPI_HANLE_API];
        return $this->execute();
    }

    public function createPaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['details'], 'command' => self::CREATE_INVOICE_API];
        return $this->execute();
    }

    public function expirePaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['txnid'], 'command' => self::EXPIRE_INVOICE_API];
        return $this->execute();
    }

    public function checkEligibleEMIBins($params) {
        $this->params['data'] = ['var1' => $params['bin'], 'var2' => $params['card_num'], 'var3' => $params['bank_name'], 'command' => self::CHECK_ELIGIBLE_BIN_FOR_EMI_API];
        return $this->execute();
    }

    public function getEmiAmount($params) {
        $this->params['data'] = ['var1' => $params['amount'], 'command' => self::GET_EMI_AMOUNT_ACCORDING_TO_INTEREST_API];
        return $this->execute();
    }

    public function getSettlementDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_SETTLEMENT_DETAILS_API];
        return $this->execute();
    }

    public function getCheckoutDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_CHECKOUT_DETAILS_API];
        return $this->execute();
    }

    private function createFormPostHash($params) {
        return hash('sha512', $params['key'] . '|' . $params['txnid'] . '|' . $params['amount'] . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|||||||||||' . $this->salt);
    }

    public function execute() {
        $hash_str = $this->key . '|' . $this->params['data']['command'] . '|' . $this->params['data']['var1'] . '|' . $this->salt;
        $this->params['data']['key'] = $this->key;
        $this->params['data']['hash'] = strtolower(hash('sha512', $hash_str));
        $response = $this->cUrl();
        return $response;
    }

    private function cUrl() {
        $data = $this->params['data'] ? http_build_query($this->params['data']) : NULL;
        $url = $this->api_url;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6); //TLS 1.2 mandatory
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if ($this->params['data']) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('CURL Error: ' . $error);
            }

            if ($httpCode !== 200) {
                throw new \Exception('HTTP Error: ' . $httpCode);
            }

            return $response ? json_decode($response, true) : [];
        } catch (\Exception $e) {
            return [
                'status' => 0,
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Make getHashKey method public for external use
     */
    public function getHashKey($params) {
        // Ensure all UDF fields exist with empty defaults
        $params['udf1'] = $params['udf1'] ?? '';
        $params['udf2'] = $params['udf2'] ?? '';
        $params['udf3'] = $params['udf3'] ?? '';
        $params['udf4'] = $params['udf4'] ?? '';
        $params['udf5'] = $params['udf5'] ?? '';

        return hash('sha512', $this->key . '|' . $params['txnid'] . '|' . $params['amount'] . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|' . $params['udf1'] . '|' . $params['udf2'] . '|' . $params['udf3'] . '|' . $params['udf4'] . '|' . $params['udf5'] . '||||||' . $this->salt);
    }

    /**s
     * Generate payment URL for API integration
     */
    public function generatePaymentUrl($params) {
        // Set default values
        $params['surl'] = $params['surl'] ?? config('payu.success_url');
        $params['furl'] = $params['furl'] ?? config('payu.failure_url');
        $params['key'] = $this->key;
        $params['hash'] = $this->getHashKey($params);

        return $this->url . '?' . http_build_query($params);
    }

    /**
     * Get payment form data without HTML
     */
    public function getPaymentFormData($params) {
        // Set default values
        $params['surl'] = $params['surl'] ?? config('payu.success_url');
        $params['furl'] = $params['furl'] ?? config('payu.failure_url');
        $params['udf1'] = $params['udf1'] ?? '';
        $params['udf2'] = $params['udf2'] ?? '';
        $params['udf3'] = $params['udf3'] ?? '';
        $params['udf4'] = $params['udf4'] ?? '';
        $params['udf5'] = $params['udf5'] ?? '';

        // Generate hash
        $params['key'] = $this->key;
        $params['hash'] = $this->getHashKey($params);

        return [
            'url' => $this->url,
            'params' => $params
        ];
    }
}
