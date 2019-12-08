My_Paging = function (total_count,   cur_page) {

    mod_page = 0;
    total_page= 0;
    pageSize = 10;
        total_page = parseInt(parseInt(total_count)/parseInt(pageSize));
        mod_page = parseInt(total_count) % parseInt(pageSize);
        if(mod_page !=0) {
            total_page = total_page +1;
        }

    if(cur_page > total_page)
        cur_page=total_page;
    prev_page = parseInt(cur_page) - 1;
    if (prev_page < 1)
        prev_page = 1;
    next_page = parseInt(cur_page) + 1;
    if (next_page > total_page)
        next_page = total_page;

    class_first = '';
    class_prev = '';
    class_next = '';
    class_last = '';

    page_per_count_statue = "display: inline-block;";
    if(total_count < 11) {
        class_first = 'disabled';
        class_prev = 'disabled';
        class_next = 'disabled';
        class_last = 'disabled';
        page_per_count_statue = "display: none;";
    }

    page_counts = 6;
    var html = "";

    start_page = cur_page - (parseInt)(page_counts / 2);
    end_page = cur_page + (parseInt)(page_counts / 2);
    if (start_page < 1) {
        start_page = 1;
        if (page_counts < total_page)
            end_page = page_counts;
        else
            end_page = total_page;
    }
    if (end_page > total_page) {
        end_page = total_page;
        if (page_counts < total_page)
            start_page = total_page - page_counts + 1;
        else
            start_page = 1;
    }

    page = start_page;
    if(total_page ==1){
        end_page = 0;
    }
    class_pagination = '';
    if(total_page == 1)
        class_pagination = 'hidden';

    html+='<ul class="list-unstyled" style="display: inline-block;">';
    html+='<li class="'+class_first+'"><a href="javascript:my_go_page(1)">❮❮</a></li>';
    html+='<li class="'+class_prev+'"><a href="javascript:my_go_page('+ prev_page+')">❮</a></li>';
    for (page = start_page; page <= end_page; page++) {
        active = '';
        if (page == cur_page)
            active = 'active';
        html+='<li class="'+active+'"><a href="javascript:my_go_page('+page+')">'+page+'</a></li>';
    }
    html+='<li class="'+class_next+'"><a href="javascript:my_go_page('+ next_page+')">❯</a></li>';
    html+='<li class="'+class_last+'"><a href="javascript:my_go_page('+ total_page+')">❯❯</a></li>';
    html+='</ul>';
    html+='<div style="'+page_per_count_statue+' margin-left: 30px;font-size: 14px;">';

    html+='</div>';
    return html;
}

Public_Paging = function (total_count,  pageSize, cur_page) {

    mod_page = 0;
    total_page= 0;

    total_page = parseInt(parseInt(total_count)/parseInt(pageSize));
    mod_page = parseInt(total_count) % parseInt(pageSize);
    if(mod_page !=0) {
        total_page = total_page +1;
    }

    if(cur_page > total_page)
        cur_page=total_page;
    prev_page = parseInt(cur_page) - 1;
    if (prev_page < 1)
        prev_page = 1;
    next_page = parseInt(cur_page) + 1;
    if (next_page > total_page)
        next_page = total_page;

    class_first = '';
    class_prev = '';
    class_next = '';
    class_last = '';

    page_per_count_statue = "display: inline-block;";
    if(total_count < 11) {
        class_first = 'disabled';
        class_prev = 'disabled';
        class_next = 'disabled';
        class_last = 'disabled';
        page_per_count_statue = "display: none;";
    }

    page_counts = 6;
    var html = "";

    start_page = cur_page - (parseInt)(page_counts / 2);
    end_page = cur_page + (parseInt)(page_counts / 2);
    if (start_page < 1) {
        start_page = 1;
        if (page_counts < total_page)
            end_page = page_counts;
        else
            end_page = total_page;
    }
    if (end_page > total_page) {
        end_page = total_page;
        if (page_counts < total_page)
            start_page = total_page - page_counts + 1;
        else
            start_page = 1;
    }

    page = start_page;
    if(total_page ==1){
        end_page = 0;
    }
    class_pagination = '';
    if(total_page == 1)
        class_pagination = 'hidden';

    html+='<ul class="list-unstyled" style="display: inline-block;">';
    html+='<li class="'+class_first+'"><a href="javascript:public_go_page(1)">❮❮</a></li>';
    html+='<li class="'+class_prev+'"><a href="javascript:public_go_page('+ prev_page+')">❮</a></li>';
    for (page = start_page; page <= end_page; page++) {
        active = '';
        if (page == cur_page)
            active = 'active';
        html+='<li class="'+active+'"><a href="javascript:public_go_page('+page+')">'+page+'</a></li>';
    }
    html+='<li class="'+class_next+'"><a href="javascript:public_go_page('+ next_page+')">❯</a></li>';
    html+='<li class="'+class_last+'"><a href="javascript:public_go_page('+ total_page+')">❯❯</a></li>';
    html+='</ul>';
    html+='<div style="'+page_per_count_statue+' margin-left: 30px;font-size: 14px;">';

    html+='</div>';
    return html;
}

