<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class='row'>

	<table class="table table-striped">


<?php

foreach ($model as $api => $posts) {

    ?>

		<tr>
			<td><label class="d-block w-100 text-uppercase pl-1">
<?php
    list ($method, $api_action_params) = explode(':', $api);
    $api_action = preg_replace('/\/{.*}/', '', $api_action_params);
    $api = $api_action;
    $api_url = Url::toRoute([
        $class . '/' . $api
    ], true);
    ?>
  </label>
			
			<td>
				<div class="form-group">
					<span> <?php echo $api_url ?></span>
<?php
    echo Html::textInput('action_' . $class . '-' . $api_action, $api, [
        'style' => 'width:50%',
        'class' => 'form-control w-100'
    ]);
    ?>
      
        </div>
			</td>
			<td>
				<div class="form-group d-flex justify-content-between mt-4">
					<a class="btn-sm btn btn-warning text-center text-white retest"
						id="retest_<?=$class?>-<?=$api_action;?>"
						api-id="<?=$api_action;?>" class-id="<?=$class;?>"
						mothod-id="<?= $method?>" api-url="<?php echo $api_url?>">Try Now
					</a> 
					
					<?php
    $arr = explode("?", $api, 2);
    if (! empty($posts)) {
        ?>
					<a data-id="<?=$arr[0]?>" href='javascript:'
						class="show-button btn btn-sm btn-sucess text-center">Show Params</a>
					<?php
    }
    ?>
			</div>
			</td>
		
		
		<tr>
			<td colspan="5">
				<form class="form-layout form-<?=$arr[0]?>"
					id="form_<?=$class?>-<?=$api_action;?>" action="<?=$api_url?>"
					method="<?php echo $method?>" enctype="multipart/form-data">
					<div class="card-body">
<?php

    if (isset($posts['params'])) {
        foreach ($posts['params'] as $param => $data) {
            ?>
            <div class="field form-group">
							<label>
<?php
            echo $param;
            ?></label>
							<div class="param d-flex input-group">
			<?php

            if (strstr($param, '_file'))
                echo Html::fileInput($param, $data, [
                    'id' => 'param'
                ]);
            else
                echo Html::textInput($param, $data, [
                    'id' => 'param',
                    'class' => 'form-control'
                ]);

            ?>
                 <div class="input-group-append cross-icon">
									<span class="input-group-text"><i class="fa fa-remove"></i></span>
								</div>
								<!--                 <div class="cross-icon ml-2">
							 <a id="remove" style="cursor: pointer;"><i class="fa fa-remove"></i></a>
                    </div> -->
							</div>
						</div>
			<?php
        }
    }
    ?>
        
        </div>
				</form>
			</td>
		</tr>

		</tbody>	
<?php
}

?>

</table>
</div>
<script>

var acccessToken = "<?php echo Yii::$app->request->get('access-token')?>";

$('#json-renderer').jsonViewer();
$("#copySuccess").hide();


$("#copyBtn") . click(function () {
    $("#json-renderer") . select();document . execCommand('copy');
    $("#copySuccess") . show() . delay(5000) . fadeOut();});
$('div .api-list form') . hide();

$(".show-button").click(function(e){
	var dataId  = $(this) . attr('data-id');console . log(dataId);
	$(".form-" + dataId) . toggle();
});



$("[id^=retest]").click(function(e){
	//startPopUp();
	var api = $(this).attr('api-id');	
	var classid = $(this).attr('class-id');	
	var method = $(this).attr('mothod-id');
	var apiUrl = $(this).attr('api-url');	
	var action = $("[name=action_"+classid+"-"+api+"]").val();	
	
	var values = $("#form_"+classid+"-"+api).serializeArray();
	var fd = new FormData();
	var file = $("#form_"+classid+"-"+api).find('input[type="file"]');
	
	if(typeof file[0] != 'undefined') { 
		var file_data = file[0].files; // for multiple files
		var name = $("#form_"+classid+"-"+api).find('input[type="file"]').attr('name');
		fd.append(name, file_data[0]);
	}
    $.each(values,function(key,input){
        fd.append(input.name,input.value);
    });
    
    $('#json-renderer').val('');
    $('#json-display').html('');
    var url = setUrlParams(apiUrl , 'access-token', acccessToken);
	$.ajax({
		url: url ,
		type:method,
		data:fd,
		contentType:false,
		processData:false,
		success:function(response) {	
			
			console.log('res:'+JSON.stringify(response));
			
			$("span#response_"+classid+'-'+api).removeClass('btn-primary').addClass('btn-success');
			if(acccessToken == "" &&  action == 'login' && response['access-token'])
			{
					acccessToken = response['access-token'];
					window.location = setUrlParams("<?=Url::current([],true) ?>", 'access-token', acccessToken);
			}
			
			$("#response_"+classid+'-'+api).html(response.status);
			$('#json-renderer').jsonViewer(response);
			$("#translate").trigger('click');
		},
		error:function(xhr, status, error)
		{
			
			$('#json-renderer').jsonViewer(xhr.responseJSON);
				
			$("#translate").trigger('click');	
			$("span#response_"+classid+'-'+api).html(error);
		}
		
		});
});
$("[id^=remove]").click(function(){
	$(this).parent().find('#param').attr('disabled',true);
	$(this).parent().parent().hide();	
});
function setUrlParams(url, key, value) {
if ( value == "" ) return url;
  url = url.split('?');
  usp = new URLSearchParams(url[1]);
  usp.set(key, value);
  url[1] = usp.toString();
  return url.join('?');
}

</script>