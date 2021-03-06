<?php
if(file_exists("script/_access.php")){
require_once("script/_access.php");
access([1,2,5,3,7,9,8]);
}
?>
<style>
#ordersTableDiv {
min-height: 100px;
}

</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">

    </div>
</div>
<!-- end:: Subheader -->
					<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
			  الطلبيات
			</h3>
		</div>
	</div>

	<div class="kt-portlet__body">
		<!--begin: Datatable -->
        <form id="ordertabledata" class="kt-form kt-form--fit kt-margin-b-20">
          <fieldset><legend>فلتر</legend>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الفرع:</label>
            	<select onchange="" class="form-control kt-input" id="branch" name="branch" data-col-index="6">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الصفحه:</label>
            	<select onchange="" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control kt-input" id="store" name="store" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الحالة:</label>
            	<select onchange="" class="form-control kt-input" id="orderStatus" name="orderStatus[]" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المحافظة المرسل لها:</label>
            	<select id="city" name="city" onchange="" class="form-control kt-input" data-col-index="2">
            		<option value="">Select</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة الطلبات من الكشف</label>
                <select id="invoice" name="invoice" onchange="" class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="">... اختر...</option>
            		<option value="1">طلبات بدون كشف</option>
            		<option value="2">طلبات كشف</option>
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
            	<input id="order_no" name="order_no" value="<?php if(!empty($_GET['order_no'])){ echo $_GET['order_no'];} ?>"  type="text" class="form-control kt-input" placeholder="" data-col-index="0">
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>اسم او هاتف المستلم:</label>
            	<input name="customer" onkeyup="" type="text" class="form-control kt-input" placeholder="" data-col-index="1">
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المندوب:</label>
                <select id="driver" name="driver" data-show-subtext="true" data-live-search="true" onchange="" class="form-control kt-input" data-col-index="2">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة تسليم المبلغ للعميل:</label>
                <select name="money_status" onchange="" class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="">... اختر...</option>
            		<option value="1">تم تسليم المبلغ</option>
            		<option value="0">لم يتم تسليم المبلغ</option>
                </select>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية :</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input value="" onchange="" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input onchange="" type="text" class="form-control kt-input" name="end"  id="end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة التاكيد من الفروع</label>
                <select id="confirm" name="confirm" onchange="" class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="all">الكل</option>
            		<option value="1">الطلبيات المؤكدة</option>
            		<option value="4">الطلبيات الغير المؤكدة</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>.</label> <br />
            	<input type="button" class="btn btn-warning" value="بحث" id="updateStatues"  onclick="getorders()"/>
            </div>
          </div>
          </fieldset>
          <div class="row kt-margin-b-20">
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
              	<label>عدد السجلات في الصفحة الواحدة</label>
              	<select onchange="" class="form-control selectpicker" name="limit" data-col-index="7">
                    <option value="10">10</option>
              		<option value="15">15</option>
              		<option value="20">20</option>
              		<option value="25">25</option>
              		<option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
              	</select>
              </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>تحديث الحاله الى:</label>
            	<select st='st' data-size="5" class="form-control kt-input" onchange="setOrdersStatus()" id="setOrderStatus" name="setOrderStatus" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>تحديث:</label> <br />
            	<input type="button" class="btn btn-success" value="تحديث" id="updateStatues"  onclick="updateOredrsStatus()"/>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                <br /><label class="fa-2x">عدد الطلبيات:&nbsp;</label><label class="fa-2x" id="total-orders"> 0 </label>
            </div>
          </div>
        <div id="ordersTableDiv" >
        <table class="table table-striped table-bordered table-hover table-checkable responsive nowarp" id="tb-orders">
			       <thead>
	  						<tr>
										<th>العنوان</th>
                                        <th>رقم الوصل</th>
                                        <th>مبلغ الوصل</th>
                                        <th width="200px;">تـــــــــــحديث الحالــــــــــــــة</th>
										<th width="200px;">سبب الــــــــرجع</th>
										<th>الحاله</th>
										<th width="120px">المبلغ المستلم</th>
										<th>ادخال مخزني</th>
										<th>اسم و هاتف العميل</th>
										<th>رقم هاتف المستلم</th>
										<th>مبلغ التوصيل</th>
                                        <th width="120px">التاريخ</th>
                                        <th>المدخل</th>
										<th>المندوب</th>
						   </tr>
      	            </thead>
                            <tbody id="ordersTable">
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
<input type="hidden" id="user_id" value="<?php echo $_SESSION['userid'];?>"/>
<input type="hidden" id="user_branch" value="<?php echo $_SESSION['user_details']['branch_id'];?>"/>
<input type="hidden"  id="user_role" value="<?php echo $_SESSION['role'];?>"/>

