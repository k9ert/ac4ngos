to test ac4ngos, surf to the subdirectory php or copy this link to your browser:
http://ac4ngos.sf.net/php
Login is "test", password is "test".

Readme-file for the ac4ngos Application
=======================================

1. Description of the Program
ac4ngos is a financial accounting-program to track all financial movements of a non-govermental-organisation. Original it is written for the ngo CRP (Centre of the rehabilitation of the paralysed - www.crp-bangladesh.org) but it could be useful for other organisations, too. ac4ngos is under developement, which means, that it could have errors. The author is not responsible for anything which is caused from the use of this program.
Beside the financial tracking system, it is possible to enter and maintain your personals. This includes the possibility of maintaining "Lohn" and Loans to the personals.

2. Installation Notes
ac4ngos is free Software, which means, that you can use it for any purpose, make changes if you want to. But if you make changes and pass it to other people, they have the same right than you. I am convinced, that free software is the only alternative for ngos. So, ac4ngos is mainly designed, to work on a Linux operating-system but you may use it on a Windows machine as well. 
ac4ngos is a so called LAMP Application. It is used via a Webbrowser like Mozilla, Netscape Navigator or Internet Explorer. That means, that you have to install a Webserver prior to this Program. How a Webserver is installed is described in the Manual of your Linux-Distribution. That means also, that you can use the Program on a Network but in the moment, this is not recommended because of security-issues.
To install the application, type in "./install" on the command-prompt.
To use it, you have to start your Webbrowser and browse to the location "http://localhost/ac4ngos". Login as username "test" and password "test".

3. Some Informations about the use of the Program
There is no Manual up to now. So, here is a brief desription of how to use the Program.

3.1. Accounts
ac4ngos is based on the "Doppelte-Buchfuehrung". All Vouchers are double booked on (at least) two accounts. An Account consists of 4 different Codes which are called "AC-Codes" (Accounting Codes). The different Accounting Codes has the following meanings (however there is the possibility to change this meanings):
- AC-Code_1: Is used for different Projects within the NGO. This gives you the possibility to track money-transfers within one Project or via all Projects together.
- AC-Code_2: This Code is fixed, can not be changed, and have the traditional Meaning as followed:
	- LIABILITY
	- EXPENSES
	- ASSETS
	- INCOME
- AC-Code_3: This code can be freely used to group different kind of accounts. There are two special group of accounts, which are defined by having an AC-Code_3 "BANK" and "CASH". These Accounts get a special treatment. Apart from this, you can use this AC-Code as zou want to.
- AC-Code_4: This can be freely used
- AC-Code_5: Every entry here the sum of the above 4 AC-Codes, an account

3.2. Vouchers
There are 3 types of vouchers existing.
- Credit-Voucher: A Cash or Bank-Account is receiving Money. Afterwards, a money-receipt could be printed.
  A brief description of the most important fields:
  - AC_Name: Here you can choose from all Bank/Cash-Accounts. In Brackets, you can see, to which Project this account is belonging (Shortcut of AC_Code1).
  - Date-Field, right from AC_Name: Date, when the transaction is typed in, not editable
  - DR/CR: Debit/Credit, always "D" and not editable for Credit-Voucher.
  - Amount: In the moment, there is no possibilities for currencies which need a dot (like "$1.22").
  - Vr.No: The Voucher-Number for this voucher. For all type of vouchers, the same "number-loop" is used. This field is not editable. It will get filled in automatically after submitting the voucher.
  - Rcvd-From: No description available ;-)
  - Narration: Place for some more notes
  - The "Counterbookings-Fields" have the same fields than above, except the "dept"-field. Up to now, there is no report, which makes use of that field.
  - You are able to book to any account. This is not always useful and I am not lucky with it but is was a wish.
  - Normally, you will use only one account for the counterbooking but you can use also more accounts to make, what I would call a "split-booking". If you want to book to more accounts than displayed, there is the "more-rows"-button.
  - If you submit, you will receive a Vr-Number and the possibility of printing a money-receipt, however, this is only possible for Credit-Voucher.
  
- Debit-Voucher: money is withdrawn from the account
  a even briefer description of the fields:
  - Fields are exactly the same except:
    - DR/CR is always "C" for Debitvoucher
    - The Recipient-field is called "paid-to" rather than "Rcv-From".
    
- Journal-Voucher: an amount is transfered, from one account to the other. No real money is passed.
 A Brief description of the fields:
 - The narration-field is a fill-out-tool to put the same contents to all narrations-fields
 - The DR/CR field is editable and have to contain "C" or "D".
 - The total-Debit and total-Debit fields are not editable and they sum up the ammounts of all C/D-fields. They have to be the same, to apply a submit.

3.3. Reports
In the Reports-submenu, you can generate several reports:
- Ledger-sheet: gives you all transactions of a specific account within the time-frame
- Project-Balances: Gives you an opening- and closing-balances of all Bank/Cash-Accounts on a specific or all Projects within the given time-frame. The Receipts and Payments are sum-ups of accounts where movements has happened in the time-frame.
- Bank-and-Cash: Gives you the list of all Bank/Cash Accounts of all projects with their amount on a certain date.
- Project-Transactions: Gives you a list of all Vouchers of a project in a given time-frame.
- Print money-receipt: Opportunity to generate a money receipt on a given voucher-number (must be credit-voucher).
- list-personal-salary: Should list the salary of all personal, however, this is not yet working really properly. The possibility to input or edit the salary of personal is already possible in the main menu.

4. Security-issues
If you use ac4ngos as a productive system, please check the following issues:
- ac4ngos is a webapplication and can be used on an network. Because of the insufficient security-protocols, you should only do this on a secure network.
- There is an user called ac4ngos on the mysql-system with the default-password "ac4ngos". This default-password should be changed on the mysql-system (e.g. with phpMyAdmin) and in the file "accrp-settings.php" in the php-subdirectory.
- It would be more secure, if accrp-settings.php is located in a directory, which is not public accessible. There is only one reference in the code which must be adapted: top of the file "accrp.php"
- You should create proper users to login at ac4ngos. In the moment, there is no possibility to do that directly in the program, you have to use e.g. phpMyAdmin.

5. Known Bugs
- If you make an opening balance, and perform an "Project-Balance-Report" on the same day, you get a gain of money without any receipts. This only occurs, if you perform that report on the day, you made your opening balance. Maybe never fixed.
- Journal-Voucher: If you update an amount field, and click directly on the submit field, it is maybe complained "Total Debit is not equal Total Credit !". Just click once again on the submit-button. Bug has less priority.

6. ToDos:
- Possibility to delete accounts and AC-Codes
- https would be fine

7. Wishlist
- No wishes up to now

06.02.2004

Kim Neunert
