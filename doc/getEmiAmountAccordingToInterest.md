# getEmiAmountAccordingToInterest

The Get EMI Amount According to Interest API (getEmiAmountAccordingToInterest) is used to get the EMI interest bank rates for all the enabled EMIs.

# API Command: getEmiAmountAccordingToInterest


# PHP

    public function getEmiAmount($params) {
        $this->params['data'] = ['var1' => $params['amount'], 'command' => self::GET_EMI_AMOUNT_ACCORDING_TO_INTEREST_API];
        return $this->execute();
    }

## Method Arguments


Argument |  Description
------------ | --------------------------
*amount* | The amount that must be converted to EMI.


## Output


**Merchant Hosted Checkout EMI Response**

{
      "7": {
            "EMIA3": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 6666.67,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 13,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 6666.67,
                  "card_type": "credit card",
                  "emi_value": 6811.63,
                  "emi_interest_paid": 434.89,
                  "tenure": "03 months"
            },
            "EMIA6": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 3333.33,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 13,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 3333.33,
                  "card_type": "credit card",
                  "emi_value": 3460.86,
                  "emi_interest_paid": 765.14,
                  "tenure": "06 months"
            },
            "EMIA9": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 2222.22,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 14,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 2222.22,
                  "card_type": "credit card",
                  "emi_value": 2353.86,
                  "emi_interest_paid": 1184.71,
                  "tenure": "09 months"
            },
            "EMIA12": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 1666.67,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 14,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 1666.67,
                  "card_type": "credit card",
                  "emi_value": 1795.74,
                  "emi_interest_paid": 1548.91,
                  "tenure": "12 months"
            }
      },
      "AXISD": {
            "AXISD03": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 6666.67,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 14,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 6666.67,
                  "card_type": "debit card",
                  "emi_value": 6822.82,
                  "emi_interest_paid": 468.47,
                  "tenure": "03 months"
            },
            "AXISD06": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 3333.33,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 14,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 3333.33,
                  "card_type": "debit card",
                  "emi_value": 3470.76,
                  "emi_interest_paid": 824.56,
                  "tenure": "06 months"
            },
            "AXISD09": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 2222.22,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 16,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 2222.22,
                  "card_type": "debit card",
                  "emi_value": 2372.99,
                  "emi_interest_paid": 1356.87,
                  "tenure": "09 months"
            },
            "AXISD12": {
                  "transactionAmount": 20000,
                  "paybackAmount": 0,
                  "loanAmount": 20000,
                  "emiAmount": 1666.67,
                  "additionalCost": "0.00",
                  "emiMdrNote": null,
                  "emiBankInterest": 16,
                  "bankRate": null,
                  "bankCharge": 0,
                  "amount": 1666.67,
                  "card_type": "debit card",
                  "emi_value": 1814.62,
                  "emi_interest_paid": 1775.41,
                  "tenure": "12 months"
            }
      }
}