<!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!--begin::Page Scripts(used by this page) -->
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script src="js/getClients.js" type="text/javascript"></script>
<script src="js/getStores.js" type="text/javascript"></script>
<script src="js/getorderStatusMulti.js" type="text/javascript"></script>
<script src="js/getCities.js" type="text/javascript"></script>
<script src="js/getTowns.js" type="text/javascript"></script>
<script src="js/getManagers.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script src="js/getAllDrivers.js" type="text/javascript"></script>
<script type="text/javascript">
getStores($("#store"));
getAllDrivers($("#driver"));
$(document).keydown(function(e) {
if (event.which === 13 || event.keyCode === 13 ) {
    event.stopPropagation();
    event.preventDefault();
    getorders();
}
});
function getorders(){
  options = `<option style="" value="_">-- اختر الحالة --</option><option style="background-color:" value="1">تم تسجيل الطلب</option><option style="background-color:" value="2">جاهز للارسال</option><option style="background-color:" value="3">بالطريق مع المندوب</option><option style="background-color:#9CDE7C" value="4">تم تسليم الطلب</option><option style="background-color:#FFFFAC" value="5">استبدال الطلب</option><option style="background-color:" value="6">راجع جزئي</option><option style="background-color:" value="7">مؤجل </option><option style="background-color:" value="8">تغير عنوان</option><option style="background-color:#F2A69B" value="9">راجع كلي</option><option style="background-color:" value="10">راجع بمخزن المحافظه</option><option style="background-color:" value="11">راجع بالمخزن الرئيسي</option><option style="background-color:" value="12">راجع للعميل</option><option style="background-color:" value="13">اعادة ارسال</option>`;
  reason = `         <option value="لايرد">لايرد</option>
                     <option value="لايرد مع رسالة">لايرد مع رسالة</option>
                     <option value="تم اغلاق الهاتف">تم اغلاق الهاتف</option>
                     <option value="رفض الطلب">رفض الطلب</option>
                     <option value="مكرر">مكرر</option>
                     <option value="كاذب">كاذب</option>
                     <option value="الرقم غير معرف">الرقم غير معرف</option>
                     <option value="رفض الطلب">رفض الطلب</option>
                     <option value="حظر المندوب">حظر المندوب</option>
                     <option value="لايرد بعد التاجيل">لايرد بعد التاجيل</option>
                     <option value="مسافر">مسافر</option>
                     <option value="تالف">تالف</option>
                     <option value="راجع بسبب الحظر">راجع بسبب الحظر</option>
                     <option value="لايمكن الاتصال به">لايمكن الاتصال به</option>
                     <option value="مغلق بعد الاتفاق">مغلق بعد الاتفاق</option>
                     <option value="مستلم سابقا">مستلم سابقا</option>
                     <option value="لم يطلب">لم يطلب</option>
                     <option value="لايرد بعد سماع المكالمة">لايرد بعد سماع المكالمة</option>
                     <option value="غلق بعد سماع المكالمة">غلق بعد سماع المكالمة</option>
                     <option value="مغلق">مغلق</option>
                     <option value="تم الوصول والرفض">تم الوصول والرفض</option>
                     <option value="لايرد بعد الاتفاق">لايرد بعد الاتفاق</option>
                     <option value="غير داخل بالخدمة">غير داخل بالخدمة</option>
                     <option value="خطأ بالعنوان">خطأ بالعنوان</option>
                     <option value="مستلم سابقا">مستلم سابقا</option>
                     <option value="خطأ بالتجهيز">خطأ بالتجهيز</option>
                     <option value="نقص رقم">نقص رقم</option>
                     <option value="زيادة رقم">زيادة رقم</option>
                     <option value="وصل بدون طلبية">وصل بدون طلبية</option>
                     <option value="الغاء الحجز">الغاء الحجز</option>`
$.ajax({
  url:"script/_getOrdersReport.php",
  type:"POST",
  data:$("#ordertabledata").serialize(),
  beforeSend:function(){
    $("#ordersTableDiv").addClass('loading');

  },
  success:function(res){
   $("#ordersTableDiv").removeClass('loading');
   $("#tb-orders").DataTable().destroy();
   console.log(this,res);
   // saveEventDataLocally(res.data);
   $("#ordersTable").html("");
   $("#pagination").html("");

/*   if($("#user_role").val() != 1){
    $('#branch').selectpicker('val', $("#user_branch").val());
    $('#branch').attr('disabled',"disabled");
    $('#branch').selectpicker('refresh');
   }*/
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
   $("#total-orders").text(res.total[0].orders);
   i = 0;
   $.each(res.data,function(){
     if(this.money_status == 1){
       money = '<span class="success">تم التحاسب</span>';
     }else{
       money = '<span class="danger">لم يتم التحاسب</span>';
     }
     nuseen_msg =this.nuseen_msg;
     notibg = "kt-badge--danger";
     if(this.nuseen_msg == null){
       nuseen_msg = "";
       notibg="";
     }
     if(this.usd > 0){
       usd ="<br />($"+this.usd+")";
     }else{
       usd ="";
     }
     if(this.confirm == 4){
       bg ="bg-warning";
     }else{
       bg ="";
     }
      btn ="";
     if(this.order_status_id == 6 || this.order_status_id == 5 || this.order_status_id == 9){
     if(this.storage_id == 0){
       btn ='<button type="button" class="btn btn-icon text-danger btn-lg" onclick="setOrderStorage('+this.id+')"><span class="flaticon-download"></span></button>';
     }else if(this.storage_id != 0){
       btn ='<button type="button" class="btn btn-icon text-success btn-lg" onclick="setOrderOutStorage('+this.id+')"><span class="flaticon-upload"></span></button>';
     }else{
       btn ="";
     }
     }
     $("#ordersTable").append(
       '<tr class="'+bg+'">'+
            '<td>'+this.city+"<br />"+this.town+'<input type="hidden" value="'+this.id+'" name="ids[]">'+
            '</td>'+
            '<td>'+this.order_no+'</td>'+
            '<td>'+formatMoney(this.price)+usd+'</td>'+
            '<td>'+
              '<select status="status" class="form-control" style="height:40px;" name="statuses[]"  value="">'+
                options+
              '</select>'+
            '</td>'+
            '<td>'+
              '<select reason="reason" class="form-control" style="height:40px;" name="reason[]"  value="">'+
                reason+
              '</select>'+
            '</td>'+
            '<td>'+this.status_name+'<br />('+this.storage_status+')</td>'+
            '<td><input type="text" value="'+this.new_price+'" oninput="CurrencyFormatted($(this),$(this).val())" onload="CurrencyFormatted($(this),$(this).val())"  name="new_price[]" class="form-control" width="50px"></td>'+
            '<td>'+btn+'</td>'+
            '<td>'+this.store_name+'<br />'+(this.client_phone)+'</td>'+
            '<td>'+(this.customer_phone)+'</td>'+
            '<td>'+formatMoney(this.dev_price)+'</td>'+
            '<td>'+this.date+'</td>'+
            '<td>'+this.staff_name+'</td>'+
            '<td>'+this.driver_name+'</td>'+
        '</tr>');
        i++;
     });
     //$('.selectpicker').selectpicker('refresh');
    var myTable= $('#tb-orders').DataTable({
       "bPaginate": false,
       "bFilter": false,
       "ordering": true
      });

    },
   error:function(e){
    $("#ordersTableDiv").removeClass('loading');
    console.log(e);
  }
});
}
function setOrdersStatus(){
 status = $("#setOrderStatus").val();
 $('[status="status"]').val(status);
}
function updateOredrsStatus(){
      $.ajax({
        url:"script/_updateOredrsStatus.php",
        type:"POST",
        data:$("#ordertabledata").serialize(),
        beforeSend:function(){
            $("#section-to-print").addClass('loading');
        },
        success:function(res){
         $("#section-to-print").removeClass('loading');
         if(res.success == 1){
           Toast.success('تم تحديث الحاله');
           getorders();
         }else{
           Toast.warning('خطأ');
         }
         console.log(res);
        },
        error:function(e){
          $("#section-to-print").removeClass('loading');
          console.log(e);
        }
      });
}
function getorderspage(page){
    $("#p").val(page);
    getorders();
}
function updateTown(){
   getTowns($('#e_town'),$('#e_city').val());
}
function autoUpdate(){
      $.ajax({
        url:"script/_autoUpdate.php",
        type:"POST",
        data:{days: $("#days").val(),city:$("#auto_city").val()},
        beforeSend:function(){
            $("#autoUpdateDiv").addClass('loading');
        },
        success:function(res){
         $("#autoUpdateDiv").removeClass('loading');
         if(res.success == 1){
           Toast.success('تم تحديث ما بذمه المندوبين الى واصل');
           //getorders();
         }else{
           Toast.warning('خطأ');
         }
         console.log(res);
        },
        error:function(e){
          $("#autoUpdateDiv").removeClass('loading');
          console.log(e);
        }
      });
}
function CurrencyFormatted(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val =  left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val =  input_val;

    // final formatting
    if (blur === "blur") {
      input_val += ".00";
    }
  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}
