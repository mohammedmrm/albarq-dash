<?php
if(file_exists("script/_access.php")){
  require_once("script/_access.php");
  access([1,2,5,3,7,8,9]);
}
?>
<?
include_once("config.php");
?>
<!-- end:: Subheader -->
					<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="">
				منفيست المندوبين
			</h1>
		</div>
	</div>

	<div class="kt-portlet__body">
     <form id="genrateManifestForm">
		<!--begin: Datatable -->


          <fieldset><legend>فلتر</legend>
          <div class="row">
          <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
               <div class="form-group">
						<label>الفرع:</label>
						<select  onchange="genrateManifest()" id="branch" name="branch" class="form-control selectpicker"  data-show-subtext="true" data-live-search="true" ></select>
						<span class="form-text  text-danger" id="item_err"></span>
			   </div>
          </div>
            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية :</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input value="<?php echo date('Y-m-d 00:00'); ?>" onchange="genrateManifest()" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input  type="text" onchange="genrateManifest()" class="form-control kt-input" name="end"  id="end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label class="">.</label><br />
                    <input  type="button" value="بحث" onclick="genrateManifest()"  class="btn btn-warning" placeholder="" data-col-index="1">
            </div>
           </div>
          </fieldset>
		<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="tb-manifest">
			       <thead>
	  						<tr>
								<th>المندوب</th>
								<th>عدد الطلبيات</th>
								<th>طباعه</th>
							</tr>
      	            </thead>
                            <tbody id="manifestTable">
                            </tbody>
		</table>
        <div class="kt-section__content kt-section__content--border">
		<nav aria-label="...">
			<ul class="pagination" id="pagination">

			</ul>
        <input type="hidden" id="p" name="p" value="<?php if(!empty($_GET['p'])){ echo $_GET['p'];}else{ echo 1;}?>"/>
		</nav>
     	</div>
        </form>
		<!--end: Datatable -->
	</div>
</div>
</div>
<!-- end:: Content -->
<div class="modal fade" id="ManfiestList" role="dialog">
    <div class="modal-dialog modal-xl">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h4 class="modal-title">الطلبيات المسجله في المخزن</h4>
        </div>
        <div class="modal-body">
        <form id="ordertabledata" class="kt-form kt-form--fit kt-margin-b-20">
        <fieldset><legend>فلتر</legend>
          <div class="row kt-margin-b-20">
             <input type="hidden" value="0" id="driver_id" name="driver_id" />
             <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label>الصفحه:</label>
                	<select data-size="5" data-actions-box="true" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control kt-input" id="store1" name="store1" data-col-index="7">
                		<option value="">Select</option>
                	</select>
             </div>
             <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
              <label>الفترة الزمنية :</label>
              <div class="input-daterange input-group" id="kt_datepicker">
    				<input value="<?php echo date('Y-m-d 00:00'); ?>" onchange="" type="text" class="forcm-ontrol kt-input" name="start" id="start1" placeholder="من" data-col-index="5">
    				<div class="input-group-append">
    					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
    				</div>
    				<input  type="text" class="form-control kt-input" name="end"  id="end1" placeholder="الى" data-col-index="5">
            	</div>
             </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label class="">.</label><br />
                    <input  type="button" value="بحث" onclick="details($('#driver_id').val())"  class="btn btn-warning" placeholder="" data-col-index="1">
            </div>

          </div>
          <div class="row">
              <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label>اسناد الى مندوب:</label>
                	<select data-size="5" data-actions-box="true" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control kt-input" id="driver1" name="driver1" data-col-index="7">
                		<option value="">Select</option>
                	</select>
              </div>
              <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                <button  type="button" value="" onclick="saveCustom()"  class="btn btn-clean text-success" placeholder="" data-col-index="1"><span class="fa fa-save fa-4x "></span></button>
              </div>
          </div>
        </fieldset>
		<!--begin::Portlet-->
		<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="tb-manifestlist">
			       <thead>
	  						<tr>
								<th><input  id="allselector" type="checkbox"><span></span></th>
								<th>رقم الوصل</th>
								<th>مبلغ الوصل</th>
								<th>مبلغ التوصيل</th>
								<th>اسم البيج</th>
								<th>التاريخ</th>
								<th>العنوان</th>
							</tr>
      	            </thead>
                    <tbody id="manifestlist">
                    </tbody>
		</table>
        <!--end::Portlet-->
         </form>
        </div>
      </div>

    </div>
  </div>

<!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<!--end::Page Vendors -->



