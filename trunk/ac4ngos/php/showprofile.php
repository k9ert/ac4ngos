<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection(); 
if ($CustomerID) {
	if(intval($CustomerID) == 0) {
		beginPrettyTable("2", "Customer");
		die ("$CustomerID is not a valid Customer ID.\n");
		endPrettyTable();
	}
	

	$customer_result = mysql_query("SELECT * from Customers where CustomerID=$CustomerID", $db);
	checkMySQLError();
	$customer_row = mysql_fetch_array($customer_result);
	# Check to see if this person exists
	if($customer_row == "") {
		beginPrettyTable("2", "Customer");
		die ("Customer $CustomerID not found.\n");
		endPrettyTable();
	}
	
	$account_result = mysql_query("SELECT * FROM Accounts WHERE CustomerID=$CustomerID", $db);
	checkMySQLError();
	$account_row = mysql_fetch_array($account_result);

	$payment_result = mysql_query("SELECT * FROM Payments WHERE CustomerID=$CustomerID ORDER BY PaymentID DESC", $db);
	checkMySQLError();
	$payment_row = mysql_fetch_array($payment_result);

	$invoice_result = mysql_query("SELECT * FROM Invoices WHERE CustomerID=$CustomerID ORDER BY InvoiceID DESC", $db);
	checkMySQLError();
	$invoice_row = mysql_fetch_array($invoice_result);

	$ticket_result = mysql_query("SELECT * FROM Tickets WHERE CustomerID=$CustomerID ORDER BY TicketID DESC", $db);
	checkMySQLError();
	$ticket_row = mysql_fetch_array($ticket_result);

	$config_result = mysql_query("SELECT * FROM Configuration");
	checkMySQLError();
	$config_row = mysql_fetch_array($config_result);

	echo "<table cellpadding=0 cellspacing=0 border=0>\n";
	if($config_row["SearchBar"] == "On") {
		echo "<tr>\n";
		echo " <td align=left colspan=2>\n";
		searchBar();
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr><td><br></td></tr>\n";
	}
	echo " <tr>\n  <td width=24 valign=top>\n";
	echo "<table width=24 cellpadding=0 cellspacing=0 border=0>\n <tr>\n  <td align='left' valign='top'>\n";

	beginPrettyTable("1");
	echo "<tr>\n";
	printf(" <td><a href='edit.php?CustomerID=%s'><img src='images/edit.gif' width=24 height=24 border=0 alt='Edit this Customer'></a></td>\n", $customer_row["CustomerID"]);
	echo "</tr>\n<tr>\n";
	printf(" <td><hr color='black' width=24></td>\n");
	echo "</tr>\n<tr>\n";
	printf(" <td><a href='enteraccount.php?CustomerID=%s'><img src='images/add_account.gif' width=24 height=24 border=0 alt='$lEnterAccount'></a></td>\n", $customer_row["CustomerID"]);
	echo "</tr>\n<tr>\n";
	printf(" <td><a href='enterpayment.php?CustomerID=%s'><img src='images/add_payment.gif' width=24 height=24 border=0 alt='$lEnterPayment'></a></td>\n", $customer_row["CustomerID"]);
	echo "</tr>\n<tr>\n";
	printf(" <td><a href='enterinvoice.php?CustomerID=%s'><img src='images/add_invoice.gif' width=24 height=24 border=0 alt='$lEnterInvoice'></a></td>\n", $customer_row["CustomerID"]);
	echo "</tr>\n<tr>\n";
	printf(" <td><a href='enterticket.php?CustomerID=%s'><img src='images/add_ticket.gif' width=24 height=24 border=0 alt='$lEnterTicket'></a></td>\n", $customer_row["CustomerID"]);
	echo "</tr>\n<tr>\n";
	echo " <td><hr color='black' width='24'></td>\n";
	echo "</tr>\n<tr>\n";
	printf(" <td><a href='confirm.php?action=sendbill&CustomerID=%s'><img src='images/sendbill.gif' width=24 height=24 border=0 alt='$lSendBill'><a/></td>\n", $customer_row["CustomerID"]);
	echo "</tr>\n<tr>\n";
	echo " <td><hr color='black' width='24'></td>\n";
	echo "</tr>\n<tr>\n";
	echo " <td><a href='showprofile.php'><img src='images/list.gif' width=24 height=24 border=0 alt='List Customers'></a></td>\n";
	echo "</tr>\n<tr>\n";
	printf(" <td><a href='$PHP_SELF?CustomerID=%s'><img src='images/refresh.gif' width=24 height=24 border=0 alt='Refresh'></a></td>\n", $customer_row["CustomerID"]);
	echo "</tr> <tr>\n";
	echo " <td><hr color='black' width='24'></td>\n";
	echo "</tr>\n<tr>\n";
	echo " <td><a href='index.php'><img src='images/logout.gif' width=24 height=24 border=0 alt='Logout'></a></td>\n";
	echo "</tr>\n";
	endPrettyTable();

	echo "  </td>  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> </tr>  </table>\n";

	echo "  </td> <td valign='top'>\n";

	echo ("<table cellpadding=0 cellspacing=0 border=0>\n");
	echo (" <tr>\n");
	echo ("  <td valign=top>\n");

	$name = $customer_row["First"]." ";
	if($customer_row["Mid"] != "") {
		$name = $name.$customer_row["Mid"].". ";
	}
	$name = $name.$customer_row["Last"];
	$customer_thing = $customer_row["CustomerID"]." : ".$name;
	$second_address = $customer_row["City"].", ".$customer_row["State"]."  ".$customer_row["Zip"];
	beginPrettyTable("2", $customer_thing);
	if($customer_row["Company"] != "") {
		makeStaticField($lCompany, $customer_row["Company"]);
	}
	makeStaticField($lAddress, $customer_row["Address"]);
	# Silly little hack to add a second line to a info display, I haven't
	# had to do this anywhere but here, so I wront write a function for it
	echo "<tr><td>&nbsp</td><td><div class='data'>$second_address</div></td></tr>\n";
	makeStaticField($lPhone, $customer_row["Telephone"]);
	if(strlen($customer_row["Fax"]) >= 5) {
		makeStaticField($lFax, $customer_row["Fax"]);
	}
	if(strlen($customer_row["CCNumber"]) >= 16) {
		makeStaticField($lCCNumber, $customer_row["CCNumber"]);
	}
	makeStaticField($lEmail, "<a href=\"mailto:".$customer_row["Email"]."\" class='mailto'>".$customer_row["Email"]."</a>");
	endPrettyTable();

	echo ("  </td>\n");
	echo ("  <td>&nbsp;</td>\n");
	echo ("  <td valign=top>\n");

	$affectedcount = mysql_num_rows($account_result);
	beginPrettyTable("7", "$affectedcount $lAccounts");
	beginBorderedTable("7");
	if ($account_row) {
	  echo ("    <tr>\n");
	  echo ("     <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lStatus</b></td>  <td><b>$lCharged</b></td><td><b>$lMonthsRunning</b></td><td><b>$lTotalPrice</b></td><td><b>$lActions</b></td>\n");
	  echo ("    </tr>\n");
	  do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
	    $running_months = get_running_months($account_row["DateOpened"], $account_row["DateClosed"], $config_row["SameMonth"], $config_row["Anniversary"]);
	    $total_price = get_total_price($account_row["Price"], $account_row["TaxRate"], $account_row["Charged"], $running_months, $config_row["BillAhead"]);
	    $accounts_total += $total_price;
	    printf ("<tr class='$class'> <td align='center'><a href='showaccounts.php?AccountID=%s&CustomerID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%.2f</td> <td align='center'><a href='edit.php?AccountID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Account'></a><a href='delete.php?AccountID=%s&CustomerID=%s'><img src='images/delete.gif' border=0></a></td></tr>\n", $account_row["AccountID"], $account_row["CustomerID"], $account_row["AccountID"], $account_row["Description"], $account_row["Status"], $account_row["Charged"], $running_months, $total_price, $account_row["AccountID"], $account_row["AccountID"], $account_row["CustomerID"]);
	  } while($account_row = mysql_fetch_array($account_result));
		$class = "";
	  printf("    <tr>   <td colspan=7 align=right><b>$lTotal: %.2f</b></td>  </tr>\n", $accounts_total);
	} else {
	  echo ("    <tr><td>$lNoAccountsFound<td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();

	echo ("  </td>\n");
	echo (" </tr>\n");
	echo ("</table>\n");
	echo ("<br>\n");
	echo ("<table cellpadding=0 cellspacing=0 border=0>\n");
	echo (" <tr>\n");
	echo ("  <td valign=top>\n");
	
	$payment_count = mysql_num_rows($payment_result);
	beginPrettyTable("5", "$payment_count $lPayments");
	beginBorderedTable("5");
	if ($payment_row) {
		echo ("    <tr>\n");
		echo ("     <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDatePaid</b></td> <td><b>$lType</b></td> <td><b>$lAmount</b></td>  <td><b>$lActions</b></td>\n");
		echo ("    </tr>\n");
		# Unless our display mode is set to long, lets only display the
		# most recent payments.
		if(($payment_count > 5) && ($mode != "long")) {
			$pay_abridged = 1;
			do {
				if($pay_counter < 5) {
					if($class == "odd") { $class = "even"; } else { $class = "odd"; }
					printf("<tr class='$class'><td align='center'><a href='showpayments.php?PaymentID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?PaymentID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Payment'></a> <a href='delete.php?PaymentID=%s&CustomerID=%s'><img src='images/delete.gif' border=0 alt='Delete this Payment'></a></td>  </tr>\n", $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["DatePaid"], $payment_row["Type"], $payment_row["Amount"], $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["CustomerID"]); 
				}
				$pay_counter++;
				$payment_total += $payment_row["Amount"];
			} while($payment_row = mysql_fetch_array($payment_result)); 
		} else {
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf("    <tr class='$class'><td align='center'><a href='showpayments.php?PaymentID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?PaymentID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Payment'></a> <a href='delete.php?PaymentID=%s&CustomerID=%s'><img src='images/delete.gif' border=0 alt='Delete this Payment'></a></td>  </tr>\n", $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["DatePaid"], $payment_row["Type"], $payment_row["Amount"], $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["CustomerID"]); 
				$payment_total += $payment_row["Amount"];
			} while($payment_row = mysql_fetch_array($payment_result));
			$class = "";
		}
		printf("    <tr>   <td colspan=5 align=right><b>$lTotal: %.2f</b></td>  </tr>\n", $payment_total);
	} else {
		echo ("<tr><td>$lNoPaymentsFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo ("  </td>\n");
	echo ("<td>&nbsp;</td>\n");
	echo ("  <td valign=top>\n");

	$invoice_count = mysql_num_rows($invoice_result);
	beginPrettyTable("5", "$invoice_count $lInvoices");
	beginBorderedTable("5");
	if ($invoice_row) {
		echo ("    <tr>\n");
		echo ("     <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lDateBilled</b></td> <td><b>$lAmount</b></td>  <td><b>$lActions</b></td>\n");
		echo ("    </tr>\n");
		# Unless our display mode is set to long, lets only display the
		# most recent payments.
		if(($invoice_count > 5) && ($mode != "long")) {
			$inv_abridged = 1;
			do {
				if($inv_counter < 5) {
					if($class == "odd") { $class = "even"; } else { $class = "odd"; }
					printf("    <tr class='$class'><td align='center'><a href='showinvoices.php?InvoiceID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?InvoiceID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Invoice'></a> <a href='delete.php?InvoiceID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Invoice'></a></td>\n    </tr>\n", $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["Description"], $invoice_row["DateBilled"], $invoice_row["Amount"], $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["CustomerID"]); 
				}
				$inv_counter++;
				$invoice_total += $invoice_row["Amount"];
			} while($invoice_row = mysql_fetch_array($invoice_result));
		} else {
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf("    <tr class='$class'><td align='center'><a href='showinvoices.php?InvoiceID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?InvoiceID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Invoice'></a> <a href='delete.php?InvoiceID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Invoice'></a></td>\n    </tr>\n", $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["Description"], $invoice_row["DateBilled"], $invoice_row["Amount"], $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["CustomerID"]); 
				$invoice_total += $invoice_row["Amount"];
			} while($invoice_row = mysql_fetch_array($invoice_result));
			$class = "";
		}
		printf("    <tr>   <td colspan=5 align=right><b>$lTotal: %.2f</b></td>  </tr>\n", $invoice_total);
	} else {
		echo ("<tr><td>$lNoInvoicesFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo ("  </td>\n");
	echo (" </tr>\n");
	echo ("</table>\n");
		
	echo ("<br>\n");
	
	$balance = $payment_total - ($invoice_total + $accounts_total);
	$balance = sprintf("%.2f", $balance);
	$balance_result = mysql_query("UPDATE Customers SET Balance=$balance where CustomerID=$CustomerID", $db);
	if ($balance >= 0) { $bal_color = "positive"; } else { $bal_color = "negative"; } 
	beginPrettyTable("1", "$lBalance");
  echo("<tr> <td width=100 align=center><div class=$bal_color>\$ $balance&nbsp;&nbsp;&nbsp;</div></td> </tr>\n");
	endPrettyTable();

	echo "<br>\n";
	
	$ticketcount = mysql_num_rows($ticket_result);
	beginPrettyTable("4", "$ticketcount $lTickets");
	beginBorderedTable("4");
	if ($ticket_row) {
		echo ("    <tr>\n");
		echo ("     <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lStatus</b></td> <td><b>$lDateOpened</b></td> <td><b>$lActions</b></td>\n");
		echo ("    </tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf("    <tr class='$class'><td align='center'><a href='showtickets.php?TicketID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?TicketID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Ticket'></a> <a href='delete.php?TicketID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Ticket'></a></td>\n    </tr>\n", $ticket_row["TicketID"], $ticket_row["TicketID"], $ticket_row["Status"], $ticket_row["OpenDate"], $ticket_row["TicketID"], $ticket_row["TicketID"], $ticket_row["CustomerID"]); 
		} while ($ticket_row = mysql_fetch_array($ticket_result));
		$class = "";
	} else {
		echo ("$lNoTicketsFound\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo "<br>\n";
	if($pay_abridged == 1) {
		if($inv_abridged == 1) {
			$abridged = "$lPayments and $lInvoices";
		} else {
			$abridged = "$lPayments";
		}
	} else if($inv_abridged == 1) {
		$abridged = "$lInvoices";
	}
	if($abridged != "") {
		echo "<div class='data'>The number of $abridged was abridged for readability, go <a href='showprofile.php?CustomerID=$CustomerID&mode=long'>here</a> to see them all.</div>\n";
	}
	echo ("  </td>\n </tr>\n </table>");
} else {
	$lastditch_result = mysql_query("select * from Customers order by CustomerID", $db);
	if ($lastditch_row = mysql_fetch_array($lastditch_result)) {
		beginPrettyTable("4", "$lCustomers");
		beginBorderedTable("4");
		echo ("<tr>\n");
		echo (" <td><b>$lCustomerID</b></td> <td><b>$lName</b></td> <td><b>$lAddress</b></td>\n <td><b>$lActions</b></td>\n </tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf(" <tr class='$class'>\n  <td><a href='%s?CustomerID=%s'>%s</td> <td>%s, %s</td>\n <td>%s</td> <td align='center'><a href='confirm.php?action=deletecustomer&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Customer'></a></td></tr>\n", $PHP_SELF, $lastditch_row["CustomerID"], $lastditch_row["CustomerID"], $lastditch_row["Last"], $lastditch_row["First"], $lastditch_row["Address"], $lastditch_row["CustomerID"]); 
		} while ($lastditch_row = mysql_fetch_array($lastditch_result));
		endPrettyTable();
		endBorderedTable();
	} else {
		echo ("$lNoProfilesFound...\n"); 
	}
}
endDocument();
?>
