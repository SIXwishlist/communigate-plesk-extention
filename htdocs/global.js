document.addEventListener("DOMContentLoaded", function(event) {

    // Disable scrollbars for the plugin, cuz it's ugly
    var iframes = document.getElementsByTagName("iframe");

    if (iframes.length > 0) {
        for (var i = 0; i < iframes.length; i++) {
            iframes[i].setAttribute("scrolling", "no");
        };
    }

    //add a new tab to the menu
    window.onload = function(){

        // Create elements for the tab
        var newTab = document.createElement("li"),
        link = document.createElement("a"),
        text = document.createElement("span"),
        obj = document.getElementById('navbar');

        link.setAttribute("href", "/modules/communigate");
        text.innerHTML = "Unified Messaging";

        if (obj !== null) {
            var navbar = obj.firstChild,
            liNodes = 0;

            for (var i = 0; i < navbar.childNodes.length; i++) {
                if (navbar.childNodes[i].nodeName == "LI") {
                    liNodes ++;
                }
            }

            if (liNodes > 1) {

                link.appendChild(text);
                newTab.appendChild(link);
                navbar.appendChild(newTab);
            };
        };

        var btnRemove = document.getElementsByClassName('btnRemove');

        if (btnRemove !== null) {

            for (var i = 0; i < btnRemove.length; i++) {
                btnRemove[i].onclick = function() {
                    return confirm("Are you sure?");
                }
            };
        };

        var datepickr=function(){function q(a,c){var b=0,j=a.length;for(b;b<j&&!1!==c(a[b],b);b++);}function p(a,c,b){a.addEventListener?a.addEventListener(c,b,!1):a.attachEvent&&a.attachEvent("on"+c,function(c){var d=c=c||window.event;c.preventDefault=function(){d.returnValue=!1};var f=c;c.stopPropagation=function(){f.cancelBubble=!0};c.target=c.srcElement;b.call(a,c)})}function d(a,c,b){a in r||(r[a]=document.createElement(a));a=r[a].cloneNode(!1);if(null!=c)for(var d in c)a[d]=c[d];null!=b&&("object"==
            typeof b?a.appendChild(b):a.innerHTML=b);return a}function m(a,c,b){return!0==c?b[a]:3<b[a].length?b[a].substring(0,3):b[a]}function s(){for(;this.calendarBody.hasChildNodes();)this.calendarBody.removeChild(this.calendarBody.lastChild);var a=(new Date(this.currentYearView,this.currentMonthView,1)).getDay(),c=k.month.numDays(this.currentMonthView,this.currentYearView);this.currentMonth.innerHTML=k.month.string(this.config.fullCurrentMonth,this.currentMonthView,this.config.months)+" "+this.currentYearView;
        this.calendarBody.appendChild(t(a,c,this.currentMonthView,this.currentYearView))}function t(a,c,b,j){var h=document.createDocumentFragment(),f=d("tr"),g=0,e;for(e=1;e<=a;e++)f.appendChild(d("td",null,"&nbsp;")),g++;for(e=1;e<=c;e++)7==g&&(h.appendChild(f),f=d("tr"),g=0),a=e==k.current.day()&&b==k.current.month.integer()&&j==k.current.year()?{className:"today"}:null,f.appendChild(d("td",a,d("span",{className:"day"},e))),g++;for(e=1;e<=7-g;e++)f.appendChild(d("td",null,"&nbsp;"));h.appendChild(f);return h}
        var u=[],l=new Date,k={current:{year:function(){return l.getFullYear()},month:{integer:function(){return l.getMonth()},string:function(a,c){var b=l.getMonth();return m(b,a,c)}},day:function(){return l.getDate()}},month:{string:function(a,c,b){return m(c,a,b)},numDays:function(a,c){return 1==a&&!(c&3)&&(c%100||!(c%400))?29:v[a]}}},v=[31,28,31,30,31,30,31,31,30,31,30,31],r=[],w=function(a){if(a.target.className)switch(a.target.className){case "prev-month":case "prevMonth":this.currentMonthView--;0>
        this.currentMonthView&&(this.currentYearView--,this.currentMonthView=11);s.call(this);break;case "next-month":case "nextMonth":this.currentMonthView++;11<this.currentMonthView&&(this.currentYearView++,this.currentMonthView=0);s.call(this);break;case "day":var c=this.element;a=(new Date(this.currentYearView,this.currentMonthView,a.target.innerHTML)).getTime();var b=this.config,d="",h=new Date(a),f={d:function(){var a=f.j();return 10>a?"0"+a:a},D:function(){return b.weekdays[f.w()].substring(0,3)},
        j:function(){return h.getDate()},l:function(){return b.weekdays[f.w()]},S:function(){return b.suffix[f.j()]||b.defaultSuffix},w:function(){return h.getDay()},F:function(){return m(f.n(),!0,b.months)},m:function(){var a=f.n()+1;return 10>a?"0"+a:a},M:function(){return m(f.n(),!1,b.months)},n:function(){return h.getMonth()},Y:function(){return h.getFullYear()},y:function(){return f.Y().toString().substring(2,4)}},g=b.dateFormat.split("");q(g,function(a,b){f[a]&&"\\"!=g[b-1]?d+=f[a]():"\\"!=a&&(d+=a)});
        c.value=d;this.close()}},x=function(a){if(a.target!=this.element&&a.target!=this.calendar&&(a=a.target.parentNode,a!=this.calender))for(;a!=this.calendar;)if(a=a.parentNode,null==a){this.close();break}};return function(a,c){var b=this;this.element=document.getElementById(a);this.config={fullCurrentMonth:!0,dateFormat:"F jS, Y",weekdays:"Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),months:"January February March April May June July August September October November December".split(" "),
        suffix:{1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"},defaultSuffix:"th"};this.currentYearView=k.current.year();this.currentMonthView=k.current.month.integer();if(c)for(var j in c)this.config.hasOwnProperty(j)&&(this.config[j]=c[j]);this.documentClick=function(a){x.call(b,a)};this.open=function(){p(document,"click",b.documentClick);q(u,function(a){a!=b&&a.close()});b.calendar.style.display="block"};this.close=function(){var a=b.documentClick;document.removeEventListener?document.removeEventListener("click",
            a,!1):document.detachEvent&&document.detachEvent("onclick",a);b.calendar.style.display="none"};j=(new Date(this.currentYearView,this.currentMonthView,1)).getDay();var h=k.month.numDays(this.currentMonthView,this.currentYearView),f=this,g=inputTop=0,e=this.element;if(e.offsetParent){do g+=e.offsetLeft,inputTop+=e.offsetTop;while(e=e.offsetParent)}e=d("div",{className:"calendar"});e.style.cssText="display: none; position: absolute; top: "+(inputTop+this.element.offsetHeight)+"px; left: "+g+"px; z-index: 100;";
        this.currentMonth=d("span",{className:"current-month"},k.month.string(this.config.fullCurrentMonth,this.currentMonthView,this.config.months)+" "+this.currentYearView);var g=d("div",{className:"months"}),n=d("span",{className:"prev-month"},d("span",{className:"prevMonth"},"&lt;")),m=d("span",{className:"next-month"},d("span",{className:"nextMonth"},"&gt;"));g.appendChild(n);g.appendChild(m);g.appendChild(this.currentMonth);var n=this.config.weekdays,l=document.createDocumentFragment();q(n,function(a){l.appendChild(d("th",
            {},a.substring(0,2)))});n=d("table",null,d("thead",null,d("tr",{className:"weekdays"},l)));this.calendarBody=d("tbody");this.calendarBody.appendChild(t(j,h,this.currentMonthView,this.currentYearView));n.appendChild(this.calendarBody);e.appendChild(g);e.appendChild(n);document.body.appendChild(e);p(e,"click",function(a){w.call(f,a)});this.calendar=e;u.push(this);"INPUT"==this.element.nodeName?p(this.element,"focus",this.open):p(this.element,"click",this.open)}}();


        var datePickField = document.getElementById('datepick');

        if (datePickField !== null) {

            new datepickr('datepick', {
                'dateFormat': 'd M Y'
            });
        };

        var accountDropdown = document.getElementById('accountDropdown');
        if (accountDropdown !== null) {
            (accountDropdown.onchange = function (argument) {
                var account = $("accountDropdown").getValue();
                if (account !== 'Please Choose') {
                    window.location = '/modules/communigate/index.php/remote-Pop/index/account/' + account;
                } else{};
            })();
        }; 

        var i = 1;
        if ($('btn-addFilter') !== null) {
            $('btn-addFilter').addClassName('addFilterClass');
            $('btn-removeFilter').addClassName('removeFilterClass');
            $('parameterFilter-form-row').addClassName('zeroHeight');
            $('btn-addFilter').observe('click', duplicateFormFields);
        };

        function duplicateFormFields (event) {
            var htmlForData = $('dataFilter-form-row').innerHTML.replace('Rules', '');
            htmlForData = htmlForData.replace(/dataFilter/g , 'dataFilter_' + i);
            
            var htmlForOperation = $('oprationFilter-form-row').innerHTML;
            htmlForOperation = htmlForOperation.replace(/oprationFilter/g, 'oprationFilter_' + i);
            
            var htmlForParameter = $('parameterFilter-form-row').innerHTML;
            htmlForParameter = htmlForParameter.replace(/parameterFilter/g, 'parameterFilter_' + i); 

            var htmlForAddFilterButton = $('btn-addFilter').innerHTML;
            htmlForRemoveFilterButton = $('btn-removeFilter').innerHTML;

            var dataFilterElement = new Element('div', {'class': 'form-row', id: 'dataFilter-form-row_' + i}).update(htmlForData);
            var operationFilterElement = new Element('div', {'class': 'form-row', id: 'oprationFilter-form-row_' + i}).update(htmlForOperation);
            var parameterFilterElement = new Element('div', {'class': 'form-row zeroHeight', id: 'parameterFilter-form-row_' + i}).update(htmlForParameter);
            var addFilterElement = new Element('span', {'class': 'btn addFilterClass', id: 'btn-addFilter_' + i}).update(htmlForAddFilterButton);
            var removeFilterElement = new Element('span', {'class': 'btn removeFilterClass', id: 'btn-removeFilter_' + i}).update(htmlForRemoveFilterButton);

            addFilterElement.observe('click', duplicateFormFields);
            removeFilterElement.observe('click', removeFormFields);

            if (i == 1) {
                $('btn-removeFilter').insert({after: dataFilterElement});
                $('dataFilter-form-row_' + i).insert({after: operationFilterElement});
                $('oprationFilter-form-row_' + i).insert({after: parameterFilterElement});
                $('parameterFilter-form-row_' + i).insert({after: addFilterElement})
                $('btn-addFilter_' + i).insert({after: removeFilterElement});
            } else{
                $('btn-removeFilter_' + (i - 1)).insert({after: dataFilterElement});
                $('dataFilter-form-row_' + i).insert({after: operationFilterElement});
                $('oprationFilter-form-row_' + i).insert({after: parameterFilterElement});
                $('parameterFilter-form-row_' + i).insert({after: addFilterElement})
                $('btn-addFilter_' + i).insert({after: removeFilterElement});
            };
            i++;
        }

        function removeFormFields (event) {
            var elementID = this.readAttribute('id');
            var elementToRemove = $$("[id$=" + elementID.slice(-2) + ']')
            elementToRemove
            for (var j = 0; j < elementToRemove.length; j++) {
                elementToRemove[j].remove();
            };        
            i--;
        }

        if ($('btn-addAction') !== null) {
            $('actionParameter-form-row').addClassName('zeroHeight');
            $('btn-addAction').observe('click', duplicateFields);
        };

        var counter = 1;
        function duplicateFields (event) {
            var htmlForActionFilter = $('actionFilter-form-row').innerHTML.replace('Actions', '');
            htmlForActionFilter = htmlForActionFilter.replace(/actionFilter/g , 'actionFilter_' + counter);
            
            var htmlForActionOperation = $('actionParameter-form-row').innerHTML;
            htmlForActionOperation = htmlForActionOperation.replace(/actionParameter/g, 'actionParameter_' + counter);

            var htmlForAddActionButton = $('btn-addAction').innerHTML;
            htmlForRemoveActionButton = $('btn-removeAction').innerHTML;

            var actionFilterElement = new Element('div', {'class': 'form-row', id: 'actionFilter-form-row_' + counter}).update(htmlForActionFilter);
            var operationActionElement = new Element('div', {'class': 'form-row zeroHeight', id: 'actionParameter-form-row_' + counter}).update(htmlForActionOperation);
            var addActionElement = new Element('span', {'class': 'btn addActionClass', id: 'btn-addAction_' + counter}).update(htmlForAddActionButton);
            var removeActionElement = new Element('span', {'class': 'btn removeActionClass', id: 'btn-removeAction_' + counter}).update(htmlForRemoveActionButton);

            addActionElement.observe('click', duplicateFields);
            removeActionElement.observe('click', removeFields);

            if (counter == 1) {
                $('btn-removeAction').insert({after: actionFilterElement});
                $('actionFilter-form-row_' + counter).insert({after: operationActionElement});
                $('actionParameter-form-row_' + counter).insert({after: addActionElement});
                $('btn-addAction_' + counter).insert({after: removeActionElement});
            } else{
                $('btn-removeAction_' + (counter - 1)).insert({after: actionFilterElement});
                $('actionFilter-form-row_' + counter).insert({after: operationActionElement});
                $('actionParameter-form-row_' + counter).insert({after: addActionElement});
                $('btn-addAction_' + counter).insert({after: removeActionElement});
            };
            counter++;
        }

        function removeFields (event) {
            var element = this.readAttribute('id');
            var elementTo = $$("[id$=" + element.slice(-2) + ']')
            elementTo
            for (var p = 0; p < elementTo.length; p++) {
                elementTo[p].remove();
            };        
            counter--;
        }

    };
});
