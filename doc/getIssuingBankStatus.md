# getIssuingBankStatus

The Get Issuing Bank Status API (getIssuingBankStatus) is used to help you handle the credit card or debit card issuing bank downtime.

# API Command: getIssuingBankStatus

# PHP

```php
    public function getIssuingBankStatus($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_ISSUING_BANK_STATUS_API];
        return $this->execute();
    }
```

## Method Arguments

Argument |  Description
------------ | --------------------------
*cardnum* | This parameter must contain the bank identification number(first 6 digits of a card).

## Output

**If successfully fetched**

```json
{
      "issuing_bank": "HDFC",
      "up_status": "1"
}
```
