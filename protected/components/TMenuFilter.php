<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\components\TBaseWidget;
use yii\helpers\Html;

class TMenuFilter extends TBaseWidget
{

    public function renderHtml()
    {
        \Yii::$app->view->registerCss('input#filterInput {
            background-color: #ebebeb;
border-radius: 5px;
        }
');
        echo Html::textInput('filterInput', null, [
            'id' => 'filterInput',
            'class' => 'form-control',
            'placeholder' => 'Search ...'
        ]);
        echo \Yii::$app->view->registerJs("
  $(document).on('keyup', '#filterInput', function(){
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('filterInput');
            filter = input.value.toUpperCase();

        li = document.querySelectorAll('.sidebar-left-info li');
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName('a')[0];
            if(a){
                txtValue = a.textContent || a.innerText;
                $( li[i] ).children( '.child-list' ).parent('.menu-list').removeClass('main-class');
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                 	li[i].style.cssText = '';
                 	if($( li[i] ).children( '.child-list' ).length > 0){
                 		$( li[i] ).children( '.child-list' ).parent('.menu-list').addClass('main-class');
                 	}
                } else {
                	li[i].style.cssText = 'display:none !important';
                    if($( li[i] ).parent( '.child-list' ).parent('.menu-list').length > 0){
                    	var parentStyle = $( li[i] ).parent( '.child-list' ).parent('.menu-list').css('display');
                    	$( li[i] ).parent( '.child-list' ).parent('.menu-list').addClass('nav-active');
                		if($( li[i] ).parent( '.child-list' ).parent('.menu-list').children('ul').length) {
                		
                		 	var flag = 0;
                		 	$( li[i] ).parent( '.child-list' ).parent('.menu-list').children('ul').children('li').each(function(){
                                if($(this).css('display') == 'block') {
                                	 flag = 1;
                                }
                            });
                            if(flag == 1){
                            var divStyle = $( li[i] ).parent( '.child-list' ).parent('.menu-list').prop('style');
                				divStyle.removeProperty('display');
            				 var childStyle = $( li[i] ).parent( '.child-list' ).prop('style');
            					childStyle.removeProperty('display');
                            }else{
                            	$( li[i] ).parent( '.child-list' ).parent('.menu-list').show()[0].style.cssText = 'display:none !important';
                			}
                         }
                    }
                    if($( li[i] ).children( '.child-list' ).length > 0){
                		 	var flags = 0;
                		 	$( li[i] ).children( '.child-list' ).children('li').each(function(){
                                if($(this).css('display') == 'block') {
                                	 flags = 1;
                                }
                            });
                            if(flags == 1){
                            $( li[i] ).addClass('nav-active');
                            var divStyle = $( li[i] ).prop('style');
                				divStyle.removeProperty('display');
                            }else{
                            	li[i].style.cssText = 'display:none !important';
                			}
                    }
                    
                    if($( li[i] ).parent( '.child-list' ).parent('.menu-list').hasClass('main-class')){
                    	var parentStyle = $( li[i] ).parent( '.child-list' ).parent('.menu-list').prop('style');
                				parentStyle.removeProperty('display');
                				
                			$( li[i] ).parent( '.child-list' ).parent('.menu-list').children('ul').children('li').each(function(){
                                this.style.cssText = '';
                            });
                    }
                }
                 if(filter == ''){
                 	$( li[i] ).parent( '.child-list' ).parent('.menu-list').removeClass('nav-active');
                 }
             }
        }
        });
");
    }
}