<!--begin::Page Scripts(used by this page) -->
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script src="js/getStores.js" type="text/javascript"></script>
<script src="js/getAllDrivers.js" type="text/javascript"></script>
<script type="text/javascript">
getBraches($("#branch"));
getStores($("#store1"));
getAllDrivers($("#driver1"));
function genrateManifest(){
      $.ajax({
        url:"script/_getDriverUnconfirmedOrders.php",
        type:"POST",
        beforeSend:function(){
          $("#genrateManifestForm").addClass("loading");
        },
        data:$("#genrateManifestForm").serialize(),
        success:function(res){
          console.log(res);

         $("#genrateManifestForm").removeClass("loading");
         $("#tb-manifest").DataTable().destroy();
         $("#manifestTable").html("");
         if(res.success == 1){
            $.each(res.data,function(){
              $("#manifestTable").append(
              "<tr>"+
              "<td>"+
                this.driver_name+
              "</td>"+
              "<td>"+this.orders+"</td>"+
              "<td>"+
               '<button type="button" class="btn btn-clean" onclick="save('+this.driver_id+')"><span class="fa fa-save fa-2x "></span></button>'+
               '<button type="button" class="btn btn-clean " onclick="details('+this.driver_id+')" data-toggle="modal" data-target="#ManfiestList" ><span class="fa-2x flaticon-list"></span></button>'+
              "</td>"+
              "</tr>");
            })
         }else{
           Toast.warning(res.msg);
         }
         $("#tb-manifest").DataTable({
           "aLengthMenu": [25, 30, 50, 100],
         });

        } ,
        error:function(e){
          $("#genrateManifestForm").removeClass("loading");
          console.log(e);
        }
      });
}
function save(driver){
    $.ajax({
        url:"script/_confirmOrdersToDriver.php",
        type:"POST",
        beforeSend:function(){
          $("#genrateManifestForm").addClass("loading");
        },
        data:$("#genrateManifestForm").serialize()+"&driver="+driver,
        success:function(res){
          $("#genrateManifestForm").removeClass("loading");
          if(res.success == 1 ){
            window.open('script/downloadOrdersReport.php?start='+$('#start').val()+'&end='+$('#end').val()+'&driver='+this.id+'&orderStatus[0]=1&orderStatus[1]=2&orderStatus[2]=3&orderStatus[3]=13&orderStatus[4]=7&orderStatus[5]=8');
          }
          console.log(res);
          genrateManifest();
        },
        error:function(e){
          $("#genrateManifestForm").removeClass("loading");
          console.log(e);
        }
    });
}
function details(driver){
    $("#driver_id").val(driver);
    $("#driver1").val('0');
    $("#driver1").selectpicker('refresh');
     $('input[name="ids\[\]"]', form).remove();
      var form = $('#ordertabledata');
      $.each($('input[name="id\[\]"]:checked'), function(){
               rowId = $(this).attr('rowid');
         form.append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'ids[]')
                .val(rowId)
         );
      });
    $.ajax({
        url:"script/_getOrdersManfiest.php",
        type:"POST",
        beforeSend:function(){
          $("#ordertabledata").addClass("loading");
        },
        data:$("#ordertabledata").serialize()+"&driver="+driver,
        success:function(res){
          $("#manifestlist").html('');
          $("#ordertabledata").removeClass("loading");
          $("#tb-manifest").DataTable().destroy();
          $.each(res.data,function(){
             $("#manifestlist").append(
               '<tr>'+
                    '<td><input type="checkbox" name="id[]" rowid="'+this.id+'"><span></span></td>'+
                    '<td>'+this.order_no+'</td>'+
                    '<td>'+formatMoney(this.price)+'</td>'+
                    '<td>'+formatMoney(this.dev_price)+'</td>'+
                    '<td>'+this.store_name+'<br />'+(this.client_phone)+'</td>'+
                    '<td>'+this.date+'</td>'+
                    '<td>'+this.city+'/'+this.town+''+
                    '<br />'+(this.customer_phone)+'</td>'+
                '</tr>');
             });
             $('#tb-manifestlist').DataTable();
          console.log(res);
        },
        error:function(e){
          $("#ordertabledata").removeClass("loading");
          console.log(e);
        }
    });
}
function saveCustom(driver){
     $('input[name="ids\[\]"]', form).remove();
      var form = $('#ordertabledata');
      $.each($('input[name="id\[\]"]:checked'), function(){
               rowId = $(this).attr('rowid');
         form.append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'ids[]')
                .val(rowId)
         );
      });
    $.ajax({
        url:"script/_confirmOrdersToDriverCustom.php",
        type:"POST",
        beforeSend:function(){
          $("#ordertabledata").addClass("loading");
        },
        data:$("#ordertabledata").serialize(),
        success:function(res){
          $("#ordertabledata").removeClass("loading");
          if(res.success == 1){
            window.open('script/downloadOrdersReport.php?start='+$('#start1').val()+'&end='+$('#end1').val()+'&driver='+res.re_driver+'&orderStatus[0]=1&orderStatus[1]=2&orderStatus[2]=3&orderStatus[3]=13&orderStatus[4]=7&orderStatus[5]=8');
          }
          console.log(res);
          details(res.driver_id);
        },
        error:function(e){
          $("#ordertabledata").removeClass("loading");
          console.log(e);
        }
    });
}
$('#start').datetimepicker({
    format: "yyyy-mm-dd hh:ii",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$("#allselector").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('input[name="id\[\]"]').attr('checked', false);;
    }else{
      $('input[name="id\[\]"]').attr('checked', true);;
    }
});
$('#end').datetimepicker({
    format: "yyyy-mm-dd hh:ii",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#start1').datetimepicker({
    format: "yyyy-mm-dd hh:ii",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#end1').datetimepicker({
    format: "yyyy-mm-dd hh:ii",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
</script>
<script>


</script>