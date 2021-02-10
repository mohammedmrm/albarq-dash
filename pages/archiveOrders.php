<?php
if(file_exists("script/_access.php")){
require_once("script/_access.php");
    access([1,2,3,5,4,7,8,9]);
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
.tdstyle {
  color: #000000;
  font-weight: bold;
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
  font-size: 18px;
}
#total-section {
  background-color: #242939;
  border-radius: 5px;
  box-shadow: 0px 0px 0px #444444;
  margin-top:5px;
}
.table td {
  padding: 4px !important;
  text-align: center !important;
}
.danger {
  display: block;
  background-color: #990000;
  color:#FFFFFF;
  text-align: center !important;
}
.success {
  display: block;
  background-color: #008000;
  color:#FFFFFF;
  text-align: center !important;
}


@page {
  size: landscape;
  margin: 5mm 5mm 5mm 5mm;
  }
 .chatbody {
  height: 400px;
  border:1px solid #A9A9A9;
  border-radius: 10px;
  overflow-y: scroll;
  padding-top:5px;
 }
 .msg {
   display: block;
   position: relative;
   margin-bottom:15px;
   padding-bottom:10px;
 }
 .other{
   position: relative;
   margin-left:0px;
   width:80%;
   margin-right:auto;
   text-align: left !important;
 }
 .other .content {
   background-color: #F8F8FF;
   border-top-right-radius: 5px;
   border-bottom-right-radius: 5px;
   text-align: left !important;
 }

 .mine {
   position: relative;
   width:80%;
   margin-left:0px;
   margin-right: 0px;

 }
 .mine .content {
   background-color: #008B8B;
   color:#F8F8FF;
   border-top-left-radius: 5px;
   border-bottom-left-radius: 5px;
 }

 .content{
   position: relative;
   padding:5px;
   padding-left:15px;
   padding-right:15px;
   display:inline-block;
   min-width:10px;
   max-width:80%;
   font-size: 14px;
   color:#000000;
 }
