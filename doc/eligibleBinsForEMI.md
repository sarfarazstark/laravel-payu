# eligibleBinsForEMI

The Eligible Bin for EMI API (eligibleBinsForEMI) is used only when the merchant needs the EMI feature of PayU. If you are managing card details on your website, this API can tell the issuing bank of the card bin. It also provides the minimum eligible amount for a particular bank.

# API Command: eligibleBinsForEMI


# PHP

    public function checkEligibleEMIBins($params) {
        $this->params['data'] = ['var1' => $params['bin'], 'var2' => $params['card_num'], 'var3' => $params['bank_name'], 'command' => self::CHECK_ELIGIBLE_BIN_FOR_EMI_API];
        return $this->execute();
    }

## Method Arguments

Argument |  Description
------------ | --------------------------
*bin* | This parameter needs can include any of the following the values:

bin: If you want to check on the basis of the first 6/8/9 digits of card number or network token.
NET: If you want to check on the basis of network token.

*card_num* | This parameter can contain any of the following-

If bin used in var1 parameter, the first 6/8/9 digits of card number or network token.
If NET used in the var1 parameter, the entire network token must be passed.

*bank_name* | This parameter must include the bank name.


## Output


**If successfully fetched**

Array
(
    [status] => 1
    [msg] => Details fetched successfully
    [details] => Array
        (
            [isEligible] => 1
            [bank] => AXIS
            [minAmount] => 2500
        )

)

Array (
[status] => 0
[msg] => Invalid Bin )