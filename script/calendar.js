function showCalendar(idinput, container, context, div) {
	var dialog, calendar;
		
		calendar = new YAHOO.widget.Calendar(div, {
            iframe:false,          // Turn iframe off, since container has iframe support.
        hide_blank_weeks:true  // Enable, to demonstrate how we handle changing height, using changeContent
    });

    function okHandler() {
        if (calendar.getSelectedDates().length > 0) {

            var selDate = calendar.getSelectedDates()[0];

            // Pretty Date Output, using Calendar's Locale values: Friday, 8 February 2008
            var wStr = calendar.cfg.getProperty("WEEKDAYS_LONG")[selDate.getDay()];
            var dStr = selDate.getDate();
            //var mStr = calendar.cfg.getProperty("MONTHS_LONG")[selDate.getMonth()];
            var mStr = selDate.getMonth() + 1;
            var yStr = selDate.getFullYear();
            
            dStr = "" + dStr;
            mStr = "" + mStr;
            if (dStr.length < 2) { dStr = "0" + dStr; }
            if (mStr.length < 2) { mStr = "0" + mStr; }

            //YAHOO.util.Dom.get(idinput).value = wStr + ", " + dStr + " " + mStr + " " + yStr;
            YAHOO.util.Dom.get(idinput).value = dStr + "/" + mStr + "/" + yStr;
        } else {
            YAHOO.util.Dom.get(idinput).value = "";
        }
        this.hide();
    }

    function cancelHandler() {
        this.hide();
    }

    dialog = new YAHOO.widget.Dialog(container, {
        context:[context, "tl", "bl"],
        buttons:[ {text:"Select", isDefault:true, handler: okHandler}, 
                  {text:"Cancel", handler: cancelHandler}],
        width:"16em",  // Sam Skin dialog needs to have a width defined (7*2em + 2*1em = 16em).
        draggable:false,
        close:true
    });
    calendar.render();
    dialog.render();    
}