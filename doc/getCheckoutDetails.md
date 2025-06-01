# getCheckoutDetails

The **get\_checkout\_details** API is a generic API using which they can get information when you create the custom checkout-pages, that will contain the payment options, offers, recommendations, and downtime details. The API provides the following details:

* **Payment option details**: The extended details for each payment option available for the merchant.
* **Additional charges**: The additional charges configured for all payment options.
* eligibility details
* **Downtime details**: The downtime status of the payment options.

# API Command: get_checkout_details

# PHP

```php
    public function getCheckoutDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_CHECKOUT_DETAILS_API];
        return $this->execute();
    }
```

## Method Arguments

| Parameter | Data type | Example |
| --- | --- | --- |
| var1 | JSON | {"requestId":"1","transactionDetails":{"amount":"28900"},"customerDetails":{"mobile":"9999999999"},"filters":{"paymentOptions":{"emi":{"dc":"all"}}},"useCase":{"checkCustomerEligibility":true}} |

## Output

**If successfully fetched**

```json
{
  "status": 1,
  "details": {
    "paymentOptions": {
      "emi": {
        "all": {
          "dc": {
            "hasEligible": true,
            "all": {
              // Key is the 4 letter IFSC initials of the banks.
              "UTIB": {
                "title": "Axis Bank",
                "shortName": "Axis",
                // Minimum amount for this bank.
                "minimumAmount": 1000,
                // Maximum amount for this bank, null means no-limit.
                "maximumAmount": null,
                "eligibility": {"status": true},
                "tenureOptions": {
                  // Key name is the value of bankcode accepted by PayU.
                  "AXISD03": {
                    "tenure": 3,
                    "interestRate": 10.5,
                    "interestCharged": 200.45,
                    "monthlyEmi": 400.5,
                    "minimumAmount": 1000,
                    "maximumAmount": null,
                    "eligibility": {"status": true},
                  },
                  "AXISD...": { "...": "..." }
                }
              },
              "HDFC": { "...": "..." }
            },
            // Least amount limit of any dc emi.
            "minimumAmount": 1000,
            // Highest amount limit of any dc emi, null means no limit.
            "maximumAmount": null
          },
          "cc": { "...": "..." },
          "others": { "...": "..." },
          "cardless": {
            "hasEligible": true,
            "all": {
              "ZESTMON": {
                "title": "Zest Money",
                "shortName": "ZestMoney",
                "minimumAmount": 1000,
                "maximumAmount": null,
                "tenureOptions": {
                  "ZESTMON": {
                    // Tenure field will be all in case tenures of an option
                    // not managed on PayU end.
                    "tenure": null,
                    "minimumAmount": 1000,
                    "maximumAmount": null,
                    "eligibility": {"status": true},
                    // interestRate, interestCharged, monthlyEmi, etc may/may
                    // not be present depending whether these are maintained
                    // at payu's end or not. Eg ZESTMON's tenures are maintained
                    // on the bank end only and are returned once the customer
                    // proceeds with this option and submits the OTP.
                  }
                }
              },
              "...": { "...": "..." }
            }
          }
        }
      },
      "nb": {
        "all": {
          // Key name is the value of bankcode accepted by PayU.
          "SBIB": {
            "title": "State Bank of India"
          },
          "ADBB": {
            "title": "Andhra Bank"
          },
          "AXIB": {
            "title": "AXIS Bank NetBanking"
          },
          "AXNBTPV": {
            "title": "Axis NB TPV"
          },
          "...": { "...": "..." }
        }
      },
      "si": {
        "all": {
          "ANDBENCR": {
            "title": "Andhra Bank Recurring"
          },
          "AUBLENCR": {
            "title": "AU Small Finance Bank Ltd Recurring"
          },
          "UTIBENCR": {
            "title": "AXIS BANK Recurring"
          },
          "BARBENCR": {
            "title": "BARB ENACH Recurring"
          },
          "...": { "...": "..." }
        }
      },
      "dc": {
        "all": {
          "MAST": {
            "title": "MasterCard Debit Cards"
          },
          "MASTTPV": {
            "title": "MasterCard TPV Debit Cards"
          },
          "SMAE": {
            "title": "State Bank Maestro Cards"
          },
          "MAES": {
            "title": "Other Maestro Cards"
          },
          "RUPAY": {
            "title": "Rupay Debit Card"
          },
          "...": { "...": "..." }
        }
      },
      "cc": {
        "all": {
          "CC": {
            "title": "Credit Card"
          },
          "DINR": {
            "title": "Diners"
          },
          "RUPAYCC": {
            "title": "Rupay Credit Card"
          },
          "...": { "...": "..." }
        }
      },
      "lazypay": {
        "all": {
          "LAZYPAY": {
            "title": "LazyPay"
          }
        }
      },
      "lp-emi": {
        "all": {
          "LP-EMI": {
            "title": "LAZYPAYEMI"
          }
        }
      },
      "cash": {
        "all": {
          "PAYTM": {
            "title": "Paytm"
          },
          "...": { "...": "..." }
        }
      },
      // Similarly all the modes & payment options that are available for
      // the merchant.
      "...": { "...": "..." }
    }
  }
}
{
  "status": 1,
  "details": {
    "paymentOptions": {
      "emi": {
        "all": {
          "dc": {
            "hasEligible": true,
            "all": {
              // Key is the 4 letter IFSC initials of the banks.
              "UTIB": {
                "title": "Axis Bank",
                "shortName": "Axis",
                // Minimum amount for this bank.
                "minimumAmount": 1000,
                // Maximum amount for this bank, null means no-limit.
                "maximumAmount": null,
                "eligibility": {"status": true},
                "tenureOptions": {
                  // Key name is the value of bankcode accepted by PayU.
                  "AXISD03": {
                    "tenure": 3,
                    "interestRate": 10.5,
                    "interestCharged": 200.45,
                    "monthlyEmi": 400.5,
                    "minimumAmount": 1000,
                    "maximumAmount": null,
                    "eligibility": {"status": true},
                  },
                  "AXISD...": { "...": "..." }
                }
              },
              "HDFC": { "...": "..." }
            },
            // Least amount limit of any dc emi.
            "minimumAmount": 1000,
            // Highest amount limit of any dc emi, null means no limit.
            "maximumAmount": null
          },
          "cc": { "...": "..." },
          "others": { "...": "..." },
          "cardless": {
            "hasEligible": true,
            "all": {
              "ZESTMON": {
                "title": "Zest Money",
                "shortName": "ZestMoney",
                "minimumAmount": 1000,
                "maximumAmount": null,
                "tenureOptions": {
                  "ZESTMON": {
                    // Tenure field will be all in case tenures of an option
                    // not managed on PayU end.
                    "tenure": null,
                    "minimumAmount": 1000,
                    "maximumAmount": null,
                    "eligibility": {"status": true},
                    // interestRate, interestCharged, monthlyEmi, etc may/may
                    // not be present depending whether these are maintained
                    // at payu's end or not. Eg ZESTMON's tenures are maintained
                    // on the bank end only and are returned once the customer
                    // proceeds with this option and submits the OTP.
                  }
                }
              },
              "...": { "...": "..." }
            }
          }
        }
      },
      "nb": {
        "all": {
          // Key name is the value of bankcode accepted by PayU.
          "SBIB": {
            "title": "State Bank of India"
          },
          "ADBB": {
            "title": "Andhra Bank"
          },
          "AXIB": {
            "title": "AXIS Bank NetBanking"
          },
          "AXNBTPV": {
            "title": "Axis NB TPV"
          },
          "...": { "...": "..." }
        }
      },
      "si": {
        "all": {
          "ANDBENCR": {
            "title": "Andhra Bank Recurring"
          },
          "AUBLENCR": {
            "title": "AU Small Finance Bank Ltd Recurring"
          },
          "UTIBENCR": {
            "title": "AXIS BANK Recurring"
          },
          "BARBENCR": {
            "title": "BARB ENACH Recurring"
          },
          "...": { "...": "..." }
        }
      },
      "dc": {
        "all": {
          "MAST": {
            "title": "MasterCard Debit Cards"
          },
          "MASTTPV": {
            "title": "MasterCard TPV Debit Cards"
          },
          "SMAE": {
            "title": "State Bank Maestro Cards"
          },
          "MAES": {
            "title": "Other Maestro Cards"
          },
          "RUPAY": {
            "title": "Rupay Debit Card"
          },
          "...": { "...": "..." }
        }
      },
      "cc": {
        "all": {
          "CC": {
            "title": "Credit Card"
          },
          "DINR": {
            "title": "Diners"
          },
          "RUPAYCC": {
            "title": "Rupay Credit Card"
          },
          "...": { "...": "..." }
        }
      },
      "lazypay": {
        "all": {
          "LAZYPAY": {
            "title": "LazyPay"
          }
        }
      },
      "lp-emi": {
        "all": {
          "LP-EMI": {
            "title": "LAZYPAYEMI"
          }
        }
      },
      "cash": {
        "all": {
          "PAYTM": {
            "title": "Paytm"
          },
          "...": { "...": "..." }
        }
      },
      // Similarly all the modes & payment options that are available for
      // the merchant.
      "...": { "...": "..." }
    }
  }
}
```