.name {
  position: relative;
  display: inline-block;
  font-size:10px;
}
.time {
  display:inline-block;
  position: relative;
  font-size: 10px;
  color: #696969;
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
				تقرير الطلبيات
			</h3>
		</div>
	</div>


	<div class="kt-portlet__body">
    <form id="ordertabledata" class="kt-form kt-form--fit kt-margin-b-20">
          <fieldset><legend>فلتر</legend>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المدخل:</label>
            	<select onchange=""  data-live-search="true" class="form-control kt-input" id="inserter" name="inserter" data-col-index="6">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>فرع الارسال:</label>
            	<select onchange="" class="form-control kt-input" id="to_branch" name="to_branch" data-col-index="6">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الصفحه:</label>
            	<select onchange="" data-actions-box="true" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control kt-input" id="store" name="store" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الحالة:</label>
            	<select onchange=""  title="اختر الحالة" class="form-control kt-input" id="orderStatus" name="orderStatus[]" data-live-search="true" data-show-subtext="true" data-actions-box="true" multiple data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المحافظة المرسل لها:</label>
            	<select id="city" name="city"  onchange="getTowns2($('#town'),$(this).val());" data-live-search="true" class="form-control kt-input" data-col-index="2">
            		<option value="">Select</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة التكرار:</label>
                <select name="repated" onchange="" class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="">عرض الكل</option>
            		<option value="1">عرض المكرر فقط</option>
            		<option value="2">عرض غير المكرر</option>
                </select>
            </div>
          </div>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>رقم الوصل:</label>
            	<input id="order_no" name="order_no" value="<?php if(!empty($_GET['order_no'])){ echo $_GET['order_no'];} ?>" onkeyup="" type="text" class="form-control kt-input" placeholder="" data-col-index="0">
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>اسم او هاتف المستلم:</label>
            	<input name="customer" onkeyup="" type="text" class="form-control kt-input" placeholder="" data-col-index="1">
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية :</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input value="<?php echo date('Y-m-d h:i', strtotime("-31 days")); ?>" onchange="" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input  type="text" class="form-control kt-input" name="end"  id="end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
            <div class="col-lg-1 kt-margin-b-10-tablet-and-mobile">
            	<label>المندوب:</label>
                <select id="driver" name="driver"  data-actions-box="true" data-live-search="true" class="form-control kt-input" data-col-index="3">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>كشف المندوب:</label>
                <select name="driver_invoice"  class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="">الكل</option>
            		<option value="1">طلبات بدون كشف</option>
            		<option value="2">طلبات بكشف</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة الطلبات من الكشف</label>
                <select id="invoice" name="invoice"  class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="">... اختر...</option>
            		<option value="1">طلبات بدون كشف</option>
            		<option value="2">طلبات بكشف</option>
                </select>
            </div>
          <div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
          </div>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المنطقه:</label>
                <select id="town" name="town"  class="form-control kt-input" data-live-search="true" data-col-index="2">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة التاكيد من الفروع</label>
                <select id="confirm" name="confirm"  class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="all">الكل</option>
            		<option value="1">الطلبيات المؤكدة</option>
            		<option value="4">الطلبيات الغير المؤكدة</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة الاستعلامات</label>
                <select id="callcenter" name="callcenter"  class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="all">الكل</option>
            		<option value="1">تم الاستعلام</option>
            		<option value="2">لم يتم الاستعلام</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label class="">.</label><br />
                    <input  type="button" value="بحث" onclick="getorders()"  class="btn btn-warning" placeholder="" data-col-index="1">
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
              	<label>عدد السجلات</label>
              	<select onchange="getorders()" class="form-control selectpicker" name="limit" data-col-index="7">
                    <option value="10">10</option>
              		<option value="15">15</option>
              		<option value="20">20</option>
              		<option value="25">25</option>
              		<option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                    <option value="750">750</option>
                    <option value="1000">1000</option>
              	</select>
            </div>
          </div>
          </fieldset>
		<!--begin: Datatable -->
        <div class="" id="section-to-print">
          <div class="col-md-12" id="">
          <div class="row kt-margin-b-20 text-white" id="total-section">
                <div class="col-sm-3">
                    <label>الدخل الكلي:&nbsp;</label><label id="total-income"> 0.0</label>
                 </div>
                 <div class="col-sm-3">
                    <label>مبلغ العميل:&nbsp;</label><label id="total-client-price"> 0.0 </label>
                 </div>
                 <div class="col-sm-2">
                    <label>مبلغ التوصيل:&nbsp;</label><label id="total-dev"> 0.0 </label>
                 </div>
                 <div class="col-sm-2">
                    <label>مجوع الخصم:&nbsp;</label><label id="total-discount"> 0.0 </label>
                 </div>
                 <div class="col-sm-2">
                    <label>عدد الطلبات:&nbsp;</label><label id="total-orders"> 0 </label>
                 </div>
          </div>
          </div>
        <div calss="col-sm-12 table-responsive">
		<table class="table table-striped  table-bordered  nowrap" style="white-space: nowrap; width: 100%;"  id="tb-orders">
			       <thead>
	  						<tr>
										<th><input  id="allselector" type="checkbox"><span></span></th>
										<th>رقم الوصل</th>
                                        <th>تعديل</th>
										<th>اسم وهاتف العميل</th>
										<th>عنوان وهاتف المستلم</th>
										<th>الحاله</th>
										<th>تاريخ الادخال</th>
										<th>مبلغ الوصل</th>
										<th>المبلغ المستلم</th>
                                        <th>المدخل</th>
										<th>مبلغ التوصيل</th>
										<th>المبلغ الصافي للعميل</th>

		  					</tr>
      	            </thead>
                            <tbody id="ordersTable">
                            </tbody>

		</table>
        </div>
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
<input type="hidden" id="user_id" value="<?php echo $_SESSION['userid'];?>"/>
<input type="hidden" id="user_branch" value="<?php echo $_SESSION['user_details']['branch_id'];?>"/>
<input type="hidden" id="user_role" value="<?php echo $_SESSION['role'];?>"/>
            <!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
                        <!--end::Page Vendors -->



            <!--begin::Page Scripts(used by this page) -->
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="assets/js/demo1/pages/components/datatables/extensions/fixedcolumns.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script src="js/getClients.js" type="text/javascript"></script>
<script src="js/getStores.js" type="text/javascript"></script>
<script src="js/getorderStatusMulti.js" type="text/javascript"></script>
<script src="js/getCities.js" type="text/javascript"></script>
<script src="js/getTowns.js" type="text/javascript"></script>
<script src="js/getInserter.js" type="text/javascript"></script>
<script src="js/getManagers.js" type="text/javascript"></script>
<script src="js/getAllDrivers.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).keydown(function(e) {
if (event.which === 13 || event.keyCode === 13 ) {
    event.stopPropagation();
    event.preventDefault();
    getorders();
}
});
var myTable;
getStores($("#store"));
getStores($("#e_store_id"));
getClients($("#e_client_id"),0);
getAllDrivers($("#driver"),$("#branch").val());
getInserter($("#inserter"));
$('#tb-orders').DataTable({
      "oLanguage": {
        "sLengthMenu": "عرض_MENU_سجل",
        "sSearch": "بحث:"
      },
       "scrollX": true,
       "aaSorting": [],
       "bPaginate": false,
       "bLengthChange": false,
       "bFilter": false,
      });
