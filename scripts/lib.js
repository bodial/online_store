function sendForm(e){
     
    // получаем значение поля key
    var keyBox = document.search.key;
    var val = keyBox.value;
    if(val.length>5){
        alert("Недопустимая длина строки");
        e.preventDefault();
    }   
    else
        alert("Отправка разрешена");
}

function hide_text_and_btns(text_id, hide_btn, show_btn) {
	text_id.classList.add('hidden');
	hide_btn.classList.add('hidden');
	show_btn.classList.remove('hidden');
};
function show_text_and_btns(text_id, hide_btn, show_btn) {
	text_id.classList.remove('hidden');
	show_btn.classList.add('hidden');
	hide_btn.classList.remove('hidden');
};
	
function initialize(id){
	let input_field = document.getElementById(id);
	input_field.value = "17";
};

function initialize_cart_data(length, prices_of_instruments){
	for (id in prices_of_instruments)
	{
		let minus_btn_id = 'minus_' + id;
		let plus_btn_id = 'plus_' + id;
		let input_field_id = 'input_' + id;
		let price_field_id = 'full_price_of_instr_id' + id;
		let minus_btn = document.getElementById(minus_btn_id); //клавиша -
		let plus_btn = document.getElementById(plus_btn_id); //клаваиша +
		let input_field = document.getElementById(input_field_id); //поле ввода количества данного инструмента
		let price_field = document.getElementById(price_field_id); //поле цены одного инструмента
		let price_of_one = prices_of_instruments[id];
		initialize_prices_change_due_to_btns(prices_of_instruments, plus_btn, minus_btn, input_field, price_field, price_of_one);
		initialize_prices_change_due_to_input(prices_of_instruments, input_field, price_field, price_of_one);
	};
};

function initialize_prices_change_due_to_input(prices_of_instruments, input_field, price_field, price_of_one){ //изменение цен из-за input
	input_field.addEventListener('change', function(){
		if (this.value < 0) //ввели меньше 0
		{
			this.value = 0;
		}
		if (!Number.isInteger(this.value)) //ввели дробное
		{
			this.value = Math.round(Number(this.value))
		}
		full_price_of_cart(prices_of_instruments);
		price_field.innerHTML = Number(price_of_one) * Number(this.value);
		});
};

function full_price_of_cart(prices_of_instruments){ //считает цену всей коризны и выводит в элемент с id = full_price_field
	let full_price = 0;
	let full_price_field_id = 'full_cart_price';
	let full_price_field = document.getElementById(full_price_field_id);
	for (id in prices_of_instruments)
	{
		let input_field_id = 'input_' + id;
		let input_field = document.getElementById(input_field_id);
		let amount_of_one = input_field.value;
		let price_of_one = prices_of_instruments[id]
		full_price += Number(price_of_one) * Number(amount_of_one);
			
	};
	full_price_field.innerHTML = Number(full_price);
};

function initialize_prices_change_due_to_btns(prices_of_instruments, plus_btn, minus_btn, input_field, price_field, price_of_one){ //изменение цен из-за кнопок +/-
	minus_btn.addEventListener('click', function(){
			if (Number(input_field.value) > 0)
			{
				input_field.value = Number(input_field.value) - Number(1);
				price_field.innerHTML = Number(price_of_one) * Number(input_field.value);
				full_price_of_cart(prices_of_instruments);			
			}
		});
	plus_btn.addEventListener('click', function(){
			input_field.value = Number(input_field.value) + Number(1);
			price_field.innerHTML = Number(price_of_one) * Number(input_field.value);
			full_price_of_cart(prices_of_instruments);
		});
};

function sendform_editing_instrument(){ //проверка формы редактирования или создания инструмента
	var editing_instrument = document.getElementById("editing_instrument");
	if (editing_instrument.remain.value < 0)
	{
		alert('Остаток не может быть меньше 0');
		editing_instrument.remain.focus();
		return false;
	}
	if (editing_instrument.price.value <= 0  || editing_instrument.price.value == "")
	{
		alert('Цена не может быть меньше или равна 0');
		editing_instrument.price.focus();
		return false;
	}
	if (editing_instrument.name.value == "")
	{
		alert('Имя не может быть пустым');
		editing_instrument.name.focus();
		return false;
	}
	if (editing_instrument.image_preview.src == document.location.href)
	{
		alert('Загрузите картинку');
		editing_instrument.image_upload.focus();
		return false;
	}
	if (editing_instrument.path.value == "")
	{
		alert('Выберите место в каталоге');
		editing_instrument.path.focus();
		return false;
	}
	return true;	
};

