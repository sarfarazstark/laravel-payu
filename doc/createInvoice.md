# createInvoice

The Create Invoice API (create_invoice) allows you to create an email invoice for your customer and provides an option to send the email invoice to the customer either immediately or later through automation.

# API Command: create_invoice

# PHP

```php
    public function createPaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['details'], 'command' => self::CREATE_INVOICE_API];
        return $this->execute();
    }
```

## Method Arguments

Argument |  Description
------------ | --------------------------
*details* | {“amount“:”10000”,”txnid“:”abaac3332″,”productinfo“:”iPhone”,”firstname“:”Samir”,”em ail“:”<test@test.com>”,”phone“:”9988776655”,”address1“:”testaddress”,”city“:”Mumbai”,”stat e“:”Maharashtra”,”country“:”India”,”zipcode“:”122002″,”template_id“:”14″,”validation_period“: 6,”send_email_now“:”1”}

## Output

**Success Scenario**

If successfully executed:

```javascript
(
          [Transaction Id] => test11390
          [Email Id] => test@gmail.com
          [Phone] => 9988776655
          [Status] => Success
[URL] => https://test.payu.in/processInvoice?invoiceId=9eec02ac9e2efc335bdda2d7486121ce03de24c2fa7d32d17462ad5a6a9058db
)
```

**Failure Scenarios**

If duplicate transaction ID is used:

Invoice for this transaction ID already exists.

If invalid parameter is sent:

Invalid <parameter>
