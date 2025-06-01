# expireInvoice

The Expire Invoice API (expire_invoice) is used to expire an invoice link corresponding to the txnID. In few cases, an invoice might be sent to an incorrect email ID by the merchant. In such scenario, you can discard that invoice by expiring it.

# API Command: expire_invoice

# PHP

```php
    public function expirePaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['txnid'], 'command' => self::EXPIRE_INVOICE_API];
        return $this->execute();
    }
```

## Method Arguments

Argument |  Description
------------ | --------------------------
*txnid* | Specify the transaction ID in this field. *Note*: The transaction ID specified here is same as the transaction ID specified in the txnID field of the var1 parameter for Create Invoice API

## Output

**Success Scenario**

If invoice is successfully expired, and the transaction is not already in progress

```js
Array
(
[status] => 1
[msg] => Invoice expired
)
```

**Failure Scenarios**

If invoice is successfully expired, but the transaction is already in progress

```js
Array
(
[status] => 1
[msg] => Invoice expired, Transaction is already in progress
)
```

If invoice does not exist for txnID

```js
Array
(
[status] => 0
[msg] => Invoice does not exist for this txnid
)
```
