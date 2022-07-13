function change (operation, request_data) {
    request_data = `operation=${operation}&${request_data}`;
    let request = new XMLHttpRequest();
    request.open('POST', 'change.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(request_data);
    request.addEventListener('loadend', function () {
        if (request.response.length > 0) {
            console.log(request.response)
        }
    });    
}

function add (belonging, p_id) {
    let text = document.querySelector(`#${belonging}`).querySelector('li.add input[type="text"]').value;
    if (text === '') {
        alert('Вы не ввели текст!');
    }
    else {
        let request_data = `then=${belonging}&text=${text}`;
        change('add', request_data);
        
        let add_li = document.querySelector('#'+belonging).querySelector('li.add');
        let new_li = document.createElement('li');
        new_li.innerHTML = `<input type='checkbox'> <p> ${text} </p> <button class='del'> <img src='../plans/src/delete.svg' alt='delete'> </button>`;
        let li_no_plans = document.querySelector('#'+belonging).querySelector('li.no_plans');
        if (li_no_plans) {
            let hr_no_plans = document.querySelector('#'+belonging).querySelector('hr.no_plans');
            li_no_plans.parentNode.removeChild(li_no_plans);
            hr_no_plans.parentNode.removeChild(hr_no_plans);
        }
        add_li.before(new_li);
        add_li.before(document.createElement('hr'));
        document.querySelector('#'+belonging).querySelector('li.add input[type="text"]').value = '';
    }

};

function del (elem_id, belonging) {
    let request_data = `id=${elem_id}`;
    change('del', request_data);

    this_li = document.querySelector('#id'+elem_id);
    this_hr = this_li.nextElementSibling;
    if (document.querySelector('#'+belonging).querySelectorAll('li:not(.add):not(#id'+elem_id+')').length == 0) {
        let li_no_plans = document.createElement('li');
        li_no_plans.classList.add('no_plans');
        li_no_plans.innerHTML = '<p> Пока тут ничего нет </p>';
        let hr_no_plans = document.createElement('hr');
        hr_no_plans.classList.add('no_plans');
        this_li.before(li_no_plans);
        this_li.before(hr_no_plans);
    }
    this_li.parentNode.removeChild(this_li);
    this_hr.parentNode.removeChild(this_hr);
};

function check (el, elem_id) {
    let request_data = `id=${elem_id}&checked=${el.checked}`;
    change('check', request_data);
}

function calendar_window_close () {
    document.querySelector('.body_shadow').classList.add('hidden');
    let calendar_window = document.querySelector("#calendar_window");
    calendar_window.querySelector('textarea').innerHTML = null;
    let check = calendar_window.querySelector("#check").querySelector("input");
    check.hasAttribute('checked') ? check.removeAttribute('checked') : '';
    let deadline = calendar_window.querySelector("#deadline").querySelector("input");
    deadline.hasAttribute('checked') ? deadline.removeAttribute('checked') : ''; 
    calendar_window.querySelector('#date').querySelector('input').value = null;
    calendar_window.querySelector('#save').setAttribute('onclick', 'calendar_window_save()');
}

function emoji_select (file) {
    let emoji_button = document.querySelector("#calendar_window").querySelector('#emoji_button');
    emoji_button.setAttribute('src', `../src_global/emoji/${file}`)
    emoji_button.setAttribute('data-icon', `${file}`)
}

function calendar_change_set (elem_id, text, is_checked, is_deadline, date, icon) {
    document.querySelector('.body_shadow').classList.remove('hidden');
    let calendar_window = document.querySelector('#calendar_window');
    calendar_window.querySelector('h2').innerHTML = "изменить задачу";
    calendar_window.querySelector('textarea').innerHTML = text;
    let check = calendar_window.querySelector("#check").querySelector("input");
    is_checked ? check.setAttribute('checked', '') : '';
    let deadline = calendar_window.querySelector("#deadline").querySelector("input");
    is_deadline ? deadline.setAttribute('checked', '') : '';
    calendar_window.querySelector('#date').querySelector('input').value = date;
    let emoji_button = calendar_window.querySelector('#emoji_button');
    emoji_button.setAttribute('data-icon', icon);
    icon ? icon = `../src_global/emoji/${icon}` : icon = `../src_global/emoji.svg`;
    emoji_button.setAttribute('src', icon);
    calendar_window.querySelector('#save').setAttribute('onclick', `calendar_window_save(${elem_id})`);
    calendar_window.querySelector('#del').setAttribute('onclick', `calendar_window_del(${elem_id})`);
}

function calendar_add_set (date) {
    document.querySelector('.body_shadow').classList.remove('hidden');
    document.querySelector('#calendar_window').querySelector('h2').innerHTML = "добавить задачу";
    calendar_window.querySelector('#save').setAttribute('onclick', 'calendar_window_save("add")');
    calendar_window.querySelector('#del').setAttribute('onclick', 'calendar_window_del("add")');
}

function calendar_window_del (id) {
    if (id == 'add') {
        alert('Вы не можете удалить не созданную цель!');
    }
    else {
        let request_data = `id=${id}`;
        change('calendar_del', request_data);
        window.location.reload();
    }
}

function calendar_window_save (id) {
    let calendar_window = document.querySelector('#calendar_window');
    let text = calendar_window.querySelector('textarea').value;
    let date = calendar_window.querySelector('#date').querySelector('input').value;
    if (!text) {
        alert('Вы не ввели текст!');
    }
    else if (!date) {
        alert('Вы не ввели дату!');
    }
    else {
        let check = 0;
        calendar_window.querySelector('#check').querySelector('input').checked ? check = 1 : check = 0;
        let deadline = 0;
        calendar_window.querySelector('#deadline').querySelector('input').checked ? deadline = 1 : deadline = 0;
        let icon = calendar_window.querySelector('#emoji_button').getAttribute('data-icon');
        console.log(id)
        if (id == 'add') {
            let request_data = `text=${text}&check=${check}&deadline=${deadline}&date=${date}&icon=${icon}`;
            change('calendar_add', request_data);
        }
        else {
            let request_data = `id=${id}&text=${text}&check=${check}&deadline=${deadline}&date=${date}&icon=${icon}`;
            change('calendar_change', request_data);
        }
        window.location.reload();
    }
}