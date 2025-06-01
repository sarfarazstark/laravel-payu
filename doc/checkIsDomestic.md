# checkIsDomestic

The checkIsDomestic method is used to detect whether a particular BIN number is international or domestic. It is also useful to determine:

    1. card’s issuing bank
    2. card type such as, Visa, Master, etc.,
    3. card category such as Credit/Debit, etc.
    4. var1 is bin number which is the first 6 digits of a Credit/Debit card.

# API Command: check_isDomestic

# PHP

    public function getCardBin($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_CARD_BIN_API];
        return $this->execute();
    }

## Method Arguments

Argument |  Description
------------ | --------------------------
*cardnum* | This parameter must contain the bank identification number(first 6 digits of a card).

## Output

**If successfully fetched**

If the card is domestic

```json
{
      "isDomestic": "Y",
      "issuingBank": "SCB",
      "cardType": "VISA",
      "cardCategory": "CC"
}
```

If the card is international

```json
{
      "isDomestic": "N",
      "issuingBank": "UNKNOWN",
      "cardType": "Unknown",
      "cardCategory": "CC"
}
```
