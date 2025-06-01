# checkActionStatus

The Check Action Status API (check_action_status) is used to check the status of the refund or cancel requests

# API Command: check_action_status


# PHP

    public function checkRefundStatus($params) {
        $this->params['data'] = ['var1' => $params['request_id'], 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

    public function checkRefundStatusByPayuId($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => 'payuid', 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

## Method Arguments

Argument | Data Type |  Description
------------ | ------------- | -------------
*request id* | ```String``` | In this parameter, you can pass the request_id of the refund transaction.
OR
*payuid* | ```String``` | This parameter must contain the Payu ID (mihpayid) of transaction.

## Output


**If successfully fetched**

{
      "status": 1,
      "msg": "1 out of 1 Transactions Fetched Successfully",
      "transaction_details": {
            "403993715521937565": {
                  "131278418": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "399900",
                        "request_id": "131278418",
                        "amt": "100.00",
                        "mode": "CC",
                        "action": "capture",
                        "token": "",
                        "status": "SUCCESS",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "-"
                  },
                  "131278422": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278422",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131278430": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278430",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131278458": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278458",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131278471": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278471",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131278484": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278484",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131278499": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278499",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131278515": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131278515",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131287648": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131287648",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131295795": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131295795",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  },
                  "131297379": {
                        "mihpayid": "403993715521937565",
                        "bank_ref_num": "527013524405",
                        "request_id": "131297379",
                        "amt": "10.00",
                        "mode": "CC",
                        "action": "refund",
                        "token": "20201105secrettokenatur",
                        "status": "success",
                        "bank_arn": null,
                        "settlement_id": null,
                        "amount_settled": null,
                        "UTR_no": null,
                        "value_date": null,
                        "refund_mode": "Back to Source"
                  }
            }
      }
}