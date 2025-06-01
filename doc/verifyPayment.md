# verifyPayment

The Verify Payment (verify_payment) API gives you the status of the transaction. PayU recommends using this API to reconcile with PayU’s database after you receive the response.

# API Command: verify_payment

# PHP

```php
    public function verifyPayment($params) {
        if(!empty($params['txnid'])){
            $transaction = $this->getTransactionByTxnId($params['txnid']);
        }
        else{
            $transaction = $this->getTransactionByPayuId($params['payuid']);
        }
        if ($transaction && $transaction['status'] == 'success') {
            return true;
        }
        return false;
    }
```

## Method Arguments

Argument |  Description
------------ | --------------------------
*txnid* | In this argument you can put all the transaction IDs, that is, txnid (your transaction ID/order ID) values separated by pipe.

## Output

**If successfully fetched**

```json
{
      "status": 1,
      "msg": "1 out of 1 Transactions Fetched Successfully",
      "transaction_details": {
            "7fa6c4783a363b3da573": {
                  "mihpayid": "403993715521889530",
                  "request_id": "",
                  "bank_ref_num": "721522",
                  "amt": "10.00",
                  "transaction_amount": "10.00",
                  "txnid": "7fa6c4783a363b3da573",
                  "additional_charges": "0.00",
                  "productinfo": "Test",
                  "firstname": "K",
                  "bankcode": "CC",
                  "udf1": "",
                  "udf3": "",
                  "udf4": "",
                  "udf5": "",
                  "field2": "177047",
                  "field9": "No Error",
                  "error_code": "E000",
                  "addedon": "2020-10-26 14:12:13",
                  "payment_source": "payu",
                  "card_type": "MAST",
                  "error_Message": "NO ERROR",
                  "net_amount_debit": 10,
                  "disc": "0.00",
                  "mode": "CC",
                  "PG_TYPE": "CC-PG",
                  "card_no": "512345XXXXXX2346",
                  "name_on_card": "Test",
                  "udf2": "",
                  "field5": "211939174867",
                  "field7": "AUTHPOSITIVE",
                  "status": "success",
                  "unmappedstatus": "captured",
                  "Merchant_UTR": null,
                  "Settled_At": "0000-00-00 00:00:00"
            }
      }
 }
```

 **If txnID not found**

```json
 {
      "status": 0,
      "msg": "0 out of 1 Transactions Fetched Successfully",
      "transaction_details": {
            "7fa6c4783a363b3da57": {
                  "mihpayid": "Not Found",
                  "status": "Not Found"
            }
      }
}
```
