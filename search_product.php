<?php
include "dbconfig.php";

$con = mysqli_connect($host,$username,$password,$dbname)
		or die("<br> Cannot connect to DB:$dbname on $host\n");

	$keyword = $_GET['search_items'];

	if($keyword=="*"){
		$condition="";
	}
	else{
		$condition="where p.name like CONCAT('%' '$keyword' '%') or p.description like CONCAT('%','$keyword','%')";
	}
	$sql="select p.id, p.name, p.description, p.cost, p.sell_price, p.quantity, v.name as vname, e.name as ename from 2022F_parraolk.PRODUCT p join CPS5740.VENDOR v on p.vendor_id = v.vendor_id join CPS5740.EMPLOYEE2 e on p.employee_id = e.employee_id $condition group by p.id";

	if(isset($_COOKIE['Customer_name'])){
		$result = mysqli_query($con,$sql);
		setcookie("Customer_search",$keyword,time()+3600);

		if($result){
			if(mysqli_num_rows($result)>0){
				echo "<a href= 'CPS5740_customer_logout.php'>Customer logout</a><br>
					  Available product list for the search keyword: $keyword
					  <form name='input' action='customer_order.php' method='post'>
					  <TABLE border=1\n
					  <TR><TH>Product Name<TH>Description<TH>Sell Price<TH>Available quantity<TH>Order Quantity<TH>Vendor Name</TR>\n";
					  while($row=mysqli_fetch_array($result)){
					  	$id=$row['id'];
					  	$product_name=$row['name'];
					  	$scription=$row['description'];
					  	$sell_price=$row['sell_price'];
					  	$quantity=$row['quantity'];
					  	$vendor_name=$row['vname'];

					  	echo "<TR><TD>$product_name<TD>$scription<TD>$sell_price<TD>$quantity<TD><input type='number' max='$quantity' min='1'name='quantity[]'size='5'><TD>$vendor_name<input type='hidden' name='id[]' value='$id'</TR>";
					  }
					  echo "</TABLE>\n";
					  echo "<input type='submit' value='Place Order'>
					  		</form>";
					  echo "<br><a href='CPS5740_customer_login_p2.php'>Customer home page</a>
					  		<br><a href='index.html'>project home page</a>";
			}
			else{
		echo "<a href= 'CPS5740_customer_logout.php'>Customer logout</a><br>
			  No product found for search keyword: <b>$keyword</b>
			  <br><a href='CPS5740_customer_login_p2.php'>Customer home page</a>
			  <br><a href='index.html'>project home page</a>";
			}
		}
	}

	elseif(!isset($_COOKIE['Customer_name'])){
		setcookie("Customer_search",$keyword,time()+3600);
		$result = mysqli_query($con,$sql);

	if($result){
		if(mysqli_num_rows($result)>0){
			echo "<a href='CPS5740_customer_login_p2.php'>Customer login</a><br>
				  Available product list for search keyword: $keyword 
				  <TABLE border=1\n
				  <TR><TH>Product Name<TH>Description<TH>Sell price<TH>Available quantity<TH>Vendor name</TR>\n";
			while($row=mysqli_fetch_array($result)){
				$pname = $row['name'];
				$description = $row['description'];
				$sprice = $row['sell_price'];
				$aquantity = $row['quantity'];
				$vname = $row['vname'];

			echo "<TR><TD>$pname<TD>$description<TD>$sprice<TD>$aquantity<TD>$vname</TR>\n";
			}
			echo "</TABLE>\n";
			echo "<br><a href='index.html'>project home page</a>";
		}
	else{
		echo "<a href='CPS5740_customer_login_p2.php'>Customer login</a><br>
			  No product found for search keyword: <b>$keyword</b>
			  <br><a href='index.html'>project home page</a>";
		}
	}//end if result.
	}
mysqli_close($con);
?>
