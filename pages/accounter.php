<?php
if(file_exists("script/_access.php")){
  require_once("script/_access.php");
  access([1,2,5]);
}
?>
<?
include("config.php");
?>
<style>
fieldset {
		border: 1px solid #ddd !important;
		margin: 0;
		xmin-width: 0;
		padding: 10px;
		position: relative;
		border-radius:4px;
		background-color:#f5f5f5;
		padding-left:10px !important;
		width:100%;
}
legend
{
	font-size:14px;
	font-weight:bold;
	margin-bottom: 0px;
	width: 55%;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 5px 5px 5px 10px;
	background-color: #ffffff;
}
.success {
 background-color: #CCFFCC;
}
.danger {
background-color: #FFCCCC;
}
.warning{
background-color: #FFFF99;
}

@media print {
  body * {
    visibility: hidden;

  }
  #printReportForm, .header{
    display: none;
  }

  #section-to-print, #section-to-print * {
    visibility: visible;
    color: #000000;

  }
  #section-to-print {
    //position: absolute;
    margin:0px;
    padding: 0px;
    left: 0;

  }
  .dele, .edit{
   visibility: hidden;
   display: none;
  }
}
.text-white {
  color: #FFFFFF;
  padding: 15px;
}
#total-section {
  background-color: #242939;
  border-radius: 5px;
  box-shadow: 0px 0px 0px #444444;
  margin-top:5px;
}
#total-sectionlabel {
  font-size: 14px;
}
@page {
  size: landscape;
  margin: 5mm 5mm 5mm 5mm;
  }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">

            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
					<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				الفواتير
			</h3>
		</div>
	</div>


	<div class="kt-portlet__body">
