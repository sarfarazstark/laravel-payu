# check_payment

This method will let you validate VPA if it is a valid VPA or not.

After the customer enters VPA on the merchant page, you need to call this API to check for VPA validation. If VPA is valid only then, the second call should be made.

# API Command: validateVPA

# PHP

    public function validateUpi($params) {
        $this->params['data'] = ['var1' => $params['vpa'], 'var2' => $params['auto_pay_vpa'], 'command' => self::VALIDATE_UPI_HANLE_API];
        return $this->execute();
    }


## Method Arguments


Argument |  Description
------------ | --------------------------
*vpa* | In this argument you need to pass the customer's VPA.

*auto_pay_vpa* | This parameter is used to check if the VPA is valid for Autopay or UPI Standing Instructions or not. This will just check whether the handle passed is supporting UPI Autopay or not. You need pass the values as 1 in the validateAutoPayVPA field (Optional).

## Output


**Success Scenarios- Valid VPA**

{
   "status":"SUCCESS",
   "vpa":"9999999999@upi",
   "isVPAValid":1,
   "isAutoPayVPAValid":1,
   "isAutoPayBankValid":"NA",
   "payerAccountName":"ABC"
}

 **Failure Scenarios- Invalid VPA:**

{"status":"SUCCESS","vpa":""abc@upi","isVPAValid":0,"payerAccountName":"NA"}  

 **Invalid VPA but handle supporting SI (Autopay):**

{"status":"SUCCESS","vpa":""abc@upi","isVPAValid":0,"isAutoPayVPAValid":1,"isAutoPayBa
nkValid":NA,"payerAccountName":"NA"}