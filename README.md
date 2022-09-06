
# Mini Aspire API

It is an app that allows authenticated users to go through a loan application. It is fully functional REST API without any UI

The Actions is defined below:

- Customer create a loan
- Admin can approve the loan
- Customer can view loan belong to him
- Customer add a Schedule repayments
-  The API should allow  use cases, which include : creating a new customer, customer login , creating a loan, with different attributes (e.g. term and amount.),  make scheduled repayments for the loan by weekly based on term.

 
## Installation Instructions

- Run `composer install`
- Run `cp .env.example .env`
- Run `php artisan key:generate`
- Run `php artisan migrate`
- Run `php artisan db:seed`


Test Case unit can be run all or by filter.
```bash
./vendor/bin/phpunit
./vendor/bin/phpunit --filter LoginTest
./vendor/bin/phpunit --filter RegisterTest
./vendor/bin/phpunit --filter LoanTest
./vendor/bin/phpunit --filter ScheduleRepaymentTest
```
## Tables

This API uses 3 tables to operate, Users Table, Loans Table and Schedule Repayments Table
 

## API Functions

No | URL | Type |  Parameters
-----| ------------| -- |---------
1 | http://127.0.0.1:8000/api/register | POST | name: John <br> email: john@example.com <br> password: john
2 | http://127.0.0.1:8000/api/login | POST | email: john@example.com <br> password: john
3 | http://127.0.0.1:8000/api/loans | GET
4 | http://127.0.0.1:8000/api/loans/{loan} | GET | 
5 | http://127.0.0.1:8000/api/loans | POST | amount: 30000 <br> term: 3 
6 | http://127.0.0.1:8000/api/loans/{loan} | PUT | status: APPROVED
7 | http://127.0.0.1:8000/api/schedulepay/{loan}| POST | amount: 10000

## API Documentation

- [Postman Collection]

## Function Information

- Customer submit a loan request defining amount and term.he will generate scheduled repayments based on amount and term.
- the loan and scheduled repayments will have state PENDING
- Admin change the pending loans to state APPROVED
- The customers can view them own loan only
- Customer add a repayment with amount greater or equal to the scheduled repayment
- If all the scheduled repayments connected to a loan are PAID automatically also the loan become PAID
