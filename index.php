
 <?php
	include($_SERVER['DOCUMENT_ROOT']."/htaccess.php");
	include($_SERVER['DOCUMENT_ROOT']."/$omenNX/var.php");
	
 ?>
<title>

	</title>
	
   
	
	<!-- Pushy Menu -->
<nav class="pushy pushy-left">
    <div class="pushy-content">
        <ul>
            <!-- Submenu -->
            <li class="pushy-submenu">
                <button>Customers</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$omenNX?>/customers/customerAdd.php">Customers Add</a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/customers/customerList.php">Customers List</a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/customers/CustomerStatement.php">Customers Statement</a></li>
					
					
					
					
				</ul>
            </li>
			
			<li class="pushy-submenu">
                <button>Items</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$stockManager?>/items/listGeneralizedItems.php">Items List</a></li>
					<li class="pushy-link"><a href="/<?=$stockManager?>/items/listReceivedLogger.php">Received Logger</a></li>
					
				</ul>
            </li>
			<li class="pushy-submenu">
                <button>Challan</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$omenNX?>/challan/newChallan.php">New </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/challan/listChallan.php">List Challan</a></li>
				</ul>
            </li>
			<li class="pushy-submenu">
                <button>Invoice</button>
                <ul>
					
					<li class="pushy-link"><a href="/<?=$omenNX?>/invoice/listInvoice.php">List Invoice</a></li>
					
					<li class="pushy-link"><a href="/<?=$omenNX?>/invoice/addTransportDetails.php">Add Transport Details</a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/invoice/WhatsappLogger.php">WhatsappLogger</a></li>
				
				</ul>
            </li>
			<li class="pushy-submenu">
                <button>Payments</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$omenNX?>/payments/credits/newCredits.php">new Credit</a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/payments/debits/newDebits.php">New Debit </a></li>
					
					<li class="pushy-link"><a href="/<?=$omenNX?>/payments/credits/listCredits.php">List Credit </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/payments/debits/listAllDebits.php">LIST Debits </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/payments/debits/listDebits.php">Debits LOGGER </a></li>
					</ul>
            </li>
			<li class="pushy-submenu">
                <button>Supliers</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$omenNX?>/fabric/bills/newInvoice.php">New bill </a></li>
					
					<li class="pushy-link"><a href="/<?=$omenNX?>/fabric/Statement/SuppliertStatement.php">Statement </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/fabric/Statement/listAllBillsAndPayments.php">Statement ALL </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/fabric/Statement/listAllInvoice.php">Mothly Statement </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/fabric/merchants/AddMerchant.php">Add supplier </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/fabric/merchants/MerchantList.php">List Supplier </a></li>
					
				</ul>
				
            </li>
			
			<li class="pushy-submenu">
                <button>Backup</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$omenNX?>/Backup/backup.php">create new Backup </a></li>
				</ul>
            </li>
			<li class="pushy-submenu">
                <button>Report</button>
                <ul>
					<li class="pushy-link"><a href="/<?=$omenNX?>/report/monthsales.php">Sales  </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/report/monthpurchase.php">Purchase  </a></li>
					<li class="pushy-link"><a href="/<?=$omenNX?>/report/assetLiability.php">asset/Liability  </a></li>
					
				</ul>
            </li>
			
            
        </ul>
    </div>
</nav>

<!-- Site Overlay -->
<div class="site-overlay"></div>

<!-- Your Content -->
<div id="container">
    <!-- Menu Button -->
	<table><tr><td>
    <button class="menu-btn">&#9776; Menu</button></td><td width="95%"><font face="selfish" size="30px" ><center>Omen</font><font  size="90px" >NX</center></td></tr></table>
</div>

     
        

    