function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/[^0-9\-]+/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function setOrderOutStorage(id){
        $.ajax({
        url:"script/_setOrderOutStorage.php",
        type:"POST",
        data:{id:id},
        success:function(res){
         if(res.success == 1){
           Toast.success('تم اخرج الطلب من المخزن');
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
function setOrderStorage(id){
        $.ajax({
        url:"script/_setOrderStorage.php",
        type:"POST",
        data:{id:id},
        success:function(res){
         if(res.success == 1){
           Toast.success('تم الادخال الى المخزن');
           getorders();
           $("#order_no").focus();
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

$( document ).ready(function(){

$("#allselector").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('input[name="id\[\]"]').attr('checked', false);;
    }else{
      $('input[name="id\[\]"]').attr('checked', true);;
    }
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
getBraches($("#e_branch"));
getBraches($("#e_branch_to"));
getCities($("#e_city"));
getBraches($("#branch"));
getorderStatus($("#orderStatus"),1);
getorderStatus($("#status_action"),1);
getorderStatus($("#setOrderStatus"),1);
getCities($("#city"));
getCities($("#auto_city"));
getorders();
});
</script>
  <div class="modal fade" id="editorderStatusModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">تحديث حالة الطلب</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="addClientForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>الحالة</label>
						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="orderStatus" id="orderStatus"  value="">
                        </select>
                        <span class="form-text text-danger" id="orderStatus_err"></span>
					</div>
					<div class="form-group">
						<label>ملاحظات:</label>
						<input type="name" name="rderStatus_note" class="form-control"  placeholder="">
						<span class="form-text  text-danger" id="orderStatus_note_err"></span>
					</div>
                    <div class="input-group date">
						<input size="16" type="text"  readonly class="form-control form_datetime"  placeholder="الوقت والتاريخ" id="orderStatus_date">
						<div class="input-group-append">
							<span class="input-group-text">
							<i class="la la-calendar glyphicon-th"></i>
							</span>
						</div>
						<span class="form-text  text-danger" id="orderStatus_date_err"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addClient()" class="btn btn-brand">اضافة</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>
            <!--begin::Page Scripts(used by this page) -->
                            <script src="./assets/js/demo1/pages/components/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
