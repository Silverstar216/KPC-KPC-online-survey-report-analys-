//add chkd

var gst='all';
var selected = [];


//그룹검색
function SeachBtnClick(obj_id)
{
	var val = $.trim($('#'+obj_id).val());
	gst = val;
	$("#input_groupadd").val('');//그룹추가 내용삭제
	$(".shmemo").hide();//메모숨기기
	getGroupList();
    // location.href = site_url + "phone?st="+st+"&gst="+gst+"&stval="+stval+"&what="+what;
	
}

//======현재사용안함== 그룹에 사용자추가부분
function book_submit()
{
 var sval = $('.chosen-select').val();
 if(sval==null || sval=='')
 {
	swal({title: '', text: '그룹을 선택하세요.',
		confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
		function(isConfirm) {});
	return;
 }
 var bkname = $.trim($('#bk_name').val());
 var bkhp = $.trim($('#bk_hp').val());
 if($.isNumeric(bkhp)===false)
{
	swal({title: '', text: '휴대폰번호를 정확히 입력하세요!\n 입력형식:0123456789.',
		confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
		function(isConfirm) {});
  return;
 }
 var address_num = $.trim($('#address_num').val());
 var bkmemo = $.trim($('#bk_memo').val());
 var w = $.trim($('#what').val());
 var mobileid=$.trim($('#mobileid').val());
/* var para="groups="+sval+"&username="+bkname+"&mobile="+bkhp+"&address_num="+address_num+"&memo="+bkmemo+"&what="+w+"&mid="+mobileid;*/

    $.ajax({
            url: site_url + "phone/setMobileUsr",
            cache:false,
            timeout : 10000,
        type: 'POST',
        data: {
            groups: sval,
            username: bkname,
            mobile: bkhp,
            address_num: address_num,
            memo : bkmemo,
            what : w,
            mid :mobileid
        },
            success: function(data) {
               if (data == 'err') {
               	swal({title: '', text: '등록오류!\n 다시 시도하세요!',
					confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
					function(isConfirm) {});
				   
               } else {
				   if(w=='reg')
				   {
                    $('.chosen-select').val('');
					$(".chosen-select").trigger("chosen:updated");
					$('#bk_name').val('');
					$('#bk_hp').val('');
					$('#address_num').val('');
					$('#bk_memo').val('');
					swal({title: '', text: "정확히 등록되었습니다!",
						confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
						function(isConfirm) {});
					
				   }
					else
						swal({title: '', text: "정확히 수정되었습니다!",
							confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
							function(isConfirm) {});
						
               }
            },
            error: function (xhr, ajaxOptions, thrownError) {
            	swal({title: '', text: xhr.status,
					confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
					function(isConfirm) {});
                
            }
        });

}
//==========


function addGroupEventlisner()
{
	 var tal = $('#gtotal').val();
    if(Number(tal) > 0){
        $('.serv_t').text('총 개수 '+tal+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('등록된 번호가 없습니다.');//phonenumberCont
        $('.serv_t').css('color','#e02222');
    }
	 
	$(".phonecursorImg").click(function(){
		var thisid=$(this).attr('id');
		var id=thisid.split('_')[1];
		location.href=site_url+"phone/Phonenumber?ngst="+id;
		/*
        var obj = $( ".sub_title1" ).offset();//p.position()
			$("#gdetailview").css({
			   "position" : "absolute",
			   "top" : obj.top + 150,
			   "left" : obj.left + 150
			}).show();
			*/
		});
	$(".gpclose").click(function(){
		$("#gdetailview").hide();
	});
	
	$('.groupExChange').change(function() {
		var thisid=$(this).attr('id');
		var id=thisid.split('_')[1];
		if(parseInt(id) < 1)
		{
			swal({title: '', text: "이 그룹에 속한 번호가 없습니다.",
				confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
				function(isConfirm) {});
			
			return;
		}
		var changegroup=$(this).val();
		changegroup = $.trim(changegroup);
		if(changegroup !=="")
		{
			var preid=changegroup.split('_')[0];
			var newid=changegroup.split('_')[1];
			var param = "preid="+preid+"&newid="+newid;
			$.ajax({
            url: site_url+"phone/changeGroup",
            cache:false,
            timeout : 10000,
            dataType:'text',
            data:param,
            type:'get',
            success: function(data) {
               if (data !== 'err')
			   {
                   swal({title: '', text: '그룹이동성공!',
                           confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                       function(isConfirm) {});
				  getGroupList();
               }
               else {
                    swal({title: '', text: '일부 중복번호들의 이동이 취소되었습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function(isConfirm) {});
                    getGroupList();
               }
            },
            error: function (xhr, ajaxOptions, thrownError) {
            	swal({title: '', text: xhr.status,
					confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
					function(isConfirm) {});
                
            }
        });
			
		}
	});

	$('#chkall').click(function(){
        if ($(this).is(':checked')) 
		{
			
			$("input:checkbox").prop('checked',true);  
			$('input:checked').each(function() {
				var chkid=$(this).attr('id');
				if(chkid!="chkall")
				{
				  //selected.push($(this).attr('id'));
				  if(selected.length > 0)
				  {
					for(var i=0;i<selected.length;i++)
					{
						//var gid=parseInt(selected[i]);
						var id = selected[i];
						if(chkid !== id)
						{
							selected.push(chkid);
							break;
						}
					}
				  }
				  else
				  {
					selected.push(chkid);
				  }
				 
				}
				
			});			
        } 
		else 
		{
			$("input:checkbox").prop('checked',false); 
			selected = [];			
        }
		
	});
	$('input:checkbox').click(function(){
		var chkid=$(this).attr('id'); 
			if(chkid=="chkall")
				return;
		$('#chkall').prop('checked',false);
			
		 if ($(this).is(':checked'))
		 {
			selected.push($(this).attr('id'));
		 }
		 else
		 {
			 var temp = [];
			for(var i=0;i<selected.length;i++)
			{
				//var gid=parseInt(selected[i]);
				var id = selected[i];
				if(chkid == id)
				{
					temp.push(id);
				}
			}
           	selected = [];
           	selected = temp;
            temp = [];			
		 }
	});
	
	//그룹명변경
	$(".groupnamechange").click(function(){
		var clen = selected.length;
		if(clen==0)
		{
			swal({title: '', text: '변경할 그룹을 체크하세요!',
					confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
					function(isConfirm) {});
			
			return;
		}
		var changecont=[];
		for(var i=0;i<clen;i++)
		{
			//var gid=parseInt(selected[i]);
			var gid = "gn_"+selected[i];
			var val=$('#'+gid).val();
			var idval=selected[i]+"_"+val;
			changecont.push(idval);
		}
		
		$.ajax({
            url: site_url + "phone/changeGroupName",
            cache:false,
            timeout : 10000,
            dataType:'text',
            data:"changecont="+changecont,
            type:'post',
            success: function(data) {
               if (data == 'err') 
			   {
				   	swal({title: '', text: '그룹이름변경 오류!',
						confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
						function(isConfirm) {});
				   
               } 
			   else
			   {
				  $("#groups option").remove();
				  var json = $.parseJSON(data);
					//now json variable contains data in json format
					//let's display a few items
					$('#groups').append('<option value="all">전체</option>');
					for (var i=0;i<json.length;++i)
					{
						$('#groups').append('<option value="'+json[i].id+'">'+json[i].name+'</option>');
					}
					$("input:checkbox").prop('checked',false); 
					selected = [];
                   swal({title: '', text: '그룹변경성공!',
                           confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                       function(isConfirm) {});
			   }
            },
            error: function (xhr, ajaxOptions, thrownError) {
            	swal({title: '', text: xhr.status,
					confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
					function(isConfirm) {});
                
            }
        });
		
	});
	
		
	//그룹명, 내용삭제
	$(".groupnamecontdel").click(function(){
		var clen = selected.length;
		if(clen==0)
		{
			swal({title: '', text: '삭제할 그룹을 체크하세요!',
						confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
						function(isConfirm) {});
			return;
		}
        swal({
                title: '', text: '정말 삭제하시겠습니까?',
                allowOutsideClick: false,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                cancelButtonClass: 'btn-default',
                closeOnConfirm: true,
                closeOnCancel: true,
                confirmButtonText: '삭제',
                cancelButtonText: '취소',
                type: 'warning'
            },
            function (isConfirm) {
                if (isConfirm) {
                    var changecont = [];
                    for (var i = 0; i < clen; i++) {
                        //var gid=parseInt(selected[i]);
                        var gid = "gn_" + selected[i];
                        var val = $('#' + gid).val();
                        var idval = selected[i] + "_" + val;
                        changecont.push(idval);
                    }

                    $.ajax({
                        url: site_url + "phone/delete_groupCont",
                        cache: false,
                        timeout: 10000,
                        dataType: 'text',
                        data: "changecont=" + changecont,
                        type: 'post',
                        success: function (data) {
                            if (data == 'err') {
                                swal({
                                        title: '', text: '그룹삭제 오류!',
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                    },
                                    function (isConfirm) {
                                    });

                            }
                            else {
                                swal({
                                        title: '', text: '그룹 삭제성공!',
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                    },
                                    function (isConfirm) {
                                    });
                                $("#groups option").remove();
                                var json = $.parseJSON(data);
                                //now json variable contains data in json format
                                //let's display a few items
                                $('#groups').append('<option value="all">전체</option>');
                                for (var i = 0; i < json.length; ++i) {
                                    $('#groups').append('<option value="' + json[i].id + '">' + json[i].name + '</option>');
                                }

                                $("input:checkbox").prop('checked', false);
                                selected = [];

                                getGroupList();

                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal({
                                    title: '', text: xhr.status,
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });
                        }
                    });
                }

            });
		
	});
	
	//그룹내용만삭제
	$(".groupcontdel").click(function(){
		var clen = selected.length;
		if(clen==0)
		{
			swal({title: '', text: '삭제할 그룹을 체크하세요!',
						confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
						function(isConfirm) {});
			return;
		}
        swal({
                title: '', text: '정말 삭제하시겠습니까?',
                allowOutsideClick: false,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                cancelButtonClass: 'btn-default',
                closeOnConfirm: true,
                closeOnCancel: true,
                confirmButtonText: '삭제',
                cancelButtonText: '취소',
                type: 'warning'
            },
            function (isConfirm) {
                if (isConfirm) {
                    var changecont = [];
                    for (var i = 0; i < clen; i++) {
                        //var gid=parseInt(selected[i]);
                        var gid = "gn_" + selected[i];
                        var val = $('#' + gid).val();
                        var idval = selected[i] + "_" + val;
                        changecont.push(idval);
                    }

                    $.ajax({
                        url: site_url + "phone/delete_contOfGroup",
                        cache: false,
                        timeout: 10000,
                        dataType: 'text',
                        data: "changecont=" + changecont,
                        type: 'post',
                        success: function (data) {
                            if (data == 'err') {
                                swal({
                                        title: '', text: '그룹내용삭제 오류!',
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                    },
                                    function (isConfirm) {
                                    });

                            }
                            else {
                                $("input:checkbox").prop('checked', false);
                                selected = [];
                                swal({
                                        title: '', text: '그룹내용만 삭제성공!',
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                    },
                                    function (isConfirm) {
                                    });
                                getGroupList();
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal({
                                    title: '', text: xhr.status,
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });

                        }
                    });
                }
            });
		
	});
}

function getGroupList()
{
	var param="gst="+gst;
	  $.ajax({
            url: site_url + "phone/getGroupList",
            cache:false,
            timeout : 10000,
            dataType:'html',
            data:param,
            type:'get',
            success: function(data) {
               if (data !== 'err') {
				 $('#grouplistDiv').html(data);
                 addGroupEventlisner();				 
               } 
            },
            error: function (xhr, ajaxOptions, thrownError) {
            	swal({title: '', text: xhr.status,
						confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
						function(isConfirm) {});
                
            }
        });
}

//========================document.reay==========================================//
$(function () {
	 $("#input_groupadd").click(function(){
		 $(".shmemo").show();
	 });
	 $("#input_groupadd").hover(function(){
		// $(".shmemo").hide();
	 });
	 
	getGroupList();

	//그룹추가버튼
	$(".groupaddimg").click(function(){
        var user_level = $('#user_level').val();
        if(user_level =="" || user_level == undefined) { //가입하지않았을때
			alert('회원가입하여야 가능합니다.')
            location.href=site_url+"join/login_view"

        }else {
        	 if(user_level =="test") {
                 var tal = $('#gtotal').val();
                 if(Number(tal) ==1){
                     alert('회원가입하여야 가능합니다.')
                     location.href=site_url+"join/login_view"
					 return;
				 }
             }
            var w = $('#input_groupadd').val();
            var addstr = $.trim(w);
            var m = $('#input_memo').val();
            var memostr = $.trim(m);

            if (addstr !== '') {
                var param = "addstr=" + addstr + "&memo=" + memostr;
                $.ajax({
                    url: site_url + "phone/addGroup",
                    cache: false,
                    timeout: 10000,
                    type: 'POST',
                    data: {
                        addstr: addstr,
                        memo: memostr

                    },
                    success: function (data) {
                        if (data > 0) {
                            swal({
                                    title: '', text: '그룹 추가성공!',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                });
                            $("#input_groupadd").val('');//그룹추가 내용삭제
                            $("#input_memo").val("");//메모숨기기
                            $(".shmemo").hide();//메모숨기기
                            getGroupList();
                        } else if (data == -1) {
                            alert('그릅명 증복');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal({
                                title: '', text: xhr.status,
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                            },
                            function (isConfirm) {
                            });
                    }
                });
            }
            else {
                swal({
                        title: '', text: "추가할 그룹을 입력하세요!",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                    },
                    function (isConfirm) {
                    });
                return;
            }
        }

	});

	
	$(".phonecursorImg").click(function(){
		var w=$(this).attr('id');
		var cond=w.split('_')[0];
		var id=w.split('_')[1];
		if(cond=='c')
	        location.href=site_url+"phone/addPhoneNum?what=chg&mid="+id;
		else if(cond=='d')//삭제
		{
			var mobilenum = $(this).attr('role');
			location.href=site_url+"SmsSend/index?mobilenum="+mobilenum;
		}

	});

});
