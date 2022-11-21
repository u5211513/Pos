 
function redirect(url) {
	location.replace(url);
}

function pageChange(url) {
	window.open(url, '_self');
}

function confirmDelete(msg, url) {
	if (confirm(msg) == true) {
		location.replace(url);
	}
}

function extenderElement(elementName) {
	var obj = window.document.getElementById(elementName).value;
	opener.document.getElementById(elementName).value = obj;
	window.close();
}

 
//////////////////////////////************* Event Register***********************/

function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function () {
			oldonload();
			func();
		}
	}
}
 

/************************สร้างฟังก์ชั้นแยกตัวเลข ออกจากตัวแปรข้อความ *********************************/
function extract_int($str) {
	preg_match('/[^0-9]*([0-9]+)[^0-9]*/', $str, $regs);
	return (intval($regs[1]));
}
// $a="Test  123 ";
// echo extract_int($a);
// output 123

/*******************************************************************************************/
/************************ สร้างฟังก์ชั้นแยกตัวเลข ออกจากตัวแปรข้อความ *********************************/
function extract_int($str) {
	$str = str_replace(",", "", $str);
	preg_match('/[[:digit:]]+\.?[[:digit:]]*/', $str, $regs);
	return (doubleval($regs[0]));
}
// $a="test  5,555.02   ";  
// echo extract_int($a);  
// output 5555.02 


/*******************************************************************************************/
/************************ สร้างฟังก์ชั้นแยกตัวเลข ออกจากตัวแปรข้อความ *********************************/
function extract_int($str) {
	$str = str_replace(",", "", $str);
	preg_match('/[[:digit:]]+\.?[[:digit:]]*/', $str, $regs);
	return (doubleval($regs[0]));
}
// $a="test  5,555.02   ";  
// echo extract_int($a);  
// output 5555.02 


////////////////////เติม , (คอมมา)/////////////////////////
function dokeyupf(obj) {
	var key = Event.keyCode;
	if (key != 37 & key != 39 & key != 110) {
		var value = obj.value;
		var svals = value.split("."); //แยกทศนิยมออก
		var sval = svals[0]; //ตัวเลขจำนวนเต็ม

		var n = 0;
		var result = "";
		var c = "";
		for (a = sval.length - 1; a >= 0; a--) {
			c = sval.charAt(a);
			if (c != ',') {
				n++;
				if (n == 4) {
					result = "," + result;
					n = 1;
				};
				result = c + result;
			};
		};

		if (svals[1]) {
			result = result + '.' + svals[1];
		};

		obj.value = result;
	};
};

//ให้ text รับค่าเป็นตัวเลขอย่างเดียว
function checknumber() {
	key = Event.keyCode;
	if (key != 46 & (key < 48 || key > 57)) {
		Event.returnValue = false;
	};
};

function formatCurrency(number) {
	number = parseFloat(number);
	return number.toFixed(2).replace(/./g, function (c, i, a) {
		return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
	});
}