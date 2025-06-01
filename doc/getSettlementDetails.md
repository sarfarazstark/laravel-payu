# getSettlementDetails

You can use this API to retrieve settlement details which the bank has to settle for you. The input is the date for which settlement details are required, where the var1 parameter is the date you want to know the settlement status or UTR (Unique Transaction Reference number). This API can be posted with version (1 or 2) in the var5 parameter.

# API Command: get_settlement_details


# PHP

       public function getSettlementDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_SETTLEMENT_DETAILS_API];
        return $this->execute();
    }

## Method Arguments

Argument | Data Type |  Description
------------ | ------------- | -------------
*date/UTR number* | ```String``` | This parameter must contain any of the following parameters: Date for the settlement: Date for the settlement is required is in YYYY-MM-DD format. UTR (Unique Transaction Reference number-alphanumeric) number: UTR of the transaction is required. 


## Output


**If successfully fetched**

Successful Response for Version 1

(
	    [status] => 1
	    [msg] => 1 transactions settled on 2021-08-11
	    [Txn_details] => Array
        (
			[1] => Array
                (
                    [payuid] => 13799177287
                    [txnid] => 13818
                    [txndate] => 2021-08-10 23:46:25
                    [mode] => DC
                    [amount] => 11979.88
                    [requestid] => 9586840660
                    [requestdate] => 2021-08-10 23:49:16
                    [requestaction] => capture
                    [requestamount] => 11979.88
                    [mer_utr] => N223211598444659
                    [mer_service_fee] => 239.6000
                    [mer_service_tax] => 43.1300
                    [mer_net_amount] => 11697.1500
                    [bank_name] => MAST
                    [issuing_bank] => SBI
                    [merchant_subvention_amount] => 0.00
                    [cgst] => 0.00000
                    [igst] => 43.13000
                    [sgst] => 0.00000
                    [PG_TYPE] => HDFC_Internal_Plus
                    [Card Type] => 
                    [token] => 
                )

        )

)

**If invalid date or no date found for Version 1 or Version 2**

Date format is incorrect

{
      "status": 0,
      "msg": "Please check date format it should be YYYY-MM-DD"
}

If no data found for the particular date

Array 
(
      [status] => 1
      [msg] => 0 transactions settled on 2015-05-01 
      [Txn_details] => Array
                (
                 ) 
)