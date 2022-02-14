// 하위분류호출
var categorychange = function(key, sel_id)
{
	var opt_nm = sel_id.replace(/[^0-9]/g, "");
	var opt_st = sel_id.replace(/[^a-z_]/g, "");

	for(var i=opt_nm; i<=5; i++) {
		$("select#"+opt_st+i+" option").remove();
		$("select#"+opt_st+i).prepend("<option value=\"\">= "+i+"차 분류 선택=</option>");
	}

	if(typeof(multi_select[key]) != 'undefined')
	{
		var option_add = multi_select[key].split(",");
		for(var i=0; i<option_add.length; i++) 
		{
			info = option_add[i].split("|");	
			$("select#"+sel_id).append("<option value=\""+info[0]+"\">"+info[1]+"</option>");
		}
	}
}

// 5단 분류 생성
var multiple_select = function(sel_id)
{
	var opt = "";
	var sel_ca1 = sel_id+'1';
	var sel_ca2 = sel_id+'2';
	var sel_ca3 = sel_id+'3';
	var sel_ca4 = sel_id+'4';
	var sel_ca5 = sel_id+'5';

	opt += "<select name=\""+sel_ca1+"\" id=\""+sel_ca1+"\" onchange=\"categorychange(this.value, '"+sel_ca2+"');\">\n";
	opt += "<option value=\"\">1차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca2+"\" id=\""+sel_ca2+"\" onchange=\"categorychange(this.value, '"+sel_ca3+"');\">\n";
	opt += "<option value=\"\">2차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca3+"\" id=\""+sel_ca3+"\" onchange=\"categorychange(this.value, '"+sel_ca4+"');\">\n";
	opt += "<option value=\"\">3차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca4+"\" id=\""+sel_ca4+"\" onchange=\"categorychange(this.value, '"+sel_ca5+"');\">\n";
	opt += "<option value=\"\">4차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca5+"\" id=\""+sel_ca5+"\">";
	opt += "<option value=\"\">5차 분류 선택</option>\n";
	opt += "</select>\n";

	document.write(opt);

	var option_add = multi_select["first"].split(",");
	for(var i=0; i<option_add.length; i++)
	{
		info = option_add[i].split("|");
		$("select#"+sel_ca1).append("<option value=\""+info[0]+"\">"+info[1]+"</option>");
	}
}

// 하위분류호출
var categorychange2 = function(key, sel_id)
{
	var opt_nm = sel_id.replace(/[^0-9]/g, "");
	var opt_st = sel_id.replace(/[^a-z_]/g, "");

	for(var i=opt_nm; i<=4; i++) {
		$("select#"+opt_st+i+" option").remove();
		$("select#"+opt_st+i).prepend("<option value=\"\">= "+i+"차 분류 선택=</option>");
	}

	if(typeof(multi_select[key]) != 'undefined')
	{
		var option_add = multi_select[key].split(",");
		for(var i=0; i<option_add.length; i++) 
		{
			info = option_add[i].split("|");	
			$("select#"+sel_id).append("<option value=\""+info[0]+"\">"+info[1]+"</option>");
		}
	}
}

// 4단 분류 생성
var multiple_select2 = function(sel_id)
{
	var opt = "";
	var sel_ca1 = sel_id+'1';
	var sel_ca2 = sel_id+'2';
	var sel_ca3 = sel_id+'3';
	var sel_ca4 = sel_id+'4';

	opt += "<select name=\""+sel_ca1+"\" id=\""+sel_ca1+"\" onchange=\"categorychange2(this.value, '"+sel_ca2+"');\">\n";
	opt += "<option value=\"\">1차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca2+"\" id=\""+sel_ca2+"\" onchange=\"categorychange2(this.value, '"+sel_ca3+"');\">\n";
	opt += "<option value=\"\">2차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca3+"\" id=\""+sel_ca3+"\" onchange=\"categorychange2(this.value, '"+sel_ca4+"');\">\n";
	opt += "<option value=\"\">3차 분류 선택</option>\n";
	opt += "</select>\n";
	opt += "<select name=\""+sel_ca4+"\" id=\""+sel_ca4+"\">";
	opt += "<option value=\"\">4차 분류 선택</option>\n";
	opt += "</select>\n";

	document.write(opt);

	var option_add = multi_select["first"].split(",");
	for(var i=0; i<option_add.length; i++)
	{
		info = option_add[i].split("|");
		$("select#"+sel_ca1).append("<option value=\""+info[0]+"\">"+info[1]+"</option>");
	}
}