Get Additional Charges
Sample Request
Curl

```json
{
    "requestId": "12345678",
    "transactionDetails": {
      "amount": 12345.12
    },
    "useCase": {
      "getAdditionalCharges": true
    }
  }
  ```

Sample Response

```json
{
  "status": 1,
  "details": {
    "paymentOptions": {
      "emi": {
        "all": {
          "dc": {
            "all": {
              "UTIB": {
                "tenureOptions": {
                  "AXISD03": {
                    "additionalCharge": 13.37
                  },
                  "AXISD...": { "...": "..." }
                }
              },
              "...": { "...": "..." }
            }
          },
          "...": { "...": "..." }
        }
      },
      "nb": {
        "all": {
          "SBIB": {
            "additionalCharge": 0
          },
          "ADBB": {
            "additionalCharge": 0
          },
          "AXIB": {
            "additionalCharge": 0
          },
          "AXNBTPV": {
            "additionalCharge": 0
          },
          "...": { "...": "..." }
        }
      },
      "dc": {
        "all": {
          "MAST": {
            "additionalCharge": 5.0
          },
          "MASTTPV": {
            "additionalCharge": 5.0
          },
          "SMAE": {
            "additionalCharge": 5.0
          },
          "MAES": {
            "additionalCharge": 5.0
          },
          "RUPAY": {
            "additionalCharge": 5.0
          },
          "...": { "...": "..." }
        }
      },
      "cc": {
        "all": {
          "CC": {
            "additionalCharge": 5.0
          },
          "DINR": {
            "additionalCharge": 5.0
          },
          "RUPAYCC": {
            "additionalCharge": 5.0
          },
          "...": { "...": "..." }
        }
      },
      "cash": {
        "all": {
          "PAYTM": {
            "additionalCharge": 10.5
          },
          "...": { "...": "..." }
        }
      },
      // Similarly all the modes & payment options that are available for
      // the merchant.
      "...": { "...": "..." }
    }
  }
}
```

