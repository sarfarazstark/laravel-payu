# getNetbankingStatus

The Get Net Banking Status API (getNetbankingStatus) is used to help you in handling the NetBanking Downtime. A few times, one or more Net Banking options may be facing downtime due to issues observed at the bank’s end. This API is used to tell the status of one or all the Net Banking options. The status can be either up or down. If you want to know the status of a specific Net Banking option, the input parameter should contain the corresponding ibibo_code. If you want to know the status of all the Net Banking options, the input parameter should contain the value as default.

# API Command: getNetbankingStatus


# PHP

    public function getTransaction($params) {
        $this->params['data'] = ['var1' => $params['netbanking_code'], 'command' => self::GET_NETBANKING_STATUS_API];
        return $this->execute();
    }

## Method Arguments


Argument |  Description
------------ | --------------------------
*netbanking_code* | This parameter must contain the bankcode of the bank or "DEFAULT" as the value.

## Output


**If successfully fetched**

{
      "ibibo_code": "AXIB",
      "title": "AXIS Bank NetBanking",
      "up_status": 0,
      "mode": "NB"
}


**To get the status of all Net Banking options pass (value “default” is passed in input)**
{
      "AXIB": {
            "ibibo_code": "AXIB",
            "title": "AXIS Bank NetBanking",
            "up_status": 0,
            "mode": "NB"
      },
      "SBIB": {
            "ibibo_code": "SBIB",
            "title": "State Bank of India",
            "up_status": 1,
            "mode": "NB"
      },
      "TESTPGNB": {
            "ibibo_code": "TESTPGNB",
            "title": "Test Net Banking",
            "up_status": 1,
            "mode": "NB"
      },
      "UPI": {
            "ibibo_code": "UPI",
            "title": "Test UPI",
            "up_status": 1,
            "mode": "UPI"
      },
      "CASH": {
            "ibibo_code": "CASH",
            "title": "Test Wallet",
            "up_status": 1,
            "mode": "CASH"
      }
}