function initialize_catalog(paths_map, up_map, down_map){
	go_to_catalog(paths_map, down_map);
	var hidden_path = document.getElementById("hidden_path");
	console.log('down_map');
	console.log(down_map);
	console.log('up_map');
	console.log(up_map);
	let up_btn = document.getElementById('up_btn');
	up_btn.addEventListener("click", function(){
		catalog_up(paths_map, up_map, down_map);
	});
	for (number in paths_map)
	{
		let catalog_element_id = paths_map[number];
		let catalog_element = document.getElementById(catalog_element_id);
		catalog_element.addEventListener("click", function(){
			console.log(this.id);
			current_path = this.id;
			hidden_path.value = current_path;
			go_to_catalog(paths_map, down_map);
			
		});
	}
}

function catalog_up(paths_map, up_map, down_map){
	if (current_path != '0')
	{
		current_path = String(up_map[current_path]);
		hidden_path.value = current_path;
		console.log('НАЖАЛ на кнопку вверх, новый текущий путь ' + current_path);
		go_to_catalog(paths_map, down_map);
	}
	else
	{
		alert('выше нельзя');
	}
}

 function go_to_catalog(paths_map, down_map){ //переходим в папку по пути current_path
	hide_all_folders_in_catalog(paths_map);
	let folder = down_map[current_path];
	if (folder)
	{
		
		for (number in folder)
		{
			//console.log(folder[number]);
			show_folder_in_catalog(folder[number]);
		}
		empty_folder('hide');
	}
	else
	{
		empty_folder('show');
		console.log('пустая папка');
	}
}

function empty_folder(action){
	let empty_folder = document.getElementById('empty_folder');
	if (action == 'hide')
	{
		empty_folder.classList.add('hidden');
	}
	else
	{
		empty_folder.classList.remove('hidden');
	}
}

function hide_all_folders_in_catalog(paths_map){
	for (number in paths_map)
	{
		let new_id = 'li_' + paths_map[number];
		console.log('HIDE ' + new_id);
		let folder_to_hide = document.getElementById(new_id);
		//let folder_to_hide = document.getElementById(paths_map[number]);
		folder_to_hide.classList.add('hidden');
	}
}

function show_folder_in_catalog(id){
	let new_id = 'li_' + id;
	console.log('SHOW ' + new_id);
	let folder_to_show = document.getElementById(new_id);
	//let folder_to_show = document.getElementById(id);
	folder_to_show.classList.remove('hidden');
}

function initialize_catalog_name_change(paths){
	for (number in paths)
	{
		let hidden_element_id = 'editing_catalog[' + paths[number] + ']';
		let btn_id = 'change_name[' + paths[number] + ']';
		let hidden_element = document.getElementById(hidden_element_id);
		let btn = document.getElementById(btn_id);
		btn.addEventListener('click', function(){
			if (hidden_element.classList.contains('hidden'))
			{
				hidden_element.classList.remove('hidden');
			}
			else
			{
				hidden_element.classList.add('hidden');
			}
		});
	}
}

function validate_delete_catalog(form_obj, action){	
	let result = true;
	if (action == 'change' && form_obj.change_catalog_name.value == '')
	{
		alert('Введите название');
		result = false;
	}
	if (action == 'delete')
	{
		result = confirm('Удаление каталога приведет к удалению всех каталогов внутри него, вы уверены?');
	}
	return result;
}

function initialize_new_catalog_creation(){
	let show_btn = document.getElementById('create_new_catalog_btn');
	let hide_btn = document.getElementById('create_new_catalog_btn_cancel');
	let new_catalog = document.getElementById('create_new_catalog');
	hide_btn.addEventListener('click', function(){
		hide_text_and_btns(new_catalog, hide_btn, show_btn);
	});
	show_btn.addEventListener('click', function(){
		show_text_and_btns(new_catalog, hide_btn, show_btn);
	});
}

function validate_create_new_catalog(){	
	let result = confirm('При создании каталога в каталоге с инструментами, все артикулы инструменты из него перейдут в новый созданный каталог. Вы уверены?');
	return result;
}