<!--    <div class="row">
    <div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
        <span class="h4"> خلاصه بمبالغ الطلبيات التي تملك كشف عميل</span><hr />
    </div>
    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
        <label class="h5">الدخل الكلي: <label class="text-accent" id="income"></label></label><br />
        <label class="h5">عدد الطلبيات الكلي: <label class="text-accent" id="orders"></label></label><br />
        <label class="h5">عدد الطلبيات الواصله: <label class="text-accent" id="orders_with_dev"></label></label><br />
   </div>
    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
        <label class="h5">صافي العملاء: <label class="text-danger" id="client_price"></label></label><br />
        <label class="h5">الارباح: <label class="text-success" id="earnings"></label></label><br />
    </div>
    </div>-->
    <form id="invoicesForm" class="kt-form kt-form--fit kt-margin-b-20">
          <fieldset><legend>بحث عن كشف</legend>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المحاسب:</label>
            	<select onchange="getInvoices();getDInvoices();getAccountingInfo();"  data-live-search="true" class="form-control kt-input" id="inserter" name="inserter" data-col-index="6">
            	</select>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية (تاريخ الكشف):</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input value="<?php echo date('Y-m-d', strtotime('-7 days'));?>" onchange="getInvoices();getDInvoices();getAccountingInfo();" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input onchange="getInvoices();getDInvoices();getAccountingInfo();" type="text" class="form-control kt-input" name="end"  id="end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
            <div class="col-lg-1 kt-margin-b-10-tablet-and-mobile">
            	<label>بحث:</label><br />
            	<button type="button" onclick="getInvoices();getDInvoices();getAccountingInfo();" type="text" class="btn btn-success" value="" placeholder="" data-col-index="0">بحث
                    <span id="search"  role="status"></span>
                </button>
            </div>
           <div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
          </div>
          </fieldset>

		<!--begin: Datatable -->
        <div class="" id="section-to-print">
         <?php if($_SESSION['role'] == 1){ ?>
          <div class="col-md-12" id="">
          <div class="row kt-margin-b-20 text-white" id="total-section">
                 <div class="col-sm-3">
                    <label class="">مسدد للعملاء:&nbsp;</label><label id="paid"> 0.0 </label>
                    <br /><label class="">مستلم من المندوبين:&nbsp;</label><label id="received"> 0.0</label>
                 </div>

                <div class="col-sm-3">
                    <label class="">عدد الكشوفات العملاء:&nbsp;</label><label id="c_invoices"> 0 </label>
                    <br /><label class="">عدد الكشوفات المندوبين:&nbsp;</label><label id="d_invoices"> 0 </label>
                </div>
                <div class="col-sm-3">
                    <label class="">باقي في ذمه المحاسب:&nbsp;</label><label id="with_accounter"> 0.0 </label>
                 </div>
                <div class="col-sm-2">
                   <button type="button" class="btn btn-danger"  onclick="confirmInvoices();confirmDInvoices();">تصفير ذمه المحاسب</button>
                </div>
                <div class="col-sm-1">
                   <button onclick="getAccounterHistory()" type="button" class="btn btn-icon text-white" data-toggle="modal" data-target="#accounterHistory"><span class="fa-2x fa fa-history"></span></button>
                </div>
          </div>
          </div>
          <?php } ?>
		   <table class="table  table-bordered  responsive no-wrap" id="tb-invioces">
			       <thead>
	  						<tr>
        						<th>رقم الفاتوره</th>
        						<th>اسم الصفحه</th>
        						<th>اسم العميل</th>
        						<th>رقم هاتف العميل</th>
        						<th>المحاسب</th>
        						<th>المبلغ الكلي</th>
        						<th>مبلغ التوصيل</th>
        						<th>التاريخ</th>
        						<th>الملف</th>
        						<th>حالة الكشف</th>
        						<th>التاكيد</th>
		  					</tr>
      	            </thead>
                    <tbody id="invoicesTable">
                    </tbody>
                    <tfoot>
	           </tfoot>
		</table>

        <!-- كشوفات المندوبين -->

    	<table class="table  table-bordered  responsive no-wrap" id="tb-Dinvioces">
	       <thead>
 						<tr>
      						<th>رقم الفاتوره</th>
      						<th>اسم المندوب</th>
      						<th>رقم هاتف المندوب</th>
      						<th>المحاسب</th>
      						<th>المبلغ الكلي</th>
      						<th>اجره المندوب</th>
      						<th>التاريخ</th>
      						<th>الملف</th>
      						<th>حالة الكشف</th>
  					</tr>
    	    </thead>
            <tbody id="DinvoicesTable">
            </tbody>
        </table>

        <div class="kt-section__content kt-section__content--border">
		<nav aria-label="...">
			<ul class="pagination" id="pagination">

			</ul>
        <input type="hidden" id="p" name="p" value="<?php if(!empty($_GET['p'])){ echo $_GET['p'];}else{ echo 1;}?>"/>
		</nav>
     	</div>
        </div>
        </form>
        <!--end: Datatable -->
	</div>

</div>

