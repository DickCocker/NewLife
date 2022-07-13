function emoji_select_group (el, group) {
    let li_selected = document.querySelector('#emoji').querySelector('li.li_selected');
    if (li_selected) {
        li_selected.classList.remove('li_selected');
    }
    document.querySelector('#emoji').querySelector('#emoji_list').innerHTML = '';

    el.classList.add('li_selected');
    let request = new XMLHttpRequest();
    request.open('POST', '../layout/get_emoji.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`group=${group}`);
    request.addEventListener('loadend', function () {
        try {
            let emoji_list_div = document.querySelector('#emoji').querySelector('#emoji_list');
            let answer = JSON.parse(request.response);
            let group = answer['group'];
            answer = Object.values(answer);
            for (i=0; i <= answer.length; i++) {
                let file = answer[i];
                if(file != group && file != undefined) {
                    let emoji = document.createElement('img');
                    emoji.setAttribute('src', `../src_global/emoji/${group}/${file}`);
                    emoji.setAttribute('onclick', `emoji_select('${group}/${file}')`);
                    emoji_list_div.append(emoji);
                }
            }
        }
        catch (error) {
            console.error(error);
        }
    })
}