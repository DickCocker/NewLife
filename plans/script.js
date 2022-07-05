function get_id (operation) {
    let cookie = document.cookie.split('; ');
    let key_list = [];
    for (let i = 0; i < cookie.length; i++) {
        let key = cookie[i];
        key = key.split('=')[0];
        key_list.push(key);
    }
    let id_list = [];
    for (let i = 0; i < key_list.length; i++) {
        let key = key_list[i];
        if (key.substring(0, key.indexOf('_')) == operation) {
            let id = key.substring(key.indexOf('_')+1, key.length);
            if (operation == 'add') {
                id = Number(id.split('_')[1]);
            }
            id_list.push(id);
        }
    }
    id = -1;
    for (let i = 0; i < id_list.length; i++) {
        let tmp = id_list[i];
        if (tmp > id) {
            id = tmp;
        }
    }
    id++;
    return id;
};

function add (belonging, p_id) {
    let text = document.querySelector('#'+belonging).querySelector('li.add input[type="text"]').value;
    if (text === '') {
        alert('Вы не ввели текст!');
    }
    else {
        id = get_id('add');
        document.cookie = 'add_'+belonging+'_'+id+'='+text;
        
        let add_li = document.querySelector('#'+belonging).querySelector('li.add');
        let new_li = document.createElement('li');
        new_li.innerHTML = "<input type='checkbox'> <p>"+text+"</p> <button class='del'> <img src='../"+p_id+"/src/delete.svg' alt='delete'> </button>";
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
    id = get_id('del');
    document.cookie = 'del_'+id+'='+elem_id;

    this_li = document.querySelector('#id'+elem_id);
    this_hr = this_li.nextElementSibling;
    console.log(typeof document.querySelector('#'+belonging).querySelectorAll('li:not(.add):not(#id'+elem_id+')'));
    console.log(document.querySelector('#'+belonging).querySelectorAll('li:not(.add):not(#id'+elem_id+')'));
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
    document.cookie = 'check_'+elem_id+'='+el.checked;
}