</div>
<!-- end:: Content -->
</div>
<div class="modal fade" id="accounterHistory" role="dialog">
    <div class="modal-dialog modal-xl">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h4 class="modal-title">تاريخ المحاسب</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
    <form id="historyForm" class="kt-form kt-form--fit kt-margin-b-20">
          <fieldset>
          <div class="row kt-margin-b-20">
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            	<label>المحاسب:</label>
            	<select onchange="getAccounterHistory()"  data-live-search="true" class="form-control kt-input" id="h_inserter" name="h_inserter" data-col-index="6">
            	</select>
            </div>
            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية (تاريخ الكشف):</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input value="<?php echo date('Y-m-d', strtotime('-7 days'));?>" onchange="getAccounterHistory()" type="text" class="form-control kt-input" name="h_start" id="h_start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input onchange="getAccounterHistory()" type="text" class="form-control kt-input" name="h_end"  id="h_end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
          </div>
          </fieldset>

    	<table class="table  table-bordered  responsive no-wrap" id="tb-history">
	       <thead>
 						<tr>
                            <th>اسم المحاسب</th>
      						<th>عدد الفاواتير</th>
                            <th>التاريخ</th>
      						<th>المبلغ</th>
      						<th>النوع</th>
  					</tr>
    	    </thead>
            <tbody id="history">
            </tbody>
        </table>
          </form>
        <!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>
            <!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
                        <!--end::Page Vendors -->



            <!--begin::Page Scripts(used by this page) -->
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="js/getClients.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script src="js/getInserter.js" type="text/javascript"></script>
<script type="text/javascript">
function getInvoices(){
   $.ajax({
     url:"script/_getInvoices.php",
     type:"POST",
     data:$("#invoicesForm").serialize(),
     beforeSend:function(){
       $("#tb-invioces").DataTable().destroy();
       $("#invoicesTable").html("");
     },
     success:function(res){
     console.log(res);
     $.each(res.data,function(){
      btn ="";
     if(this.invoice_status == 1){
       invoice_status = "<span >تم التحاسب<span>";
       btn = '<button type="button" class="btn btn-danger" onclick="unpayInvoice('+this.id+')" >الغأ التحاسب</button>';
     }else{
       invoice_status = "<span >لم يتم التحاسب<span>";
       btn = '<button type="button" class="btn btn-success" onclick="payInvoice('+this.id+')">تم التحاسب</button>';
     }
     if(this.orders_status == 4){
       bg = 'success';
     }else if(this.orders_status == 6 || this.orders_status == 9 ){
       bg = 'danger';
       if(this.invoice_status == 1){
         invoice_status = "راجع للعميل";
           btn = '<button type="button" class="btn btn-danger" onclick="unpayInvoice('+this.id+')" >ارجاع للمخزن الرئيسي</button>';
       }else{
         invoice_status = "رواجع";
          btn = '<button type="button" class="btn btn-success" onclick="payInvoice('+this.id+')">راجع للعميل</button>';
       }
     }else if(this.orders_status == 7){
       bg = 'warning';
     }else{
       bg = "";
     }
     if(this.confirm == 1){
       confirm = "مؤكد";
     }else{
       confirm = "غير مؤكد";
     }
      $("#invoicesTable").append(
       '<tr class="'+bg+'">'+
            '<td>'+this.id+'</td>'+
            '<td>'+this.store_name+'</td>'+
            '<td>'+this.client_name+'</td>'+
            '<td>'+this.client_phone+'</td>'+
            '<td>'+this.staff_name+'</td>'+
            '<td>'+this.total+'</td>'+
            '<td>'+this.dev_price+'</td>'+
            '<td>'+this.in_date+'</td>'+
            '<td><a href="invoice/'+this.path+'" target="_blank">تحميل ملف الكشف</a></td>'+
            '<td>'+invoice_status+'</td>'+
            '<td>'+confirm+'</td>'+
        '</tr>');
     });
/*      $.each(res.total,function(){
         $("#income").text(formatMoney(this.income));
         $("#orders").text(this.orders);
         $("#orders_with_dev").text(this.orders_with_dev);
         $("#earnings").text(formatMoney(this.earnings));
         $("#real_earnings").text(formatMoney(this.real_earnings));
         $("#branch_earnings").text(formatMoney(this.branch_earnings));
         $("#client_price").text(formatMoney(this.client_price));
      });*/
     var myTable= $('#tb-invioces').DataTable({
      "oLanguage": {
        "sLengthMenu": "عرض_MENU_سجل",
        "sSearch": "بحث:"
      },
      "ordering": false,
      });
     },
     error:function(e){
        console.log(e);
     }
   });
}
function getDInvoices(){
   $.ajax({
     url:"script/_getDInvoices.php",
     type:"POST",
     data:$("#invoicesForm").serialize(),
     beforeSend:function(){
       $("#tb-Dinvioces").DataTable().destroy();
       $("#DinvoicesTable").html("");
       $("#invoicesForm").addClass("loading");
     },
     success:function(res){
     $("#invoicesForm").removeClass("loading");
     console.log(res);
     $.each(res.data,function(){
      btn ="";
     if(this.invoice_status == 1){
       invoice_status = "<span >تم التحاسب<span>";
       btn = '<button type="button" class="btn btn-danger" onclick="unpayInvoice('+this.id+')" >الغأ التحاسب</button>';
     }else{
       invoice_status = "<span >لم يتم التحاسب<span>";
       btn = '<button type="button" class="btn btn-success" onclick="payInvoice('+this.id+')">تم التحاسب</button>';
     }
     if(this.orders_status == 4){
       bg = 'success';
     }else if(this.orders_status == 6 || this.orders_status == 9 ){
       bg = 'danger';
       if(this.invoice_status == 1){
         invoice_status = "راجع للعميل";
           btn = '<button type="button" class="btn btn-danger" onclick="unpayInvoice('+this.id+')" >ارجاع للمخزن الرئيسي</button>';
       }else{
         invoice_status = "رواجع";
          btn = '<button type="button" class="btn btn-success" onclick="payInvoice('+this.id+')">راجع للعميل</button>';
       }
     }else if(this.orders_status == 7){
       bg = 'warning';
     }else{
       bg = "";
     }
     if(this.confirm == 1){
       confirm = "مؤكد";
     }else{
       confirm = "غير مؤكد";
     }
      $("#DinvoicesTable").append(
       '<tr class="">'+
            '<td>'+this.id+'</td>'+
            '<td>'+this.driver_name+'</td>'+
            '<td>'+this.driver_phone+'</td>'+
            '<td>'+this.staff_name+'</td>'+
            '<td>'+this.total+'</td>'+
            '<td>'+this.driver_price+'</td>'+
            '<td>'+this.in_date+'</td>'+
            '<td><a href="driver_invoice/'+this.path+'" target="_blank">تحميل ملف الفاتوره</a></td>'+
            '<td>'+invoice_status+'</td>'+
        '</tr>');
     });
     var myTable= $('#tb-Dinvioces').DataTable({
      "oLanguage": {
        "sLengthMenu": "عرض_MENU_سجل",
        "sSearch": "بحث:"
      },
      "ordering": false,
      });
     },
     error:function(e){
        console.log(e);
        $("#invoicesForm").removeClass("loading");
     }
   });
}
function getAccountingInfo(){
      $.ajax({
        url:"script/_getAccountingInfo.php",
        type:"POST",
        data:$("#invoicesForm").serialize(),
        success:function(res){
          console.log(res) ;
            $("#c_invoices").text(res.total.c_invoices);
            $("#d_invoices").text(res.total.d_invoices);
            $("#paid").text(formatMoney(res.total.paid));
            $("#received").text(formatMoney(res.total.received));
            $("#with_accounter").text(formatMoney(res.total.with_accounter));

        } ,
        error:function(e){
          console.log(e);
        }
      });
}
function confirmInvoices(){
 $.ajax({
        url:"script/_confirmInvoices.php",
        type:"POST",
        data:$("#invoicesForm").serialize(),
        success:function(res){
         if(res.success == 1){
           Toast.success('تم التاكيد');
         }else{
           Toast.warning(res.msg);
         }
         console.log(res)
        } ,
        error:function(e){
          console.log(e);
        }
      });
}
function confirmDInvoices(){
 $.ajax({
        url:"script/_confirmDInvoices.php",
        type:"POST",
        data:$("#invoicesForm").serialize(),
        success:function(res){
         if(res.success == 1){
           Toast.success('تم التاكيد');
           getAccountingInfo();
         }else{
           Toast.warning(res.msg);
         }
         console.log(res)
        } ,
        error:function(e){
          console.log(e);
        }
      });
}
function getAccounterHistory(){
 $.ajax({
        url:"script/_getAccounterHistory.php",
        type:"POST",
        data:$("#historyForm").serialize(),
        success:function(res){
          console.log(res);
          $("#history").html("");
         if(res.success == 1){
           $.each(res.data,function(){
             if(this.invoices == 0){
               bg="success";
               invoices = this.driver_invoices;
               price= this.received;
               type = "استلام ملبغ من المندوبين";
             }else{
               bg="danger";
               invoices = this.invoices;
               price= this.paid;
               type="دفع مبلغ للعملاء"
             }
             $("#history").append(
             '<tr class="'+bg+'">'+
                '<td>'+this.name+'</td>'+
                '<td>'+invoices+'</td>'+
                '<td>'+this.date+'</td>'+
                '<td>'+formatMoney(price)+'</td>'+
                '<td>'+type+'</td>'+
             '</tr>'
             );
           });
         }

        } ,
        error:function(e){
          console.log(e);
        }
      });
}
$( document ).ready(function(){
 getInvoices();
 getDInvoices();
 getInserter($("#inserter"));
 getInserter($("#h_inserter"));
 getAccountingInfo();
});

$('#start').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#end').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#h_start').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#h_end').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
</script>