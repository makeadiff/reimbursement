# Reimbursement Portal

## Purpose

The Reimbursement Tool ensures
* Fellows/Strategists at MAD can claim for reimbursements for their expenses in Travel, Telephone or other made for MAD.
* Fellow/Strategists can upload bills to support their reimbursements requests.

## Description

The Reimbursement App is a data collection tool built on PHP Laravel Framework, which is linked to Salesforce Reimbursement App. The app acts as a platform which collects data from the user and sends relevant data to Salesforce which in turn takes care of the remaining process of Approvals and Disbursals.

## Dependencies

* Laravel
* MCrypt

## Upcoming Features

* Automation of Start/End of Reimbursement Cycle.

## Often Requested Change

### Open/close the portal on a non-standard date.

To do this, open the app/views/telephone-internet.blade.php, go to line 30, change the line from...

```blade
@if (date("d") > 7)
```

to what ever the day it should be opened/closed on.