function getTowns2(elem,city){
   $.ajax({
     url:"script/_getTowns.php",
     type:"POST",
     data:{city: city},
     beforeSent:function(){

     },
     success:function(res){
       elem.html("");
       elem.append("<option value=''>-- اختر --</option>");
       $.each(res.data,function(){
         elem.append("<option value='"+this.id+"'>"+this.name+"</option>");
       });
       elem.selectpicker('refresh');
       console.log(res);
     },
     error:function(e){
        elem.append("<option value='' class='bg-danger'>خطأ اتصل بمصمم النظام</option>");
        console.log(e);
     }
   });
}
getTowns2($("#town"),1);
function getorders(){
$.ajax({
  url:"script/_getOrdersReport.php",
  type:"POST",
  data:$("#ordertabledata").serialize(),
  beforeSend:function(){
    $("#section-to-print").addClass('loading');


  },
  success:function(res){
   console.log(res);
  // saveEventDataLocally(res.data);
   $("#section-to-print").removeClass('loading');
   $("#tb-orders").DataTable().destroy();
   $("#ordersTable").html("");
   $("#pagination").html("");

/*   if($("#user_role").val() !=1 && $("#user_role").val() !=5){
    $('#branch').selectpicker('val', $("#user_branch").val());
    $('#branch').attr('disabled',"disabled");
    $('#branch').selectpicker('refresh');
   }*/

   $("#total-client-price").text(formatMoney(res.total[0].client_price));
   $("#total-income").text(formatMoney(res.total[0].income));
   $("#total-discount").text(formatMoney(res.total[0].discount));
   $("#total-dev").text(formatMoney(res.total[0].dev));
   $("#total-orders").text(res.total[0].orders);

   if(res.pages >= 1){
     if(res.page > 1){
         $("#pagination").append(
          '<li class="page-item"><a href="#" onclick="getorderspage('+(Number(res.page)-1)+')" class="page-link">السابق</a></li>'
         );
     }else{
         $("#pagination").append(
          '<li class="page-item disabled"><a href="#" class="page-link">السابق</a></li>'
         );
     }
     if(Number(res.pages) <= 5){
       i = 1;
     }else{
       i =  Number(res.page) - 5;
     }
     if(i <=0 ){
       i=1;
     }
     for(i; i <= res.pages; i++){
       if(res.page != i){
         $("#pagination").append(
          '<li class="page-item"><a href="#" onclick="getorderspage('+(i)+')" class="page-link">'+i+'</a></li>'
         );
       }else{
         $("#pagination").append(
          '<li class="page-item active"><span class="page-link">'+i+'</span></li>'
         );
       }
       if(i == Number(res.page) + 5 ){
         break;
       }
     }
     if(res.page < res.pages){
         $("#pagination").append(
          '<li class="page-item"><a href="#" onclick="getorderspage('+(Number(res.page)+1)+')" class="page-link">التالي</a></li>'
         );
     }else{
         $("#pagination").append(
          '<li class="page-item disabled"><a href="#" class="page-link">التالي</a></li>'
         );
     }
   }
   $.each(res.data,function(){
     if(this.invoice_id > 0 ){
         inv = '<a href="invoice/'+this.invoice_path+'" target="_blank" style="color:#FFFFFF;"> | رقم الكشف: '+'<b>'+this.invoice_id+'</b></a>';
     }else{
        inv = ""
     }
     if(this.money_status == 1){
       money = '<span class="success">تم التحاسب'+inv+'</span>';
     }else{
       money = '<span class="danger">لم يتم التحاسب'+inv+'</span>';
     }
     nuseen_msg =this.nuseen_msg;
     notibg = "kt-badge--danger";
     if(this.nuseen_msg == null){
       nuseen_msg = "";
       notibg="";
     }
     if(this.driver_id == 0){
       icon = "<br /><span  data-toggle='kt-tooltip' data-placement='top' data-original-title='لم يتم احالة الطلب الى مندوب' class='fa-2x text-danger flaticon-truck'></span>";
     }else{
       icon = "<br /><span  data-toggle='kt-tooltip' data-placement='top' data-original-title='تم الاحالة الى مندوب' class='fa-2x text-success flaticon2-delivery-truck'></span>";
     }
     if(this.driver_invoice_id > 0){
        icon = "<br /><a href='driver_invoice/"+this.driver_invoice_path+"'>رقم الكشف المندوب: "+this.driver_invoice_id+"</a>";
     }
     if(this.confirm == 4){
       bg ="bg-warning";
     }else{
       bg ="";
     }
     if(this.usd > 0){
       usd ="<br />($"+this.usd+")";
     }else{
       usd ="";
     }

     if(this.order_status_id == 4){
        status = '<div class="fc-draggable-handle kt-badge kt-badge--lg kt-badge--success kt-badge--inline " data-color="fc-event-success">'+this.status_name+'</div>';
     }else if(this.order_status_id == 9){
        status = '<div class="fc-draggable-handle kt-badge kt-badge--lg kt-badge--danger kt-badge--inline " data-color="fc-event-danger">'+this.status_name+'</div>';
     }else{
        status = this.status_name;
     }

     $("#ordersTable").append(
       '<tr class="">'+
            '<td class=""><input type="checkbox" name="id[]" rowid="'+this.id+'"><span></span></td>'+
            '<td>'+this.order_no+'</td>'+
            '<td>'+
                '<button type="button" class="btn btn-clean" onclick="editOrder('+this.id+')" data-toggle="modal" data-target="#editOrderModal"><span class="flaticon-edit"></sapn></button>'+
            '</td>'+
            '<td>'+this.store_name+'<br />'+(this.client_phone)+'</td>'+
            '<td>'+this.city+'/'+this.town+''+
            '<br />'+(this.customer_phone)+'</td>'+
            '<td>'+status+'<br /> ('+this.storage_status+')</td>'+
            '<td>'+this.date+'</td>'+
            '<td>'+formatMoney(this.price)+usd+'</td>'+
            '<td>'+formatMoney(this.new_price)+'</td>'+
            '<td>'+this.staff_name+'</td>'+
            '<td>'+formatMoney(this.dev_price)+'</td>'+
            '<td>'+formatMoney(this.client_price)+'</td>'+
        '</tr>');
     });

     myTable= $('#tb-orders').DataTable({
      "oLanguage": {
        "sLengthMenu": "عرض_MENU_سجل",
        "sSearch": "بحث:"
      },
      "scrollX": true,
      "aaSorting": [],
       "bPaginate": false,
       "bLengthChange": false,
       "bFilter": false,
      });
    },
   error:function(e){
    $("#section-to-print").removeClass('loading');
    console.log(e);
  }
});
}
$('a.toggle-vis').on( 'click', function (e) {
    e.preventDefault();

    // Get the column API object
    var column = myTable.column( $(this).attr('data-column') );

    // Toggle the visibility
    column.visible( ! column.visible() );
} );
function deleteOrder(id){
  if(confirm("هل انت متاكد من الحذف")){
      $.ajax({
        url:"script/_deleteOrder.php",
        type:"POST",
        data:{id:id},
        success:function(res){
         if(res.success == 1){
           Toast.success('تم الحذف');
           getorders();
         }else{
           Toast.warning(res.msg);
         }
         console.log(res);
        },
        error:function(e){
          console.log(e);
        }
      });
  }
}
$("#allselector").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('input[name="id\[\]"]').attr('checked', false);;
    }else{
      $('input[name="id\[\]"]').attr('checked', true);;
    }
});
function OrderChat(id,last){
  if(id != $("#chat_order_id").val()){
    chat = 1;
    $("#chatbody").html("");
  }else{
    chat = 0;
  }
  $("#chat_order_id").val(id);

  $.ajax({
    url:"script/_getMessages.php",
    type:"POST",
    data:{order_id:$("#chat_order_id").val(),last:last},
    beforeSend:function(){

    },
    success:function(res){
       if(res.success == 1){
         if(res.last <= 0){
             $("#chatbody").html("");
         }
         $.each(res.data,function(){
            clas = 'other';
           if(this.is_client == 1){
                name = this.client_name
                role = "عميل"
           }else{
               name = this.staff_name
               if(this.from_id== $("#user_id").val()){
                 clas = 'mine';
               }
             role =  this.role_name;
           }
           message =
           "<div class='row'>"+
             "<div class='msg "+clas+"' msq-id='"+this.id+"'>"+
                "<span class='name'>"+name+ " ( "+role+" ) "+"</span><br />"+
                "<span class='content'>"+this.message+"</span><br />"+
                "<span class='time'>"+this.date+"</span><br />"+
             "</div>"+
           "</div>"
           $("#chatbody").append(message);
           $("#last_msg").val(this.id);
         });
          $('#chatbody').animate({scrollTop: $('#chatbody')[0].scrollHeight},100);
            $("#spiner").remove();
       }
    },
    error:function(e){
      console.log(e);
    }
  });
}
var mychatCaller;
$("#chatOrderModal").on('show.bs.modal', function(){
mychatCaller = setInterval(function(){
  OrderChat($("#chat_order_id").val(),$("#last_msg").val());
}, 1000);
});
$("#chatOrderModal").on('hide.bs.modal', function(){
clearInterval(mychatCaller);
});

function getorderspage(page){
    $("#p").val(page);
    getorders();
}


  getBraches($("#e_branch"));
  getBraches($("#e_branch_to"));
  getCities($("#e_city"));
function updateClient(){
 getClients($('#e_client'),$('#e_branch').val());
}

function updateTown(){
   getTowns($('#e_town'),$('#e_city').val());
}

function getclient(){
 if($("#user_role").val() != 1){
     getClients($("#client"),$("#user_branch").val());
 }else{
     getClients($("#client"),$("#branch").val());
 }
 getorders();
}
$( document ).ready(function(){

$("#allselector").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('input[name="id\[\]"]').attr('checked', false);;
    }else{
      $('input[name="id\[\]"]').attr('checked', true);;
    }
});
$('#start').datetimepicker({
    format: "yyyy-mm-dd hh:ii",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#end').datetimepicker({
    format: "yyyy-mm-dd hh:ii",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#e_date').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
});

getBraches($("#branch"));
getBraches($("#to_branch"));
getorderStatus($("#orderStatus"));
getorderStatus($("#status_action"));
getCities($("#city"));
getorders();
//-- set branch equles to user branch
$('#branch').selectpicker('val', $("#user_branch").val());
//-- set clients equles to user branch's clients
getclient();
});


</script>