Get Tax Specification
Sample Request
Curl

```json
{
  // Mandatory field, random id for debugging purposes only
  "requestId": "12345678",
  "transactionDetails": {
    // Mandatory field
    "amount": 12345.12
  },
  "useCase": {
    // Down Banks info will be returned only if this flag is true.
    "getTaxSpecification": true
  }
}
```

Sample Response

```json
{
  "status": 1,
  "details": {
    // No change in the payment options returned or any other internal field
    // due to the checkDownStatus flag.
    // These will remain as it is as the remaining responses.
    "paymentOptions": {
      "cc": { "...": "..." },
      "dc": { "...": "..." },
      "...": { "...": "..." }
    },
    "config": {
      // This object will be returned if getTaxSpecification flag is true.
      // Default is the one to be applied on all modes.
      // In special cases, this can also have mode level tax percent
      "taxSpecification": {
        "default": 18
      }
    }
  }
}
```

Check Down Status
Sample Request
Curl

```json
{
  // Mandatory field, random id for debugging purposes only
  "requestId": "12345678",
  "transactionDetails": {
    // Mandatory field
    "amount": 12345.12
  },
  "useCase": {
    // Down Banks info will be returned only if this flag is true.
    "checkDownStatus": true
  }
}
```

Sample Response

```json
{
  "status": 1,
  "details": {
    // No change in the payment options returned or any other internal field
    // due to the checkDownStatus flag.
    // These will remain as it is as the remaining responses.
    "paymentOptions": {
      "cc": { "...": "..." },
      "dc": { "...": "..." },
      "nb": { "...": "..." },
      "emi": { "...": "..." },
      "upi": { "...": "..." },
      "cash": { "...": "..." }
    },
    // This object will be returned if checkDownStatus flag is true.
    "downInfo": {
      // issuingBank contains the list of down issuing banks for the cards
      "issuingBanks": ["HDFC", "AXIS", "ICICI"],
      // nb/cashcard/etc all the other keys in this object contains the list of
      // down ibibo codes corresponding to the modes. The remaing keys will the
      // same as the ones present in the "paymentOptions" object
      "nb": ["SBIB", "ANDB"],
      "cash": ["PAYTM", "YESW"],
      "...": ["..."]
    }
  }
}
```
