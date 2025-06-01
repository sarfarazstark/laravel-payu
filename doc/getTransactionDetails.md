# getTransactionDetails

The Get Transaction Details (get_Transaction_Details) method takes works on basis input as two dates (initial and final), between which the transaction details are needed. The output consists of the status of the API (success or failed) and all the transaction details in an array format.

# API Command: get_Transaction_Details

For time

# API Command: get_transaction_info

# PHP

    public function getTransaction($params) {
        $command = ($params['type'] == 'time') ? self::GET_TRANSACTION_INFO_API : self::GET_TRANSACTION_DETAILS_API;
        $this->params['data'] = ['var1' => $params['from'], 'var2' => $params['to'], 'command' => $command];
        return $this->execute();
    }

## Method Arguments


Argument |  Description
------------ | --------------------------
*type* | In this argument you need to pass to specify date or time.

*from* |  This parameter must contain the starting date (from when the transaction details are needed) in YYYY-MM-DD format. For time (from when the transaction details are needed) in YYYY-MM-DD HH:MM:SS format

*to* |  This parameter must contain the end date (till when the transaction details are needed) in YYYY-MM-DD format. For time (till when the transaction details are needed) in YYYY-MM-DD HH:MM:SS format.

## Output


**If successfully fetched**

{
      "status": 1,
      "msg": "Transaction Fetched Successfully",
      "Transaction_details": [
            {
                  "id": "403993715521889443",
                  "action": "capture",
                  "status": "SUCCESS",
                  "issuing_bank": "HDFC",
                  "transaction_fee": "10.00",
                  "key": "JPM7Fg",
                  "merchantname": "demo",
                  "txnid": "02fdb4f0a0decd1e4937",
                  "firstname": "Ashish",
                  "lastname": "Kumar",
                  "addedon": "2020-10-26 13:54:52",
                  "bank_name": "Credit Card",
                  "payment_gateway": "AXISPG",
                  "phone": "9876543210",
                  "email": "ashish25@mailinator.com",
                  "amount": "10.00",
                  "discount": "0.00",
                  "additional_charges": "0.00",
                  "productinfo": "iPhone",
                  "error_code": "E000",
                  "bank_ref_no": "895255",
                  "ibibo_code": "CC",
                  "mode": "CC",
                  "ip": "106.202.49.52",
                  "card_no": "512345XXXXXX2346",
                  "cardtype": "domestic",
                  "offer_key": "",
                  "field2": "171519",
                  "udf1": "",
                  "pg_mid": null,
                  "offer_type": null,
                  "failure_reason": null,
                  "mer_service_fee": "0.00",
                  "mer_service_tax": "0.00"
            },
            {
                  "id": "403993715521889530",
                  "action": "capture",
                  "status": "SUCCESS",
                  "issuing_bank": "HDFC",
                  "transaction_fee": "10.00",
                  "key": "JPM7Fg",
                  "merchantname": "demo",
                  "txnid": "7fa6c4783a363b3da573",
                  "firstname": "K",
                  "lastname": "K",
                  "addedon": "2020-10-26 14:12:13",
                  "bank_name": "Credit Card",
                  "payment_gateway": "AXISPG",
                  "phone": "09599736876",
                  "email": "ashish.25cca@gmail.com",
                  "amount": "10.00",
                  "discount": "0.00",
                  "additional_charges": "0.00",
                  "productinfo": "Test",
                  "error_code": "E000",
                  "bank_ref_no": "721522",
                  "ibibo_code": "CC",
                  "mode": "CC",
                  "ip": "106.202.49.52",
                  "card_no": "512345XXXXXX2346",
                  "cardtype": "domestic",
                  "offer_key": "",
                  "field2": "177047",
                  "udf1": "",
                  "pg_mid": null,
                  "offer_type": null,
                  "failure_reason": null,
                  "mer_service_fee": "0.00",
                  "mer_service_tax": "0.00"
            }
      ]
}

 **Failure Scenarios- If no transactions found, the response is similar to the following:**

{
      "status": 1,
      "msg": "Transaction Fetched Successfully",
      "Transaction_details": []
}

 **If invalid date is posted**

{
      "status": 0,
      "msg": "Invalid Date Entered. Date format should be yyyy-mm-dd"
}

{
      "status": 0,
      "msg": "Invalid Date Entered. Date format should be yyyy-mm-dd hh:mm:ss"
}