# check_payment

This API gives you the status of the transaction, similar to verify_payment API. The only difference is that the input parameter in this API ( var1) is the PayUID (MihpayID) generated at PayU’s end, whereas the input parameter in verify_payment API is the TxnID (Transaction ID generated at your end).

# API Command: check_payment

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
*payuid* | In this parameter, you need to pass the Payu id (mihpayid) of the transaction.

## Output

**If successfully fetched**

```json
{
      "status": 1,
      "msg": "Transaction Fetched Successfully",
      "transaction_details": {
            "request_id": "131242054",
            "bank_ref_num": "721522",
            "net_amount": null,
            "mihpayid": 403993715521889540,
            "amt": "10.00",
            "disc": "0.00",
            "mode": "CC",
            "txnid": "7fa6c4783a363b3da573",
            "amount": "10.00",
            "amount_paid": "10.00",
            "discount": "0.00",
            "additional_charges": "0.00",
            "udf1": "",
            "udf2": "",
            "udf3": "",
            "udf4": "",
            "udf5": "",
            "field1": "721522",
            "field2": "177047",
            "field3": "2056",
            "field4": "0",
            "field5": "211939174867",
            "field6": "00",
            "field7": "AUTHPOSITIVE",
            "field8": "Approved or completed successfully",
            "field9": "No Error",
            "addedon": "2020-10-26 14:12:13",
            "status": "success",
            "net_amount_debit": 10,
            "unmappedstatus": "captured",
            "firstname": "K",
            "bankcode": "CC",
            "productinfo": "Test",
            "name_on_card": "Test",
            "card_no": "512345XXXXXX2346",
            "PG_TYPE": "AXISPG",
            "Merchant_UTR": null,
            "Settled_At": null
      }
}
```

 **If mhpayid is missing**

```json
{
      "status": 0,
      "msg": "Parameter missing"
}
```
